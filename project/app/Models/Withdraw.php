<?php

namespace App\Models;

use App\Facades\Api\GatewayBtc;
use App\Facades\Api\GatewayCoinpayments;
use App\Facades\Api\GatewayEth;
use App\Helpers\MoneyHelper;
use App\Helpers\WalletNonceHelper;
use App\Traits\HasMoneyFieldsTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Jedrzej\Searchable\SearchableTrait;

class Withdraw extends Model
{
    const STATUS_APPROVED = 'approved';
    const STATUS_QUEUED = 'queued';
    const STATUS_REJECTED = 'rejected';

    use HasMoneyFieldsTrait;
    use SearchableTrait;

    public $searchable = ['id', 'currency_id', 'amount', 'address', 'status', 'status', 'created_at', 'user:email'];

    protected $cryptoFields = ['amount', 'fee'];

    protected $table = 'withdrawal';

    protected $decimalsFrom = '';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'evaluated_at';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_user_id');
    }

    public function currency()
    {
        return $this->belongsTo(\App\Models\Currency::class, 'currency_id');
    }

    public function walletTransaction()
    {
        return $this->belongsTo(WalletTransaction::class, 'wallet_transaction_id');
    }

    public function proceed()
    {
        $txModel = null;
        $txId = null;
        $txSender = null;
        $txFee = 0;
        $status = Withdraw::STATUS_APPROVED;

        if ($this->currency_id === Currency::ID_BTC) {

            $txId = GatewayBtc::send($this->address, $this->amount_decimal);
            Artisan::call('blockchain:updatebalance_btc');
            $txFee = GatewayBtc::getSendTxFee($txId);

            $this->proceedTransaction($txId, $txFee);

        } elseif ($this->currency_id === Currency::ID_ETH || $this->currency->type == Currency::TYPE_ERC_20_TOKEN) {
            $accountAddress = GatewayEth::getAccountWallet();
            $nonce = WalletNonceHelper::calculateNonce($accountAddress);

            $amountInWei = MoneyHelper::parseCrypto($this->amount_decimal, $this->currency->decimals, false);

            $data = [
                'address' => $this->address,
                'amount' => $this->amount_decimal,
                'amount_decimals' => $amountInWei->getAmount(),
                'id' => $this->id,
                'nonce' => $nonce,
                'contract' => $this->currency->type == Currency::TYPE_ERC_20_TOKEN ? $this->currency->extra_data : false,
                'decimals' => $this->currency->decimals
            ];

            GatewayEth::send($data);
            // Tx id will be updated with scheduler command
            $txId = null;
            Artisan::call('blockchain:updatebalance_eth');
            //@ToDo Fee
            $txFee = 0; //GatewayEth::getSendTxFee($txId);

            $this->proceedTransaction($txId, $txFee);

        } elseif($this->currency->type == Currency::TYPE_COINPAYMENTS) {
            $this->txsender = GatewayCoinpayments::send($this->address, $this->dest_tag, $this->amount_decimal, $this->currency);
            $this->save();

            $status = Withdraw::STATUS_QUEUED;
        }

        return $status;
    }

    public function proceedTransaction($txId, $txFee)
    {
        $currency = Currency::find($this->currency_id);

        $totalAmount = $this->amount->add($this->fee);
        $wallet = $this->getWallet();
        $wallet->decrease('withdraw_pending_balance', $totalAmount->getAmount());

        //Save transaction in wallet transactions, which is used to show the deposit/withdraws to user
        $walletTransaction = new WalletTransaction();
        $walletTransaction->currency_id = $wallet->currency_id;
        $walletTransaction->type = 'send';
        $walletTransaction->user_id = $wallet->user_id;
        $walletTransaction->other_party_address = $this->address;
        $walletTransaction->amount = $this->amount;
        $walletTransaction->fee = $this->fee;
        $walletTransaction->txid = $txId;
        $walletTransaction->tx_fee = MoneyHelper::parseCrypto($txFee, $currency->decimals);
        $walletTransaction->save();

        $this->wallet_transaction_id = $walletTransaction->id;
        $this->save();
    }

    public function cancel()
    {
        $totalAmount = $this->amount->add($this->fee);

        $wallet = $this->getWallet();
        $wallet->decrease('withdraw_pending_balance', $totalAmount->getAmount());
        $wallet->increase('available_balance', $totalAmount->getAmount());
        $this->status = 'canceled';
        $this->save();
    }

    public function getWallet()
    {
        return Wallet::where('user_id', $this->user_id)->where('currency_id', $this->currency_id)->first();
    }

    public function getTxId()
    {
        return $this->walletTransaction ? $this->walletTransaction->txid : null;
    }

    public function getTxUrl()
    {
        $txId = $this->getTxId();
        if ($txId) {
            if ($this->currency_id === Currency::ID_BTC) {
                return GatewayBtc::getTxLookupUrl($txId);
            }
            if ($this->currency_id === Currency::ID_ETH || $this->currency->type == Currency::TYPE_ERC_20_TOKEN) {
                return GatewayEth::getTxLookupUrl($txId);
            }
            if ($this->currency->type == Currency::TYPE_COINPAYMENTS) {
                return GatewayCoinpayments::getTxLookupUrl($txId, $this->currency->symbol);
            }
        }
    }

    public function scopeApproved($query) {
        return $query->where('status', self::STATUS_APPROVED);
    }
}
