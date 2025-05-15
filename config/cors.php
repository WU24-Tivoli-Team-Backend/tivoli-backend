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
        'https://wu-24-tivoli.vercel.app',
        'https://wu-24-tivoli.vercel.app/'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'max_age' => 0,

    'exposed_headers' => ['Set-Cookie'], // Important!

    'supports_credentials' => true,

];