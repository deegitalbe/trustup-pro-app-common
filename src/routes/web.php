<?php

use Illuminate\Support\Facades\Route;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Http\Middleware\AccountRelated;
use Deegitalbe\TrustupProAppCommon\Http\Controllers\Common\AccountController;

/*
|--------------------------------------------------------------------------
| Package Webhooks Routes
|--------------------------------------------------------------------------
|
*/

// Webhook related routes
Route::prefix('webhooks')->name('webhooks.')->group(function() {
    Route::prefix('accounts')->name('accounts.')->group(function() {
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::prefix('{account}')->middleware(AccountRelated::class)->group(function() {
            Route::put('/', [AccountController::class, 'update'])->name('update');
        });
    });
});