<?php

namespace App\Services;

use App\Models\FiatCurrency;
use App\Models\FiatWallet;
use App\Models\Order;
use App\Models\ReferralEarning;
use App\Models\Wallet;
use Money\Money;

class ReferralEarningsManager
{
    /**
     * Store referral earnings.
     * @param Money $referralCut
     * @param Market $market
     * @param Order $order
     * @return void
     */
    public static function addReport($referralCut, $market, $order)
    {
        if($referralCut->getAmount() > 0) {

            $walletType = $market->getQuoteCurrency() instanceof FiatCurrency ? 'fiat' : 'crypto';
            $currency_id = $market->getQuoteCurrency()->id;
            $user_id = $order->user->referrer;

            $referral = new ReferralEarning();
            $referral->referrer_id = $order->user_id;
            $referral->user_id = $user_id;
            $referral->currency_id = $currency_id;
            $referral->currency_type = $walletType;
            $referral->amount = $referralCut;
            $referral->save();

            if($walletType == "fiat") {
                $wallet = FiatWallet::getUserWallet($user_id, $currency_id);
            } else {
                $wallet = Wallet::getUserWallet($user_id, $currency_id);
            }

            if($wallet) {
                $wallet->increase('available_balance', $referralCut->getAmount());
            }
        }
    }
}
