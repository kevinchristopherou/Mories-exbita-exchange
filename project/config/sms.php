<?php

return [
    'token' => env('CLICKATELL_TOKEN', null),
    'testnumber' => env('CLICKATELL_TEST_NUMBER', null),
    'sms_enabled' => env('SMS_ENABLED', 'no'),
    'timeout' => env('SMS_TIMEOUT', 180),
    'sender_id' => env('SMS_SENDER_ID', 'Exbita'),
];
