<?php

use App\Http\Controllers\JwtTokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\AmusementController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\StampController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/jwt-token', JwtTokenController::class);

Route::get('/test', [TestController::class, 'ping']);

/** Group API routes */
Route::apiResource('/groups', GroupController::class);

Route::apiResource('amusements', AmusementController::class);

/** Group API routes */
Route::apiResource('/transactions', TransactionController::class);

Route::apiResource('/stamps', StampController::class);