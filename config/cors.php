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
        'http://localhost:5173',
        'http://localhost:5173',
        'https://tivoli.yrgobanken.vip',
        'https://tivoli.yrgobanken.vip/',
        'https://enter-paneer-game.vercel.app/',
        'https://enter-paneer-game.vercel.app',
        'https://tivoli-mole.vercel.app/',
        'https://tivoli-mole.vercel.app',
        'https://golden-beetlebug.vercel.app/',
        'https://golden-beetlebug.vercel.app',
        'https://thehauntedescape.vercel.app/',
        'https://thehauntedescape.vercel.app',
        'https://misfortunate-unfair-funfair-co75.vercel.app/',
        'https://misfortunate-unfair-funfair-co75.vercel.app',
        'https://go-kart-nine.vercel.app/',
        'https://go-kart-nine.vercel.app',
        'https://wheel-of-fortune-lilac.vercel.app/',
        'https://wheel-of-fortune-lilac.vercel.app',
        'https://jolor2024.github.io/bingo/',
        'https://jolor2024.github.io/bingo',
        'https://runehunt.vercel.app/',
        'https://runehunt.vercel.app',

    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Content-Type',
        'X-API-Key',
        'Authorization',
        'X-Requested-With',
        'Accept',
        'Origin',
        'X-XSRF-TOKEN',
        'Cache-control'
    ],

    'max_age' => 0,

    'exposed_headers' => ['Set-Cookie'], // Important!

    'supports_credentials' => true,

];
