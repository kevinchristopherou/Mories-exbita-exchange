<?php

namespace App\Facades\Api;

use Illuminate\Support\Facades\Facade;

/**
 * This class used to create a coinpayments gateway facade
 *
 */

class GatewayCoinpayments extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'api.gateway.coinpayments';
    }
}
