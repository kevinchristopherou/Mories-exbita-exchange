<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * This class used to broadcast when the order data is updated
 *
 */

class OrderUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public $action;

    private $user_id;

    public function __construct(Order $order, $action)
    {
        $this->order = $order->toArrayMyOrders();
        $this->user_id = $order->user_id;
        $this->action = $action;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user-'.$this->user_id);
    }
}
