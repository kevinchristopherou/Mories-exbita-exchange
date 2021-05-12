<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\FiatCurrency;
use App\Models\Market;
use App\Models\Order;
use App\Models\Transaction;
use App\Services\OrderManager;
use App\User;
use Tests\ApiTestCase;

class OrderControllerTest extends ApiTestCase
{
    /**
     * Get a list of orders.
     *
     * @return void
     */
    public function testGetOrders()
    {
        $market = Market::find(1);

        $buyer = User::find(1);
        $token = $this->getUserToken($buyer);
        $headers = $this->getHeaders($token);

        OrderManager::create('BUY_LIMIT', $buyer, $market->name, 30000, 1);
        OrderManager::create('BUY_LIMIT', $buyer, $market->name, 30000, 1);

        // Get response
        $response = $this->apiGet('orders', $headers);

        // Check response status
        $response->assertStatus(200);

        // Parse received json response
        $data = json_decode($response->getContent(), true);

        // Check if exist data key
        $this->assertArrayHasKey('data', $data);
        $this->assertEquals(2, count($data['data']));
    }

    /**
     * Get a list of orders.
     *
     * @return void
     */
    public function testGetSingleOrder()
    {
        $market = Market::find(1);

        $buyer = User::find(1);
        $token = $this->getUserToken($buyer);
        $headers = $this->getHeaders($token);

        $order = OrderManager::create('BUY_LIMIT', $buyer, $market->name, 30000, 1);

        $response = $this->apiGet('order/'.$order->uuid, $headers);

        // Check response status
        $response->assertStatus(200);
    }

    public function testQuickBuyOrderFailsWhenNoSellOrders()
    {
        $buyer = User::find(1);
        $market = Market::find(1);

        $token = $this->getUserToken($buyer);
        $headers = $this->getHeaders($token);
        $data = [
            'market' => $market->name,
            'quantity' => 10000,
        ];
        //Try with an insufficient balance
        $response = $this->apiPost('order/buy', $data, $headers);

        $r = $response->json();
        $response->assertStatus(422);
        $this->assertEquals($r['success'], false);
        $this->assertArrayHasKey('errors', $r);
    }

    public function testQuickSellOrderFailsWhenNoBuyOrders()
    {
        $seller = User::find(1);
        $market = Market::find(1);

        $token = $this->getUserToken($seller);
        $headers = $this->getHeaders($token);
        $data = [
            'market' => $market->name,
            'quantity' => 10000,
        ];
        //Try with an insufficient balance
        $response = $this->apiPost('order/sell', $data, $headers);

        $r = $response->json();
        $response->assertStatus(422);
        $this->assertEquals($r['success'], false);
        $this->assertArrayHasKey('errors', $r);
    }

    /**
     * Check a buy order method.
     */
    public function testBuyOrderFills()
    {
        $buyer = User::find(1);
        $seller = User::find(2);
        $market = Market::find(1);

        $sellOrder = OrderManager::create('SELL_LIMIT', $seller, $market->name, 30000, 1);

        $token = $this->getUserToken($buyer);
        $headers = $this->getHeaders($token);
        $data = [
            'market' => $market->name,
            'quantity' => 110000,
        ];
        //Try with an insufficient balance
        $response = $this->apiPost('order/buy', $data, $headers);
        //it should fail.

        $r = $response->json();
        $response->assertStatus(422);
        $this->assertEquals($r['success'], false);
        $this->assertArrayHasKey('errors', $r);

        //Try with a sufficient amount
        $data['quantity'] = 3000;
        $response = $this->apiPost('order/buy', $data, $headers);
        // Check the order response
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'uuid']);

        $bfw = $buyer->getFiatWallet(FiatCurrency::ID_TRY);
        $bw = $buyer->getWallet(Currency::ID_BTC);

        $sfw = $seller->getFiatWallet(FiatCurrency::ID_TRY);
        $sw = $seller->getWallet(Currency::ID_BTC);

        //assert buyer fiat balance
        $this->assertEquals(97000, $bfw->available_balance_decimal);
        //Bought 0.09970566 bitcoins with rate 30000,
        // 2.991,17 TRY amount,
        // 7.48 TRY commission,
        // 1.35 TRY tax
        //assert buyer wallet balance
        $this->assertEquals(10.09970567, $bw->available_balance_decimal);

        //assert seller fiat wallet balance
        $this->assertEquals(102982.34, $sfw->available_balance_decimal);

        //assert seller wallet balance
        $this->assertEquals(9, $sw->available_balance_decimal);

        //Seller sold 0.9970566 BTC of 1 BTC, left 0.090029434, total 9.090029434
        //assert seller wallet pending balance
        $this->assertEquals(0.90029433, $sw->pending_balance_decimal);

        //Assert buyer transaction
        $this->assertDatabaseHas('transactions', [
            'market_id' => $market->id,
            'user_id' => $buyer->id,
            'type' => 'buy',
            'rate' => 3000000,
            'is_triggered' => 0,
            'quote_amount' => 299117,
            'tax' => 135,
            'commission' => 748,
            'crypto_amount' => 9970567,
        ]);

        //Assert seller transaction
        $this->assertDatabaseHas('transactions', [
            'user_id' => $seller->id,
            'type' => 'sell',
            'rate' => 3000000,
            'is_triggered' => 1,
            'quote_amount' => 299117,
            'tax' => 135,
            'commission' => 748,
            'crypto_amount' => 9970567,
        ]);

        //Assert sell order filled partially, selling 0.09970566 btc
        $sellOrder = $sellOrder->fresh();

        $this->assertEquals(0.90029433, $sellOrder->quantity_remaining_decimal);
        $this->assertEquals(Order::STATUS_PARTIAL_FILL, $sellOrder->fill_status);
    }

    public function testBuyOrderPartialFill()
    {
        $buyer = User::find(1);
        $seller = User::find(2);
        $market = Market::find(1);

        OrderManager::create('SELL_LIMIT', $seller, $market->name, 30000, 0.5);

        $token = $this->getUserToken($buyer);
        $headers = $this->getHeaders($token);
        $data = [
            'market' => $market->name,
            'quantity' => 20000,
        ];

        $response = $this->apiPost('order/buy', $data, $headers);
        // Check the order response
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'uuid']);

        $bfw = $buyer->getFiatWallet(FiatCurrency::ID_TRY);
        $bw = $buyer->getWallet(Currency::ID_BTC);

        $sfw = $seller->getFiatWallet(FiatCurrency::ID_TRY);
        $sw = $seller->getWallet(Currency::ID_BTC);

        // Buyer bought 0.5 bitcoins with rate 30000,
        // 15000 TRY amount,
        // 37.50 TRY commission,
        // 6.75 TRY tax
        // 15445.25 total cost

        //assert buyer fiat balance
        $this->assertEquals(84955.75, $bfw->available_balance_decimal);
        $this->assertEquals(0, $bfw->pending_balance_decimal);

        //assert buyer wallet balance increased by 0.5
        $this->assertEquals(10.5, $bw->available_balance_decimal);

        // Seller sold 0.5 bitcoins with rate 30000,
        // 15000 TRY amount,
        // 37.50 TRY commission,
        // 6.75 TRY tax
        // 114955.75 TRY (subtracted tax and commission)

        //assert seller fiat wallet balance
        $this->assertEquals(114955.75, $sfw->available_balance_decimal);

        //assert seller wallet balance
        $this->assertEquals(9.5, $sw->available_balance_decimal);
        $this->assertEquals(0, $sw->pending_balance_decimal);

        //Assert buyer transaction
        $this->assertDatabaseHas('transactions', [
            'market_id' => $market->id,
            'user_id' => $buyer->id,
            'type' => 'buy',
            'rate' => 3000000,
            'is_triggered' => 0,
            'quote_amount' => 1500000,
            'tax' => 675,
            'commission' => 3750,
            'crypto_amount' => 50000000,
        ]);

        //Assert seller transaction
        $this->assertDatabaseHas('transactions', [
            'user_id' => $seller->id,
            'type' => 'sell',
            'rate' => 3000000,
            'is_triggered' => 1,
            'quote_amount' => 1500000,
            'tax' => 675,
            'commission' => 3750,
            'crypto_amount' => 50000000,
        ]);

        //Assert order status
        $this->assertDatabaseHas('orders', [
            'user_id' => $buyer->id,
            'type' => 'BUY',
            'rate' => 0,
            //amount to spend is 20000.00 - costs (cutBuyCost)
            'amount' => 1994117,
            //pending amount is 5000.00 - costs (cutBuyCost)
            'pending_amount' => 494117,
            'fill_status' => 1,
        ]);

        $order = Order::where('user_id', $buyer->id)->withTrashed()->first();
        $this->assertNotNull($order->deleted_at);
    }

    public function testBuyLimitOrderNotFills()
    {
        $buyer = User::find(1);
        $seller = User::find(2);
        $seller2 = User::find(3);
        $market = Market::find(1);

        $sellOrder1 = OrderManager::create('SELL_LIMIT', $seller, $market->name, 30000.01, 0.25);

        $sellOrder2 = OrderManager::create('SELL_LIMIT', $seller2, $market->name, 35000, 0.1);

        $token = $this->getUserToken($buyer);
        $headers = $this->getHeaders($token);
        $data = [
            'market' => $market->name,
            'quantity' => 0.5,
            'rate' => 30000,
        ];

        $response = $this->apiPost('order/buylimit', $data, $headers);
        $response->assertStatus(200);

        $bfw = $buyer->getFiatWallet(FiatCurrency::ID_TRY);
        $bw = $buyer->getWallet(Currency::ID_BTC);

        $this->assertEquals(10, $bw->available_balance_decimal);

        //Reserve fiat 15000 TRY + costs
        //37.50 TRY Commission
        //6.75 TRY Tax
        //15044.25 TRY Total Pending
        // 100,000 - 15,044.25 = 84,955.75 TRY Available
        $this->assertEquals(84955.75, $bfw->available_balance_decimal);
        $this->assertEquals(15044.25, $bfw->pending_balance_decimal);

        $this->assertDatabaseHas('orders', [
            'type' => 'BUY_LIMIT',
            'amount' => 1500000,
            'pending_amount' => 1500000,
            'rate' => 3000000,
            'quantity' => 50000000,
            'quantity_remaining' => 50000000,
            'fill_status' => 0,
        ]);

        $this->assertEquals(true, Transaction::all()->isEmpty());
    }

    public function testBuyOrderStopsOnGap()
    {
        $buyer = User::find(1);
        $seller = User::find(2);
        $seller2 = User::find(3);
        $market = Market::find(1);

        $sellOrder1 = OrderManager::create('SELL_LIMIT', $seller, $market->name, 30100, 0.1);

        $sellOrder2 = OrderManager::create('SELL_LIMIT', $seller2, $market->name, 30200, 0.25);

        $sellOrder3 = OrderManager::create('SELL_LIMIT', $seller2, $market->name, 31750, 0.1);

        $buyOrder = OrderManager::create('BUY', $buyer, $market->name, 0, 12000);

        $bfw = $buyer->getFiatWallet(FiatCurrency::ID_TRY);
        $bw = $buyer->getWallet(Currency::ID_BTC);

        $this->assertEquals(10.35, $bw->available_balance_decimal);

        //Spend fiat 3010 TRY + costs
        //7.52 TRY Commission
        //1.35 TRY Tax
        //3018.88 TRY Total Spent

        //Spend fiat 7550 TRY + costs
        //18.87 TRY Commission
        //3.40  TRY Tax
        //7772.27 TRY Total Spent

        //Total spend 10591.15 TRY
        // 100,000 - 10591.15 = 89208.83 TRY Available
        $this->assertEquals(89408.85, $bfw->available_balance_decimal);

        //Assert that sellOrder3 is not matched due to %5 gap
        $this->assertDatabaseMissing('transactions', [
            'order_uuid' => $sellOrder3->uuid,
        ]);

        $buyOrder->load('transactions');
        $this->assertEquals(2, $buyOrder->transactions->count());
    }

    public function testBuyLimitOrderFills()
    {
        $buyer = User::find(1);
        $seller1 = User::find(2);
        $seller2 = User::find(3);
        $seller3 = User::find(4);
        $market = Market::find(1);

        $sellOrder1 = OrderManager::create('SELL_LIMIT', $seller1, $market->name, 29980, 0.4);

        $sellOrder2 = OrderManager::create('SELL_LIMIT', $seller2, $market->name, 29990, 0.2);

        $sellOrder3 = OrderManager::create('SELL_LIMIT', $seller3, $market->name, 30010, 1);

        $token = $this->getUserToken($buyer);
        $headers = $this->getHeaders($token);
        $data = [
            'market' => $market->name,
            'quantity' => 0.5,
            'rate' => 30000,
        ];

        $response = $this->apiPost('order/buylimit', $data, $headers);
        $response->assertStatus(200);

        $bfw = $buyer->getFiatWallet(FiatCurrency::ID_TRY);
        $bw = $buyer->getWallet(Currency::ID_BTC);

        $s1fw = $seller1->getFiatWallet(FiatCurrency::ID_TRY);
        $s1w = $seller1->getWallet(Currency::ID_BTC);

        $s2fw = $seller2->getFiatWallet(FiatCurrency::ID_TRY);
        $s2w = $seller2->getWallet(Currency::ID_BTC);

        $this->assertEquals(10.5, $bw->available_balance_decimal);

        //Bought 0.4 btc with rate 29980
        // 11992 amount
        // 29.98 commission
        // 5.40 tax

        //Bought 0.1 btc with rate 29990
        // 2999 amount
        // 7.50 commission
        // 1.35 tax

        //15044.25 TRY Total Spent
        // 100,000 - 15.035,23 = 84.964,77 TRY Remaining
        $this->assertEquals(84964.77, $bfw->available_balance_decimal);

        //Assert seller1 sold 0.4 btc
        $this->assertEquals(9.6, $s1w->available_balance_decimal);
        //Assert seller1 has no pending balance, sold all
        $this->assertEquals(0, $s1w->pending_balance_decimal);

        // 11992 amount
        // 29.98 commission
        // 5.40 tax
        // 11956.62 TRY Fiat Increase
        $this->assertEquals(111956.62, $s1fw->available_balance_decimal);

        //Assert seller2 sold 0.1 btc to buyer, 0.1 left
        $this->assertEquals(9.8, $s2w->available_balance_decimal);
        $this->assertEquals(0.1, $s2w->pending_balance_decimal);

        //Seller1 sold 0.4 BTC for 29980, but buyer bought for rate = 30000.00
        // 2999 amount
        // 7.50 commission
        // 1.35 tax
        // 102990.15 TRY Fiat Increase
        $this->assertEquals(102990.15, $s2fw->available_balance_decimal);

        //Assert buyer transaction 1
        $this->assertDatabaseHas('transactions', [
            'market_id' => $market->id,
            'user_id' => $buyer->id,
            'matched_order_uuid' => $sellOrder1->uuid,
            'type' => 'buy',
            'rate' => 2998000,
            'is_triggered' => 0,
            'quote_amount' => 1199200,
            'tax' => 540,
            'commission' => 2998,
            'crypto_amount' => 40000000,
        ]);

        //Assert buyer transaction 2
        $this->assertDatabaseHas('transactions', [
            'market_id' => $market->id,
            'user_id' => $buyer->id,
            'matched_order_uuid' => $sellOrder2->uuid,
            'type' => 'buy',
            'rate' => 2999000,
            'is_triggered' => 0,
            'quote_amount' => 299900,
            'tax' => 135,
            'commission' => 750,
            'crypto_amount' => 10000000,
        ]);

        //Assert seller 1 transaction
        $this->assertDatabaseHas('transactions', [
            'user_id' => $seller1->id,
            'type' => 'sell',
            'rate' => 2998000,
            'is_triggered' => 1,
            'quote_amount' => 1199200,
            'tax' => 540,
            'commission' => 2998,
            'crypto_amount' => 40000000,
        ]);

        //Assert seller 2 transaction
        $this->assertDatabaseHas('transactions', [
            'user_id' => $seller2->id,
            'type' => 'sell',
            'rate' => 2999000,
            'is_triggered' => 1,
            'quote_amount' => 299900,
            'tax' => 135,
            'commission' => 750,
            'crypto_amount' => 10000000,
        ]);

        $sellOrder1 = $sellOrder1->fresh();
        $sellOrder2 = $sellOrder2->fresh();

        //Assert sell order 2 has still 0.1 btc remaining
        $this->assertEquals(0.1, $sellOrder2->quantity_remaining_decimal);
        //Assert sell order 1 is filled
        $this->assertEquals(0, $sellOrder1->quantity_remaining_decimal);
        $this->assertEquals(Order::STATUS_FILLED, $sellOrder1->fill_status);

        //CANCEL TESTS
        $this->resetState();

        //Assert that completed order can not be canceled
        $token = $this->getUserToken($seller1);
        $headers = $this->getHeaders($token);

        $response = $this->apiPost('order/cancel', ['uuid' => $sellOrder1->uuid], $headers);

        $response->assertStatus(422);

        $this->resetState();
        //cancel pending sell order 2
        $token = $this->getUserToken($seller2);
        $headers = $this->getHeaders($token);

        $response = $this->apiPost('order/cancel', ['uuid' => $sellOrder2->uuid], $headers);

        $response->assertStatus(200);

        //assert that remaining amount of sell order 2 is reverted back.
        $s2w = $s2w->fresh();
        $sellOrder2 = $sellOrder2->fresh();
        $this->assertEquals(9.9, $s2w->available_balance_decimal);
        $this->assertEquals(0, $s2w->pending_balance_decimal);
        $this->assertNotNull($sellOrder2->deleted_at);
        $this->assertEquals(Order::STATUS_PARTIAL_FILL, $sellOrder2->fill_status);

        //Try to cancel for the second time
        $response = $this->apiPost('order/cancel', ['uuid' => $sellOrder2->uuid], $headers);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'order_already_canceled',
        ]);

        //Assert that high priced order is not touched
        $sellOrder3 = $sellOrder3->fresh();
        $this->assertEquals(Order::STATUS_NO_FILL, $sellOrder3->fill_status);
        $this->assertEquals(1, $sellOrder3->quantity_remaining_decimal);
    }

    public function testSellOrderFills()
    {
        $buyer = User::find(1);
        $seller = User::find(2);
        $market = Market::find(1);

        //Buyer wants to buy btc 1 with rate 30000 TRY,

        //75.00 TRY Commission
        //13.50 TRY Tax
        //30088.50 TRY Pending Total
        $buyOrder = OrderManager::create('BUY_LIMIT', $buyer, $market->name, 30000, 1);

        $bfw = $buyer->getFiatWallet(FiatCurrency::ID_TRY);
        $this->assertEquals(30088.50, $bfw->pending_balance_decimal);
        $this->assertEquals(69911.50, $bfw->available_balance_decimal);

        $token = $this->getUserToken($seller);
        $headers = $this->getHeaders($token);

        //Try to oversell (balance is 10btc)
        $data = [
            'market' => $market->name,
            'quantity' => 11.1,
        ];
        $response = $this->apiPost('order/sell', $data, $headers);

        $r = $response->json();
        $this->assertEquals($r['success'], false);
        $this->assertArrayHasKey('errors', $r);

        //Try to sell 0.1
        $data['quantity'] = 0.1;
        $response = $this->apiPost('order/sell', $data, $headers);

        // Check the order response
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'uuid']);

        $bfw = $bfw->fresh();
        $bw = $buyer->getWallet(Currency::ID_BTC);

        $sfw = $seller->getFiatWallet(FiatCurrency::ID_TRY);
        $sw = $seller->getWallet(Currency::ID_BTC);

        //After buyer bough 0.1 BTC from the quick seller
        //3000.00 TRY amount
        //7.50 TRY commission
        //1.35 TRY Tax
        //3008.85 TRY Total Cost
        //30088.50 - 3008.85 = 27079.65 , new pending balance.

        //assert buyer fiat balance
        $this->assertEquals(27079.65, $bfw->pending_balance_decimal);

        //assert buyer wallet increased by 0.1 btc
        $this->assertEquals(10.10000000, $bw->available_balance_decimal);

        //assert seller fiat wallet balance increases by 2991.15 TRY
        //3000.00 TRY amount
        //7.50 TRY Commission
        //1.35 TRY Tax
        $this->assertEquals(102991.15, $sfw->available_balance_decimal);

        //assert seller wallet balance, sold 0.1 of 10 BTC
        $this->assertEquals(9.9, $sw->available_balance_decimal);

        //Assert buyer transaction
        $this->assertDatabaseHas('transactions', [
            'market_id' => $market->id,
            'user_id' => $buyer->id,
            'type' => 'buy',
            'rate' => 3000000,
            'is_triggered' => 1,
            'quote_amount' => 300000,
            'tax' => 135,
            'commission' => 750,
            'crypto_amount' => 10000000,
        ]);

        //Assert seller transaction
        $this->assertDatabaseHas('transactions', [
            'user_id' => $seller->id,
            'type' => 'sell',
            'rate' => 3000000,
            'is_triggered' => 0,
            'quote_amount' => 300000,
            'tax' => 135,
            'commission' => 750,
            'crypto_amount' => 10000000,
        ]);

        $buyOrder = $buyOrder->fresh();

        $this->assertEquals(0.9, $buyOrder->quantity_remaining_decimal);
        $this->assertEquals(Order::STATUS_PARTIAL_FILL, $buyOrder->fill_status);
    }

    public function testSellOrderPartialFill()
    {
        $buyer = User::find(1);
        $seller = User::find(2);
        $market = Market::find(1);

        $buyOrder = OrderManager::create('BUY_LIMIT', $buyer, $market->name, 30000, 0.5);

        $token = $this->getUserToken($seller);
        $headers = $this->getHeaders($token);
        $data = [
            'market' => $market->name,
            'quantity' => 1,
        ];

        $response = $this->apiPost('order/sell', $data, $headers);
        // Check the order response
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'uuid']);

        $bfw = $buyer->getFiatWallet(FiatCurrency::ID_TRY);
        $bw = $buyer->getWallet(Currency::ID_BTC);

        $sfw = $seller->getFiatWallet(FiatCurrency::ID_TRY);
        $sw = $seller->getWallet(Currency::ID_BTC);

        // Buyer bought 0.5 bitcoins with rate 30000,
        // 15000 TRY amount,
        // 37.50 TRY commission,
        // 6.75 TRY tax
        // 15445.25 total cost

        //assert buyer fiat balance
        $this->assertEquals(84955.75, $bfw->available_balance_decimal);
        $this->assertEquals(0, $bfw->pending_balance_decimal);

        //assert buyer wallet balance increased by 0.5
        $this->assertEquals(10.5, $bw->available_balance_decimal);

        // Seller sold 0.5 bitcoins with rate 30000,
        // 15000 TRY amount,
        // 37.50 TRY commission,
        // 6.75 TRY tax
        // 114955.75 TRY (subtracted tax and commission)

        //assert seller fiat wallet balance
        $this->assertEquals(114955.75, $sfw->available_balance_decimal);

        //assert seller wallet balance
        $this->assertEquals(9.5, $sw->available_balance_decimal);
        $this->assertEquals(0, $sw->pending_balance_decimal);

        //Assert buyer transaction
        $this->assertDatabaseHas('transactions', [
            'market_id' => $market->id,
            'user_id' => $buyer->id,
            'type' => 'buy',
            'rate' => 3000000,
            'is_triggered' => 1,
            'quote_amount' => 1500000,
            'tax' => 675,
            'commission' => 3750,
            'crypto_amount' => 50000000,
        ]);

        //Assert seller transaction
        $this->assertDatabaseHas('transactions', [
            'user_id' => $seller->id,
            'type' => 'sell',
            'rate' => 3000000,
            'is_triggered' => 0,
            'quote_amount' => 1500000,
            'tax' => 675,
            'commission' => 3750,
            'crypto_amount' => 50000000,
        ]);

        $sellOrder = Order::where('uuid', $response->json()['uuid'])->withTrashed()->first();

        $this->assertNotNull($sellOrder->deleted_at);
        $this->assertEquals(0.5, $sellOrder->quantity_remaining_decimal);
        $this->assertEquals(Order::STATUS_PARTIAL_FILL, $sellOrder->fill_status);
    }

    public function testSellOrderStopsOnGap()
    {
        $buyer1 = User::find(1);
        $buyer2 = User::find(2);
        $buyer3 = User::find(3);
        $seller = User::find(4);
        $market = Market::find(1);

        $buyOrder1 = OrderManager::create('BUY_LIMIT', $buyer1, $market->name, 30100, 0.1);

        $buyOrder2 = OrderManager::create('BUY_LIMIT', $buyer2, $market->name, 31750, 0.25);

        $buyOrder3 = OrderManager::create('BUY_LIMIT', $buyer3, $market->name, 31800, 0.2);

        $sellOrder = OrderManager::create('SELL', $seller, $market->name, 0, 0.5);

        $sfw = $seller->getFiatWallet(FiatCurrency::ID_TRY);
        $sw = $seller->getWallet(Currency::ID_BTC);

        $this->assertEquals(9.55, $sw->available_balance_decimal);

        //Assert that buyOrder1 is not matched due to %5 gap
        $this->assertDatabaseMissing('transactions', [
            'order_uuid' => $buyOrder1->uuid,
        ]);

        $sellOrder->load('transactions');
        $this->assertEquals(2, $sellOrder->transactions->count());
    }

    public function testSellLimitOrderFills()
    {
        $buyer1 = User::find(1);
        $buyer2 = User::find(2);
        $seller = User::find(3);
        $market = Market::find(1);

        $buyOrder1 = OrderManager::create('BUY_LIMIT', $buyer1, $market->name, 30000, 0.4);

        $buyOrder2 = OrderManager::create('BUY_LIMIT', $buyer2, $market->name, 30005, 0.2);

        $token = $this->getUserToken($seller);
        $headers = $this->getHeaders($token);
        $data = [
            'market' => $market->name,
            'quantity' => 0.5,
            'rate' => 30000,
        ];

        $response = $this->apiPost('order/selllimit', $data, $headers);
        $response->assertStatus(200);

        //Test results:
        //Seller sold 0.2 of 0.5 btc to buyer 2 with rate 30005
        //Seller sold 0.3 of 0.5 btc to buyer 1 with rate 30000

        $b1fw = $buyer1->getFiatWallet(FiatCurrency::ID_TRY);
        $b1w = $buyer1->getWallet(Currency::ID_BTC);

        $b2fw = $buyer2->getFiatWallet(FiatCurrency::ID_TRY);
        $b2w = $buyer2->getWallet(Currency::ID_BTC);

        $sfw = $seller->getFiatWallet(FiatCurrency::ID_TRY);
        $sw = $seller->getWallet(Currency::ID_BTC);

        //Assert buyer 1 has 0.3 btc increase
        $this->assertEquals(10.3, $b1w->available_balance_decimal);
        $this->assertEquals(10.2, $b2w->available_balance_decimal);

        //Assert buyer 1 has spent amount for 0.3 btc with rate 30000
        //Bought btc for 9000 TRY
        //22.50 TRY Commission
        //4.05 TRY Tax
        //9028.55TRY Total Spent, still has 3008.85 pending to buy the remaining 0.1 btc in his order
        $this->assertEquals(87964.60, $b1fw->available_balance_decimal);
        $this->assertEquals(3008.85, $b1fw->pending_balance_decimal);

        //Assert buyer 2 has spent amount for 0.2 btc with rate 30005
        $this->assertEquals(93981.30, $b2fw->available_balance_decimal);
        $this->assertEquals(0, $b2fw->pending_balance_decimal);

        //Assert seller sold 0.5 btc
        $this->assertEquals(9.5, $sw->available_balance_decimal);
        $this->assertEquals(114956.75, $sfw->available_balance_decimal);

        //Assert buyer transaction 1
        $this->assertDatabaseHas('transactions', [
            'market_id' => $market->id,
            'user_id' => $seller->id,
            'type' => 'sell',
            'rate' => 3000500,
            'is_triggered' => 0,
            'quote_amount' => 600100,
            'tax' => 270,
            'commission' => 1500,
            'crypto_amount' => 20000000,
        ]);

        //Assert buyer transaction 2
        $this->assertDatabaseHas('transactions', [
            'market_id' => $market->id,
            'user_id' => $buyer2->id,
            'type' => 'buy',
            'rate' => 3000500,
            'is_triggered' => 1,
            'quote_amount' => 600100,
            'tax' => 270,
            'commission' => 1500,
            'crypto_amount' => 20000000,
        ]);

        //Assert seller transaction
        $this->assertDatabaseHas('transactions', [
            'user_id' => $seller->id,
            'type' => 'sell',
            'rate' => 3000000,
            'is_triggered' => 0,
            'quote_amount' => 900000,
            'tax' => 405,
            'commission' => 2250,
            'crypto_amount' => 30000000,
        ]);

        //Assert seller transaction
        $this->assertDatabaseHas('transactions', [
            'user_id' => $buyer1->id,
            'type' => 'buy',
            'rate' => 3000000,
            'is_triggered' => 1,
            'quote_amount' => 900000,
            'tax' => 405,
            'commission' => 2250,
            'crypto_amount' => 30000000,
        ]);

        $buyOrder1 = $buyOrder1->fresh();
        $this->assertEquals(Order::STATUS_PARTIAL_FILL, $buyOrder1->fill_status);
        $this->assertEquals(0.1, $buyOrder1->quantity_remaining_decimal);
        $this->assertEquals(3000, $buyOrder1->pending_amount_decimal);

        $buyOrder2 = $buyOrder2->fresh();
        $this->assertEquals(Order::STATUS_FILLED, $buyOrder2->fill_status);
        $this->assertEquals(0, $buyOrder2->quantity_remaining_decimal);

        $sellOrder = Order::where('uuid', $response->json()['uuid'])->first();
        $this->assertEquals(Order::STATUS_FILLED, $sellOrder->fill_status);
        $this->assertEquals(0, $sellOrder->quantity_remaining_decimal);

        $this->resetState();
        //Assert that partially filled buyOrder1 can be canceled
        $token = $this->getUserToken($buyer1);
        $headers = $this->getHeaders($token);

        $response = $this->apiPost('order/cancel', ['uuid' => $buyOrder1->uuid], $headers);

        $response->assertStatus(200);

        //assert that remaining amount of buyOrder1 is reverted back.
        $b1w = $b1w->fresh();
        $b1fw = $b1fw->fresh();
        $this->assertEquals(10.3, $b1w->available_balance_decimal);
        $this->assertEquals(0, $b1fw->pending_balance_decimal);
        $this->assertEquals(90973.45, $b1fw->available_balance_decimal);

        //buyOrder1 remaining amounts does not change after cancel.
        $buyOrder1 = $buyOrder1->fresh();
        $this->assertEquals(Order::STATUS_PARTIAL_FILL, $buyOrder1->fill_status);
        $this->assertEquals(0.1, $buyOrder1->quantity_remaining_decimal);
        $this->assertEquals(3000, $buyOrder1->pending_amount_decimal);
        //But it should be deleted
        $this->assertNotNull($buyOrder1->deleted_at);
    }

    public function testSellLimitOrderPartialFill()
    {
        $buyer = User::find(1);
        $seller = User::find(2);
        $market = Market::find(1);

        $buyOrder = OrderManager::create('BUY_LIMIT', $buyer, $market->name, 30000, 0.5);

        //Also lets test the taker has the advantage of getting best rate, even order rate is 29990, it's actual rate will be 30000 because of the matched buy order
        $sellOrder = OrderManager::create('SELL_LIMIT', $seller, $market->name, 29990, 1);

        $bfw = $buyer->getFiatWallet(FiatCurrency::ID_TRY);
        $bw = $buyer->getWallet(Currency::ID_BTC);

        $sfw = $seller->getFiatWallet(FiatCurrency::ID_TRY);
        $sw = $seller->getWallet(Currency::ID_BTC);

        //assert buyer fiat balance
        // 15000.00 TRY for buy
        // 37.50 TRY commission
        // 6.75 TRY tax
        // 15044.25 TRY Total
        // 100000.00 - 15044.25 = 84955.75 TRY
        $this->assertEquals(84955.75, $bfw->available_balance_decimal);
        //assert buyer bought 0.5 btc
        $this->assertEquals(10.5, $bw->available_balance_decimal);

        //assert seller fiat wallet balance
        // 100000.00 + 15000.00 (raw amount) - 44.25 (tax)
        $this->assertEquals(114955.75, $sfw->available_balance_decimal);

        //assert seller wallet balance
        $this->assertEquals(9.0, $sw->available_balance_decimal);
        $this->assertEquals(0.5, $sw->pending_balance_decimal);

        $sellOrder = $sellOrder->fresh();
        $this->assertEquals(Order::STATUS_PARTIAL_FILL, $sellOrder->fill_status);

        $buyOrder = $buyOrder->fresh();
        $this->assertEquals(Order::STATUS_FILLED, $buyOrder->fill_status);

        //Assert buyer transaction
        $this->assertDatabaseHas('transactions', [
            'market_id' => $market->id,
            'user_id' => $buyer->id,
            'type' => 'buy',
            'rate' => 3000000,
            'is_triggered' => 1,
            'quote_amount' => 1500000,
            'tax' => 675,
            'commission' => 3750,
            'crypto_amount' => 50000000,
        ]);

        //Assert seller transaction
        $this->assertDatabaseHas('transactions', [
            'user_id' => $seller->id,
            'type' => 'sell',
            'rate' => 3000000,
            'is_triggered' => 0,
            'quote_amount' => 1500000,
            'tax' => 675,
            'commission' => 3750,
            'crypto_amount' => 50000000,
        ]);

        $sellOrder = $sellOrder->fresh();
        $this->assertEquals(Order::STATUS_PARTIAL_FILL, $sellOrder->fill_status);
        $this->assertEquals(0.5, $sellOrder->quantity_remaining_decimal);
        $this->assertNull($sellOrder->deleted_at);
    }

    public function testBuyFillsWithDifferentRatesAndRefunds()
    {
        $buyer = User::find(1);
        $seller = User::find(2);
        $market = Market::find(1);

        $sellOrder1 = OrderManager::create('SELL_LIMIT', $seller, $market->name, 30000, 0.5);

        $sellOrder2 = OrderManager::create('SELL_LIMIT', $seller, $market->name, 29990, 0.2);

        $sellOrder3 = OrderManager::create('SELL_LIMIT', $seller, $market->name, 29980, 0.1);

        $buyOrder = OrderManager::create('BUY_LIMIT', $buyer, $market->name, 30000, 0.5);

        $bfw = $buyer->getFiatWallet(FiatCurrency::ID_TRY);
        $bw = $buyer->getWallet(Currency::ID_BTC);

        // Bought 0.1 btc from 29980
        // 2998.00 Amount
        // 7.49 TRY commission
        // 1.35 TRY tax
        // 3006.84 TRY Total

        // Bought 0.2 btc from 29990
        // 5998.00 Amount
        // 15.00 TRY commission
        // 2.70 TRY tax
        // 6015.70 TRY Total

        // Bought 0.2 btc from 30000
        // 6000.00 Amount
        // 15.00 TRY commission
        // 2.70 TRY tax
        // 6017.70 TRY Total

        // 100000.00 - 15040.24 = 84959.76 TRY
        $this->assertEquals(84959.76, $bfw->available_balance_decimal);
        //assert buyer bought 0.5 btc
        $this->assertEquals(10.5, $bw->available_balance_decimal);

        $buyOrder = $buyOrder->fresh();
        $this->assertEquals(Order::STATUS_FILLED, $buyOrder->fill_status);

        //Assert buyer first transaction
        $this->assertDatabaseHas('transactions', [
            'market_id' => $market->id,
            'user_id' => $buyer->id,
            'matched_order_uuid' => $sellOrder3->uuid,
            'type' => 'buy',
            'rate' => 2998000,
            'is_triggered' => 0,
            'quote_amount' => 299800,
            'tax' => 135,
            'commission' => 749,
            'crypto_amount' => 10000000,
        ]);

        //Assert buyer first transaction
        $this->assertDatabaseHas('transactions', [
            'market_id' => $market->id,
            'user_id' => $seller->id,
            'order_uuid' => $sellOrder3->uuid,
            'matched_order_uuid' => $buyOrder->uuid,
            'type' => 'sell',
            'rate' => 2998000,
            'is_triggered' => 1,
            'quote_amount' => 299800,
            'tax' => 135,
            'commission' => 749,
            'crypto_amount' => 10000000,
        ]);

        //Assert buyer 2nd transaction
        $this->assertDatabaseHas('transactions', [
            'market_id' => $market->id,
            'user_id' => $buyer->id,
            'matched_order_uuid' => $sellOrder2->uuid,
            'type' => 'buy',
            'rate' => 2999000,
            'is_triggered' => 0,
            'quote_amount' => 599800,
            'tax' => 270,
            'commission' => 1499,
            'crypto_amount' => 20000000,
        ]);

        //Assert buyer first transaction
        $this->assertDatabaseHas('transactions', [
            'market_id' => $market->id,
            'user_id' => $seller->id,
            'order_uuid' => $sellOrder2->uuid,
            'matched_order_uuid' => $buyOrder->uuid,
            'type' => 'sell',
            'rate' => 2999000,
            'is_triggered' => 1,
            'quote_amount' => 599800,
            'tax' => 270,
            'commission' => 1499,
            'crypto_amount' => 20000000,
        ]);

        //Assert buyer 3rd transaction
        $this->assertDatabaseHas('transactions', [
            'market_id' => $market->id,
            'user_id' => $buyer->id,
            'matched_order_uuid' => $sellOrder1->uuid,
            'type' => 'buy',
            'rate' => 3000000,
            'is_triggered' => 0,
            'quote_amount' => 600000,
            'tax' => 270,
            'commission' => 1500,
            'crypto_amount' => 20000000,
        ]);

        //Assert buyer first transaction
        $this->assertDatabaseHas('transactions', [
            'market_id' => $market->id,
            'user_id' => $seller->id,
            'order_uuid' => $sellOrder1->uuid,
            'matched_order_uuid' => $buyOrder->uuid,
            'type' => 'sell',
            'rate' => 3000000,
            'is_triggered' => 1,
            'quote_amount' => 600000,
            'tax' => 270,
            'commission' => 1500,
            'crypto_amount' => 20000000,
        ]);

        $buyOrder = $buyOrder->fresh();

        $this->assertEquals(Order::STATUS_FILLED, $buyOrder->fill_status);
        $this->assertEquals(15000.00, $buyOrder->amount_decimal);
        $this->assertEquals(4, $buyOrder->pending_amount_decimal);
        $this->assertEquals(29992, $buyOrder->rate_actual_decimal);
    }
}
