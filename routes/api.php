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
use Illuminate\Support\Facades\Auth;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', [TestController::class, 'ping']);

Route::post('/validate-api-key', [ApiKeyController::class, 'validate']);

/** Group API routes */
Route::apiResource('/users', UserController::class)
    ->middleware('auth:sanctum');

// // API routes for logged-in users
Route::middleware('auth:sanctum')->group(function () {
    Route::patch('/user',  [UserController::class, 'updateSelf']); // Update the logged-in user's data
    // Route::delete('/user', [UserController::class, 'destroySelf']); // Delete the logged-in user's account - not sure if we want to allow this
});

Route::apiResource('/groups', GroupController::class);

Route::apiResource('/amusements', AmusementController::class)->middleware('json.accept')->middleware('auth:sanctum');

Route::apiResource('/votes', VoteController::class)->middleware('json.accept');

Route::apiResource('/stamps', StampController::class)->middleware('json.accept');

Route::middleware(['api.auth', 'json.accept'])->group(function () {
    Route::apiResource('/transactions', TransactionController::class);
});

Route::middleware('auth:sanctum')->get('/jwt-token', JwtTokenController::class);

// Route::get('/cors-test', function () {
//     return response()->json([
//         'success' => true,
//         'message' => 'CORS is working!',
//         'headers' => request()->headers->all(),
//         'origin' => request()->headers->get('origin'),
//     ]);
// })->middleware('web');

// Route::get('/auth-test', function (Request $request) {
//     return response()->json([
//         'success' => true,
//         'message' => 'Authentication test endpoint',
//         'is_authenticated' => auth()->check(),
//         'user' => $request->user(),S
//         'session_id' => session()->getId(),
//         'cookies' => $request->cookies->all(),
//     ]);
// });

// Route::get('/debug-sanctum', function () {
//     return response()->json([
//         'stateful_domains' => config('sanctum.stateful'),
//         'frontend_url' => env('FRONTEND_URL'),
//         'parsed_host' => parse_url(env('FRONTEND_URL'), PHP_URL_HOST),
//         'app_url' => env('APP_URL'),
//     ]);
// });
