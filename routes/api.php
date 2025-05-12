<?php

use App\Http\Controllers\JwtTokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\AmusementController;
use App\Http\Controllers\TransactionController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/jwt-token', JwtTokenController::class);

Route::get('/test', [TestController::class, 'ping']);

/** Group API routes */
Route::prefix('/groups')->group(function () {
    Route::get('/', [GroupController::class, 'index']);
    Route::post('/', [GroupController::class, 'store']);
    Route::get('/{id}', [GroupController::class, 'show']);
    Route::put('/{id}', [GroupController::class, 'update']);
    Route::delete('/{id}', [GroupController::class, 'destroy']);
});

Route::apiResource('amusements', AmusementController::class);
// Route::get    ('amusements',             [AmusementController::class, 'index']);
// Route::post   ('amusements',             [AmusementController::class, 'store']);
// Route::get    ('amusements/{amusement}', [AmusementController::class, 'show']);
// Route::put    ('amusements/{amusement}', [AmusementController::class, 'update']);
// Route::delete ('amusements/{amusement}', [AmusementController::class, 'destroy']);

/** Group API routes */
Route::prefix('/transactions')->group(function () {
    Route::get('/', [TransactionController::class, 'index']);
    Route::post('/', [TransactionController::class, 'store']);
    Route::get('/{id}', [TransactionController::class, 'show']);
    Route::delete('/{id}', [TransactionController::class, 'destroy']);
});

