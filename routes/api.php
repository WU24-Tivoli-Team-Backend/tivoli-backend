<?php

use App\Http\Controllers\JwtTokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\AmusementController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\StampController;
use App\Http\Middleware\ForceAcceptJson;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\UserController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/jwt-token', JwtTokenController::class);

Route::get('/test', [TestController::class, 'ping']);

/** Group API routes */
Route::apiResource('/users', UserController::class)->middleware('auth:sanctum');

Route::apiResource('/groups', GroupController::class);

Route::apiResource('amusements', AmusementController::class)->middleware('json.accept');

Route::apiResource('/transactions', TransactionController::class)->middleware('json.accept')->middleware('auth:sanctum');

Route::apiResource('/stamps', StampController::class);

Route::apiResource('/votes', VoteController::class)->middleware('json.accept');

// if (app()->environment('local')) {
//     Route::get('/dev/token', function (Request $request) {
//         // You can specify a user ID in the query parameter or use a default one
//         $userId = $request->query('user_id', 1); // Default to user ID 1
//         $user = \App\Models\User::find($userId);
        
//         if (!$user) {
//             return response()->json(['error' => 'User not found'], 404);
//         }
        
//         // Optionally revoke old tokens
//         // $user->tokens()->delete();
        
//         // Create a new token with specific abilities if needed
//         $token = $user->createToken('postman-testing', ['*'])->plainTextToken;
        
//         return response()->json([
//             'user' => $user->only(['id', 'name', 'email']), // Send back user info for confirmation
//             'token' => $token
//         ]);
//     });
// }