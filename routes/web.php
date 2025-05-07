<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::post('/tokens/create', function (Request $request) {

    $token = $request->user()->createToken($request->token_name);

 

    return ['token' => $token->plainTextToken];

});

require __DIR__.'/auth.php';
