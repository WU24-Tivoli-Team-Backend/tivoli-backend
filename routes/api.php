<?php

use App\Http\Controllers\JwtTokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\AmusementController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\StampController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApiKeyController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', [TestController::class, 'ping']);

Route::post('/validate-api-key', [ApiKeyController::class, 'validate']);

/** Group API routes */
Route::apiResource('/users', UserController::class);

Route::apiResource('/groups', GroupController::class);

Route::apiResource('/amusements', AmusementController::class)->middleware('json.accept')->middleware('auth:sanctum');

Route::apiResource('/votes', VoteController::class)->middleware('json.accept');

Route::apiResource('/stamps', StampController::class)->middleware('json.accept');

Route::middleware(['api.auth', 'json.accept'])->group(function () {
    Route::apiResource('/transactions', TransactionController::class);
});

Route::middleware('auth:sanctum')->get('/jwt-token', JwtTokenController::class);

Route::get('/cors-test', function () {
    return response()->json([
        'success' => true,
        'message' => 'CORS is working!',
        'headers' => request()->headers->all(),
        'origin' => request()->headers->get('origin'),
    ]);
})->middleware('web');

Route::get('/cors-test-2', function () {
    return response()->json([
        'success' => true,
        'message' => 'CORS test works!',
    ]);
});
