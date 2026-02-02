<?php

return [

    /*
    |--------------------------------------------------------------------------
    | QiCard API Credentials
    |--------------------------------------------------------------------------
    |
    | These credentials are used to authenticate with the QiCard Payment Gateway.
    | You can obtain these from your QiCard merchant account.
    |
    | Test Credentials (UAT Sandbox):
    | - Username: paymentgatewaytest
    | - Password: WHaNFE5C3qlChqNbAzH4
    | - Terminal ID: 237984
    |
    */

    'username' => env('QICARD_USERNAME', 'paymentgatewaytest'),

    'password' => env('QICARD_PASSWORD', 'WHaNFE5C3qlChqNbAzH4'),

    'terminal_id' => env('QICARD_TERMINAL_ID', '237984'),

    /*
    |--------------------------------------------------------------------------
    | API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the QiCard Payment Gateway API. Use the sandbox URL
    | for testing and switch to production URL when going live.
    |
    | Sandbox: https://uat-sandbox-3ds-api.qi.iq/api/v1
    | Production: Contact QiCard for production URL
    |
    */

    'base_url' => env('QICARD_BASE_URL', 'https://uat-sandbox-3ds-api.qi.iq/api/v1'),

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | The default currency code to use for transactions. The QiCard gateway
    | primarily supports IQD (Iraqi Dinar).
    |
    | Supported: "IQD"
    |
    */

    'currency' => env('QICARD_CURRENCY', 'IQD'),

    /*
    |--------------------------------------------------------------------------
    | Locale
    |--------------------------------------------------------------------------
    |
    | The locale to use for the payment page. This determines the language
    | displayed on the QiCard payment form.
    |
    | Supported: "en_US", "ar_IQ"
    |
    */

    'locale' => env('QICARD_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Callback URLs
    |--------------------------------------------------------------------------
    |
    | These URLs are used by QiCard to redirect the user after payment
    | completion and to send webhook notifications about payment status.
    |
    | finish_url: Where the user is redirected after completing payment
    | notification_url: Where QiCard sends async webhook notifications
    |
    */

    'finish_url' => env('QICARD_FINISH_URL', ''),

    'notification_url' => env('QICARD_NOTIFICATION_URL', ''),

];
