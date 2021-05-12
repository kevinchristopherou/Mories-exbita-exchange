<?php

namespace App\Facades\Api;

use Illuminate\Support\Facades\Facade;

/**
 * This class used to create a ethereum gateway facade
 *
 */

class GatewayEth extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'api.gateway.eth';
    }
}
