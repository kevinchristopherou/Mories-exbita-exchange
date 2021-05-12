<?php

namespace Tests\Unit;

use App\Services\SmsClient;
use Carbon\Carbon;
use Tests\TestCase;

class SmsTest extends TestCase
{
    public function testClickatellSend()
    {
        //SmsClient::$forceSend = true;
        $result = SmsClient::send('Exbita Sms Test Run @ '.Carbon::now(), config('sms.testnumber'));
        $this->assertTrue($result);
        SmsClient::$forceSend = false;
    }
}
