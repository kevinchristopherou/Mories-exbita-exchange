<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * This class used to broadcast when the orderbook is updated
 *
 */

class OrderBookUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public $action;

    private $marketName;

    public function __construct(Order $order, $action)
    {
        $this->order = $order->toArrayOrderBook();
        $this->action = $action;
        $this->marketName = $order->market->name;
    }

    public function broadcastOn()
    {
        return new Channel('orderbook-'.$this->marketName);
    }
}
