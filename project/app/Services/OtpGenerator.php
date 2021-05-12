<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class OtpGenerator
{
    public static function create()
    {
        if (config('sms.sms_enabled') == 'no') {
            return '123123';
        }

        $string_shuffled = str_shuffle('0123456789');

        return substr($string_shuffled, 1, 6);
    }

    public static function message($otp)
    {
        return __('two_fa.sms_otp', ['app' => config('app.name'), 'otp' => $otp]);
    }
}
