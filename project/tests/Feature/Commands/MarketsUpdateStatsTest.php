<?php

namespace Tests\Feature\Commands;

use App\Models\Market;
use App\Services\OrderManager;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Tests\ApiTestCase;

class MarketsUpdateStatsTest extends ApiTestCase
{
    public function testMarketsStatsUpdated()
    {
        $this->createOrders();
        Artisan::call('markets:updatestats');

        $market = Market::where('name', 'BTC-USD')->first();

        $this->assertEquals(1.15488243, $market->volume_24h_decimal);
        $this->assertEquals(30000.00, $market->low_24h_decimal);
        $this->assertEquals(30450.00, $market->high_24h_decimal);
        $this->assertEquals(30300, $market->bid_decimal);
        $this->assertEquals(30450, $market->ask_decimal);
        $this->assertEquals(30450, $market->last_decimal);
        $this->assertEquals(450, $market->change_24h_decimal);
        $this->assertEquals(1.5, $market->change_24h_percent);
    }

    private function createOrders()
    {
        $market = Market::where('name', 'BTC-USD')->first();

        $buyer1 = User::find(1);
        $buyer2 = User::find(2);
        $buyer3 = User::find(3);

        $seller1 = User::find(4);
        $seller2 = User::find(5);
        $seller3 = User::find(5);

        Carbon::setTestNow(Carbon::parse('-25 hours'));
        OrderManager::create('SELL_LIMIT', $seller1, $market->name, 29000, 0.1);
        OrderManager::create('SELL_LIMIT', $seller2, $market->name, 30000, 0.5);
        OrderManager::create('BUY_LIMIT', $buyer1, $market->name, 30100, 0.5);
        Carbon::setTestNow(Carbon::parse('+4 hours'));
        OrderManager::create('BUY_LIMIT', $buyer2, $market->name, 30300, 0.6);
        Carbon::setTestNow(Carbon::parse('+1 hours'));
        OrderManager::create('SELL_LIMIT', $seller3, $market->name, 30250, 0.1);
        Carbon::setTestNow(Carbon::parse('+1 hours'));
        OrderManager::create('SELL_LIMIT', $seller3, $market->name, 30300, 0.1);
        Carbon::setTestNow(Carbon::parse('+1 hours'));
        OrderManager::create('SELL_LIMIT', $seller1, $market->name, 30450, 1);
        Carbon::setTestNow();
        OrderManager::create('BUY', $buyer3, $market->name, null, 20000);
        OrderManager::create('BUY_LIMIT', $buyer2, $market->name, 30500, 0.2);
    }
}
