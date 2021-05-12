<?php

namespace Tests\Feature\Commands\Btc;

use App\Facades\Withdraw\GatewayBtc;
use App\Models\Currency;
use Illuminate\Support\Facades\Artisan;
use Tests\ApiTestCase;

class UpdateAccountBalanceBtcTest extends ApiTestCase
{
    public function testUpdateAccountBalanceBtc()
    {
        GatewayBtc::shouldReceive('getAccountBalance')
            ->once()
            ->andReturn(1.2345);
        Artisan::call('blockchain:updatebalance_btc');
        $currency = Currency::find(Currency::ID_BTC);

        $this->assertEquals(1.2345, $currency->account_balance_decimal);
    }
}
