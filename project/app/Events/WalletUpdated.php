<?php

namespace App\Events;

use App\Models\Wallet;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * This class used to broadcast when the wallet is updated
 *
 */

class WalletUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $wallet = null;

    public function __construct(Wallet $wallet)
    {
        $this->wallet = $wallet->fresh();
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user-'.$this->wallet->user_id);
    }

    public function broadcastWith()
    {
        return ['wallet' => new \App\Http\Resources\Wallet($this->wallet)];
    }
}
