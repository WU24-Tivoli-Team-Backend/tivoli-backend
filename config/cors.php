<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        env('FRONTEND_URL', 'http://localhost:3000'),
        rtrim(env('FRONTEND_URL', 'http://localhost:3000'), '/'),
        env('FRONTEND_URL', 'http://localhost:3000') . '/',
        env('FRONTEND_URL', 'http://localhost:3001'),
        rtrim(env('FRONTEND_URL', 'http://localhost:3001'), '/'),
        env('FRONTEND_URL', 'http://localhost:3001') . '/',
        'https://tivoli.yrgobanken.vip',
        'https://tivoli.yrgobanken.vip/'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Content-Type',
        'Authorization',
        'X-Requested-With',
        'Accept',
        'Origin',
        'Pragma',                    // lägg till Pragma
    ],

    'max_age' => 0,

    'exposed_headers' => ['Set-Cookie'], // Important!

    'supports_credentials' => true,

];