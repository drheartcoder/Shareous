<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'facebook' => 
    [
        'client_id'     => '2020743008178955',
        'client_secret' => '74356934f6e38df0be2414a1f2d1ae85',
        'redirect'      => PHP_SAPI === 'cli' ? false : url('/social_auth/facebook/callback'),
    ],
    'google' => 
    [
        'client_id'     => '729094495229-o1irhighrr4h7g0ahl2t7n48ivesn71a.apps.googleusercontent.com',
        'client_secret' => 'FbXTfztRwS26N67iYz-JrKRA',
        'redirect'      => PHP_SAPI === 'cli' ? false : url('/social_auth/google/callback'),
        //'scope'         => ['userinfo_email', 'userinfo_profile', 'https://www.google.com/m8/feeds/'],
    ],
    'twitter' => 
    [
        'client_id'     => 'iOErSwtUjQgtZQLUf1cqPRKrk',
        'client_secret' => 'QeV2b3mqp0cQYXfASdihX2qJiLyHVbI3HHmCrR1zCC9ZhPyid9',
        'redirect'      => PHP_SAPI === 'cli' ? false : url('/social_auth/twitter/callback'),
        //'redirect'      => 'https://www.webwingdemo.com/node2/shareous/social_auth/twitter/callback',
    ],
];
