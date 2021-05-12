<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Predis\Connection\ConnectionException;

class InstallerManager
{
    public function getPHP() {
        preg_match("#^\d.\d#", PHP_VERSION, $phpVersion);
        return ['text' => $phpVersion[0], 'status' => $phpVersion[0] >= 7.2 ? true : false];
    }

    public function getRedis()
    {
        try
        {
            //$redis = Redis::connection();
            //dd($redis);
        }
        catch(ConnectionException $e)
        {
            //dd(1);
        }
    }
}

