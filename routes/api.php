<?php

use App\Http\Controllers\JwtTokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TransactionController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/jwt-token', JwtTokenController::class);

Route::get('/test', [TestController::class, 'ping']);

/** Group API routes */
Route::prefix('/transactions')->group(function () {
    Route::get('/', [TransactionController::class, 'index']);
    Route::post('/', [TransactionController::class, 'store']);
    Route::get('/{id}', [TransactionController::class, 'show']);
    Route::delete('/{id}', [TransactionController::class, 'destroy']);
});

