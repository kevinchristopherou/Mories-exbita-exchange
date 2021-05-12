<?php

namespace App\Events;

use App\Http\Resources\MarketHistory;
use App\Models\Transaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * This class used to broadcast when the market history is updated
 *
 */

class MarketHistoryUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $t;

    private $marketName;

    public function __construct(Transaction $transaction)
    {
        $this->t = new MarketHistory($transaction);
        $this->marketName = $transaction->market;
    }

    public function broadcastOn()
    {
        return new Channel('orderbook-'.$this->marketName);
    }
}
