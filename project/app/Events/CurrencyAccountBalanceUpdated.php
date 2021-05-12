<?php

namespace App\Events;

use App\Models\Currency;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * This class used to broadcast when the balance is updated
 *
 */

class CurrencyAccountBalanceUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $currency = null;

    public function __construct($currency_id)
    {
        $this->currency = Currency::where('id', $currency_id)
            ->pendingWithdraws()
            ->first()
            ->_toArrayAdmin();
    }

    public function broadcastOn()
    {
        return new PrivateChannel('admin');
    }
}
