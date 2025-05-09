<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AmusementController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', [TestController::class, 'ping']);


Route::apiResource('amusements', AmusementController::class);
// Route::get    ('amusements',             [AmusementController::class, 'index']);
// Route::post   ('amusements',             [AmusementController::class, 'store']);
// Route::get    ('amusements/{amusement}', [AmusementController::class, 'show']);
// Route::put    ('amusements/{amusement}', [AmusementController::class, 'update']);
// Route::delete ('amusements/{amusement}', [AmusementController::class, 'destroy']);
