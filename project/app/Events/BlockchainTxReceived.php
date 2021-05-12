<?php

namespace App\Events;

use App\Models\Tx\Tx;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * This class used to broadcast received transactions
 *
 */

class BlockchainTxReceived implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tx;

    public function __construct(Tx $tx)
    {
        $tx->load('wallet');
        $this->tx = $tx;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user-'.$this->tx->user_id);
    }

    public function broadcastWith()
    {
        $tx = $this->tx;

        return [
            'amount' => $tx->amount_decimal,
            'address' => $tx->address,
            'symbol' => $tx->wallet->currency->symbol,
        ];
    }
}
