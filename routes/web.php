<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('index');
});

Route::post('/tokens/create', function (Request $request) {

    $token = $request->user()->createToken($request->token_name);
    return ['token' => $token->plainTextToken];
});

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.submit');
    Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/vp', [AdminController::class, 'showUserStamps'])->name('admin.vp');
    Route::post('/reset-balances', [AdminController::class, 'resetBalances'])->name('admin.reset.balances');
    Route::post('/rune-balance', [AdminController::class, 'updateRuneBalance'])->name('admin.update.rune.balance');
    Route::post('/reset-votes', [AdminController::class, 'resetVotes'])->name('admin.reset.votes');
    Route::post('/reset-stamps', [AdminController::class, 'resetStamps'])->name('admin.reset.stamps');
});

require __DIR__ . '/auth.php';
