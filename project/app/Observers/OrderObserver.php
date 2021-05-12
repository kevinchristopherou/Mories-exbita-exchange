<?php

namespace App\Observers;

use App\Events\OrderBookUpdated;
use App\Events\OrderUpdated;
use App\Models\Order;
use App\Services\Broker;
use App\Services\MarketManager;

class OrderObserver
{
    /**
     * Listen to the Order created event.
     *
     * @param  \App\Models\Order $order
     * @return void
     */
    public function created(Order $order)
    {
        //Do not broadcast quick buy/sell orders
        if (! $order->isQuick()) {
            event(new OrderUpdated($order, 'created'));
            event(new OrderBookUpdated($order, 'created'));
        }

        // Set requested amount of the user balance to pending status
        $broker = new Broker($order);
        $broker->updateUserBalanceForOrder($order);

        $broker->matchOrder($order);

        if (! $order->isQuick()) {
            MarketManager::updateBidAsk($order->market);
        }
    }

    public function deleted(Order $order)
    {
        MarketManager::updateBidAsk($order->market);
        event(new OrderUpdated($order, 'cancel'));
        event(new OrderBookUpdated($order, 'cancel'));
    }
}
