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

    'algolia' => [
        'appId' => env('ALGOLIA_PLACES_APP_ID'),
        'apiKey' => env('ALGOLIA_PLACES_API_KEY'),
    ],

    'stripe' => [
        'stripe_key' => env('STRIPE_KEY'),
        'stripe_secret' => env('STRIPE_SECRET'),
    ],

    'mapbox' => [
        'api_key' => env('MAPBOX_KEY'),
    ],

    'nexmo' => [
        'sms_from' => '0371700797',
    ],

    'openapi' => [
        'api_key' => env('OPENAPI_KEY'),
    ],

    'google_place_location' => [
        'api_key' => env('VUE_APP_PLACE_API_KEY'),
    ],

];
