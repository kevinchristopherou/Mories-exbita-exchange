<?php
return [
    'SITE_NAME' => env('SITE_NAME', ''),
    'SITE_URL' => env('SITE_URL', ''),
    'SITE_COPYRIGHT' => env('SITE_COPYRIGHT', ''),
    'SITE_LOGO' => env('SITE_LOGO', ''),
    'SITE_LOGO_INVERT' => env('SITE_LOGO_INVERT', ''),
    'SITE_FAVICON' => env('SITE_FAVICON', ''),
    'SITE_CONTACT_EMAIL' => env('SITE_CONTACT_EMAIL', ''),

    'SOCIAL_FACEBOOK_URL' => env('SOCIAL_FACEBOOK_URL', ''),
    'SOCIAL_TWITTER_URL' => env('SOCIAL_TWITTER_URL', ''),

    'MAIL_DRIVER' => env('MAIL_DRIVER', ''),
    'MAIL_HOST' => env('MAIL_HOST', ''),
    'MAIL_PORT' => env('MAIL_PORT', ''),
    'MAIL_USERNAME' => env('MAIL_USERNAME', ''),
    'MAIL_PASSWORD' => env('MAIL_PASSWORD', ''),
    'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS', ''),
    'MAIL_FROM_NAME' => env('MAIL_FROM_NAME', ''),
    'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION', ''),
    'MAIL_TEST_ADDRESS' => env('MAIL_TEST_ADDRESS', ''),

    'S3_KEY' => env('S3_KEY', ''),
    'S3_SECRET' => env('S3_SECRET', ''),
    'S3_REGION' => env('S3_REGION', ''),
    'S3_BUCKET' => env('S3_BUCKET', ''),
    'S3_BUCKET_BACKUP' => env('S3_BUCKET_BACKUP', ''),

    'NOCAPTCHA_SITEKEY' => env('NOCAPTCHA_SITEKEY', ''),
    'NOCAPTCHA_SECRET' => env('NOCAPTCHA_SECRET', ''),

    'CLICKATELL_TOKEN' => env('CLICKATELL_TOKEN', ''),
    'CLICKATELL_TEST_NUMBER' => env('CLICKATELL_TEST_NUMBER', ''),
    'SMS_SENDER_ID' => env('SMS_SENDER_ID', ''),

    // Bitcoin Node
    'BITCOIND_USER' => env('BITCOIND_USER', ''),
    'BITCOIND_PASSWORD' => env('BITCOIND_PASSWORD', ''),
    'BITCOIND_HOST' => env('BITCOIND_HOST', ''),
    'BITCOIND_PORT' => env('BITCOIND_PORT', ''),
    'BITCOIND_CONFIRMATIONS_REQUIRED' => env('BITCOIND_CONFIRMATIONS_REQUIRED', 1),
    'BITCOIND_IS_TESTNET' => env('BITCOIND_IS_TESTNET', ''),

    // Ethereum Node
    'ETHEREUM_USER' => env('ETHEREUM_USER', ''),
    'ETHEREUM_PASSWORD' => env('ETHEREUM_PASSWORD', ''),
    'ETHEREUM_HOST' => env('ETHEREUM_HOST', ''),
    'ETHEREUM_PORT' => env('ETHEREUM_PORT', ''),
    'ETHEREUM_TRANSACTION_URL_LOOKUP' => env('ETHEREUM_TRANSACTION_URL_LOOKUP', 'https://etherscan.io/tx/'),
    'ETHEREUM_CONFIRMATIONS_REQUIRED' => env('ETHEREUM_CONFIRMATIONS_REQUIRED', 10),
    'ETHEREUM_STATE' => env('ETHEREUM_STATE', ''),

    'LICENSE_VALIDATOR' => env('LICENSE_VALIDATOR', 'https://exbita.com'),
    'LICENSE_ACTIVATED' => env('LICENSE_ACTIVATED', 'uninstalled'),
    'LICENSE_ACTIVATED_URL' => env('LICENSE_ACTIVATED_URL', ''),

    'APPLICATION_STATE' => env('APPLICATION_STATE', ''),
    'APPLICATION_CODE' => env('APPLICATION_CODE', ''),

    'GOOGLE_ANALYTICS_CODE' => env('GOOGLE_ANALYTICS_CODE', ''),

    'KYC_STATE' => env('KYC_STATE', ''),
];
