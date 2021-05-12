<?php

namespace App\Models;

use App\Traits\FieldUpdateTrait;
use App\Traits\HasMoneyFieldsTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use SoftDeletes, FieldUpdateTrait, HasMoneyFieldsTrait;

    protected $table = 'wallets';

    protected $cryptoFields = ['available_balance', 'pending_balance', 'withdraw_pending_balance'];

    protected $decimalsFrom = '';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function cryptoAddress()
    {
        return $this->hasOne(WalletAddress::class);
    }

    /**
     * Fail safe method for getting the fiat wallet of a user for given fiat currency. It createds the wallet record if it does not exist.
     * @param $user_id
     * @param $fiat_currency_id
     * @return FiatWallet
     */
    public static function getUserWallet($user_id, $currency_id)
    {
        $wallet = self::where('user_id', $user_id)->where('currency_id', $currency_id)->first();

        if ($wallet == null) {
            $wallet = new self();
            $wallet->user_id = $user_id;
            $wallet->currency_id = $currency_id;
            $wallet->save();
        }

        return $wallet;
    }
}
