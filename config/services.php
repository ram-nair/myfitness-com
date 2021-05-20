<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
     */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'twilio' => [
        'apiKey' => 'SK412a4339ad9efddef2fd9b8b7a7f567f',
        'apiSecret' => 'a17znpI4bBvi4URH8kG5vHwfvVDxJunj',
        'accountSid' => 'ACc78b0b3c8b21e510690a0e6696bcf783',
        'authToken' => '558b958e03ce85214bf1eefa94184755',
        'serviceSid' => (env('TWILIO_ENV') == 'production') ? '' : 'ISc21e231281a655803a50dcd29825b593',
    ],

];
