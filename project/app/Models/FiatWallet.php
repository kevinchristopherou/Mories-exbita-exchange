<?php

namespace App\Models;

use App\Traits\FieldUpdateTrait;
use App\Traits\HasMoneyFieldsTrait;
use Illuminate\Database\Eloquent\Model;

class FiatWallet extends Model
{
    use FieldUpdateTrait, HasMoneyFieldsTrait;

    protected $fillable = ['fiat_currency_id'];

    protected $cryptoFields = ['available_balance', 'pending_balance', 'withdraw_pending_balance'];

    protected $decimalsFrom = '';

    public function currency()
    {
        return $this->belongsTo(\App\Models\FiatCurrency::class, 'fiat_currency_id');
    }

    /**
     * Fail safe method for getting the fiat wallet of a user for given fiat currency. It createds the wallet record if it does not exist.
     * @param $user_id
     * @param $fiat_currency_id
     * @return FiatWallet
     */
    public static function getUserWallet($user_id, $fiat_currency_id)
    {
        $fiatWallet = self::where('user_id', $user_id)->where('fiat_currency_id', $fiat_currency_id)->first();

        if ($fiatWallet == null) {
            $fiatWallet = new self();
            $fiatWallet->user_id = $user_id;
            $fiatWallet->fiat_currency_id = $fiat_currency_id;
            $fiatWallet->save();
        }

        return $fiatWallet;
    }
}
