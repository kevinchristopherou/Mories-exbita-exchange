<?php

namespace App\Services;

use App\Models\SmsLog;
use App\User;
use Carbon\Carbon;
use Clickatell\ClickatellException;
use Clickatell\Rest;
use Illuminate\Support\Facades\Log;

class SmsClient
{
    //set this to true if we want to force sending sms even if it is disabled from the config
    //for example SmsTest unit test.
    public static $forceSend = false;

    public static function send($text, $to, $user = null)
    {
        /*
         * Clickatell Api Specification:
         *
         * The mobile number of the handset to which the message must be delivered (MSISDN).
         * The number must be in international format with no leading zeros or + symbol.
         * 27831234567 (South Africa)
         * 16501234567 (USA)
         * 44123456789 (UK)
         */

        $to = ltrim($to, '+');
        $to = ltrim($to, '0');

        if (! self::$forceSend && config('sms.sms_enabled') === 'no') {
            self::createLog($text, $to, $user);

            return true;
        }

//        $json = json_encode([
//            'from' => config('sms.sender_id'),
//            'to' => [$to],
//            'text' => $text,
//            'escalate' => 1,
//        ]);

        $client = new Rest();

        try {
            $client->sendMessage([
                'to' => [$to],
                'content' => $text,
            ]);

            self::createLog($text, $to, $user);

            return true;
        } catch (ClickatellException $e) {
            Log::error($e);
            return false;
        }
    }

    private static function createLog($text, $to, $user = null)
    {
        if ($user) {
            $log = new SmsLog();
            $log->user_id = $user->id;
            $log->phone = $to;
            $log->message = $text;
            $log->save();
        }
    }

    public static function checkTimeout(User $user)
    {
        if ($user->two_fa_otp === null) {
            return 0;
        }
        $lastSms = $user->lastSms;
        //dump($lastSms);
        if (! $lastSms) {
            return 0;
        }

        $timeout = config('sms.timeout');
        $diff = Carbon::now()->diffInSeconds($lastSms->created_at);
        if ($diff > $timeout) {
            return 0;
        }

        return $timeout - $diff;
    }
}
