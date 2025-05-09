<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;


Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::post('/tokens/create', function (Request $request) {

    $token = $request->user()->createToken($request->token_name);

 

    return ['token' => $token->plainTextToken];

});

require __DIR__.'/auth.php';
