<?php

namespace App\Observers;

use App\Models\FiatCurrency;
use App\Services\FiatWalletManager;
use App\User;

class FiatCurrencyObserver
{
    /**
     * Listen to the FiatCurrency created event.
     *
     * @param  \App\Models\FiatCurrency $currency
     * @return void
     */
    public function created(FiatCurrency $currency)
    {
        // Create fiat wallets for the new fiat currency for all users, use chunk to avoid memory leak (todo: run as job)
        User::chunk(1000, function ($users) use ($currency) {
            foreach ($users as $user) {
                //Create wallet only if wallet does not exist.
                if ($user->getFiatWallet($currency->id) === null) {
                    FiatWalletManager::assignFiatWallet($currency, $user);
                }
            }
        });
    }
}
