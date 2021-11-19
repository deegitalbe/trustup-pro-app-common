<?php

use Illuminate\Support\Facades\Route;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Http\Middleware\AccountRelated;
use Deegitalbe\TrustupProAppCommon\Http\Controllers\Common\AccountController as CommonAccountController;
use Deegitalbe\TrustupProAppCommon\Http\Controllers\Common\Webhook\AccountController as WebhookAccountController;

/*
|--------------------------------------------------------------------------
| Package Common Routes
|--------------------------------------------------------------------------
|
*/

// Webhooks routes
Route::prefix('webhooks')->name('webhooks.')->group(function() {
    Route::prefix('accounts')->name('accounts.')->group(function() {
        Route::prefix('{account}')->middleware(AccountRelated::class)->group(function() {
            Route::put('/', [WebhookAccountController::class, 'update'])->name('update');
        });
    });
});

// Common routes
Route::prefix('accounts')->name('accounts.')->group(function() {
    Route::get('/', [CommonAccountController::class, 'index'])->name('index');   
});