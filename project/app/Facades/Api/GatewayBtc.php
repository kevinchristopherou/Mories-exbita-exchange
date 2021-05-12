<?php

namespace App\Facades\Api;

use Illuminate\Support\Facades\Facade;

/**
 * This class used to create a btc gateway facade
 *
 */

class GatewayBtc extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'api.gateway.btc';
    }
}
