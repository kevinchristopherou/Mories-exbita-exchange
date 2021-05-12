<?php

namespace App\Models;

use App\Traits\HasMoneyFieldsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use HasMoneyFieldsTrait;

    const ID_BTC = 1;
    const ID_ETH = 2;

    const TYPE_COIN = 'coin';
    const TYPE_ERC_20_TOKEN = 'erc20token';
    const TYPE_BTC_FORK = 'btc_fork';
    const TYPE_COINPAYMENTS = 'coinpayments';
    const ETH_SYMBOL = 'Eth';

    use SoftDeletes;

    protected $table = 'currencies';

    protected $hidden = ['account_balance'];

    protected $cryptoFields = ['min_deposit', 'min_withdraw', 'fee_withdraw', 'account_balance', 'withdraw_pending'];

    protected $decimalsFrom = false;

    public function scopePendingWithdraws($query)
    {
        $query->selectRaw("*,
            (SELECT sum(amount) 
            FROM withdrawal 
            WHERE currency_id = currencies.id
            AND status IN ('waiting','queued')
            ) as withdraw_pending,
            (SELECT count(id) 
            FROM withdrawal 
            WHERE currency_id = currencies.id
            AND status IN ('waiting','queued')
            ) as withdraw_pending_count");
    }

    public function _toArray()
    {
        return [
            'id' => $this->id,
            'currency' => $this->symbol,
            'currency_long' => $this->name,
            'min_deposit' => $this->min_deposit_decimal,
            'fee_withdraw' => $this->fee_withdraw_decimal,
            'min_withdraw' => $this->min_withdraw_decimal,
        ];
    }

    public function _toArrayAdmin()
    {
        $data = $this->_toArray();
        $data['account_balance'] = $this->account_balance_decimal;
        $data['withdraw_pending'] = $this->withdraw_pending_decimal;
        $data['withdraw_pending_count'] = $this->withdraw_pending_count;

        return $data;
    }

    public function toArray()
    {
        $array = parent::toArray();
        $this->addMoneyFieldToArray($array);

        return $array;
    }
}
