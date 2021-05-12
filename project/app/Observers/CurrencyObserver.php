<?php

namespace App\Observers;

use App\Models\Currency;
use App\Services\WalletManager;
use App\Services\Api\Eth;
use App\User;

class CurrencyObserver
{
    /**
     * Listen to the Currency created event.
     *
     * @param  \App\Models\Currency $currency
     * @return void
     */
    public function created(Currency $currency)
    {
        if($currency->type == Currency::TYPE_ERC_20_TOKEN) {
            Eth::attachTokenWatcher([
                'contract' => $currency->extra_data,
                'symbol' => $currency->symbol
            ]);
        }

        // Create wallets for the new currency for all users, use chunk to avoid memory leak (todo: run as job)
        User::chunk(1000, function ($users) use ($currency) {
            foreach ($users as $user) {
                //Create wallet only if wallet does not exist.
                if ($user->getWallet($currency->id) === null) {
                    WalletManager::assignWallet($currency, $user);
                }
            }
        });
    }
}
