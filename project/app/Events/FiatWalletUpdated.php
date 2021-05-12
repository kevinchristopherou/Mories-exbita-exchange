<?php

namespace App\Events;

use App\Models\FiatWallet;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * This class used to broadcast when fiat wallet updated
 *
 */

class FiatWalletUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $fiatWallet = null;

    public function __construct(FiatWallet $wallet)
    {
        $this->fiatWallet = $wallet->fresh();
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user-'.$this->fiatWallet->user_id);
    }

    public function broadcastWith()
    {
        return ['fiatWallet' => new \App\Http\Resources\FiatWallet($this->fiatWallet)];
    }
}
