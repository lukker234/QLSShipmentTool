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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'qls_api' => [
        'user' => env('QLS_API_USER', 'default_user'),
        'password' => env('QLS_API_PASSWORD', 'default_password'),
        'base_url' => env('QLS_API_BASE_URL', 'https://api.example.com'),
    ],

    'company' => [
        'id' => env('COMPANY_ID', 'default_company_id'),
    ],

    'brand' => [
        'id' => env('BRAND_ID', 'default_brand_id'),
    ],

];
