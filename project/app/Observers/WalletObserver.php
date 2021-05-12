<?php

namespace App\Observers;

use App\Events\WalletUpdated;
use App\Models\Wallet;

class WalletObserver
{
    public function updated(Wallet $wallet)
    {
        event(new WalletUpdated($wallet));
    }
}
