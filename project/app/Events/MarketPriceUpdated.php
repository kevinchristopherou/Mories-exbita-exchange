<?php

namespace App\Events;

use App\Http\Resources\Market as MarketResource;
use App\Models\Market;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * This class used to broadcast when the market price is updated
 *
 */

class MarketPriceUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $market = null;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Market $market)
    {
        $this->market = new MarketResource($market);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('markets');
    }
}
