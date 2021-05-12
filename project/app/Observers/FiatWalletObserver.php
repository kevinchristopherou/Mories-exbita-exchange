<?php

namespace App\Observers;

use App\Events\FiatWalletUpdated;
use App\Models\FiatWallet;

class FiatWalletObserver
{
    public function updated(FiatWallet $wallet)
    {
        event(new FiatWalletUpdated($wallet));
    }
}
