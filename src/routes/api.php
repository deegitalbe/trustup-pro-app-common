<?php

use Illuminate\Support\Facades\Route;
use Deegitalbe\TrustupProAppCommon\Facades\Package;

/*
|--------------------------------------------------------------------------
| Package API Routes
|--------------------------------------------------------------------------
|
*/

// Account related routes
Route::prefix('accounts')->name('accounts.')->group(function() {
    Route::post('/', Package::config('routes.accounts.store'))->name('store');
    Route::prefix('{account_uuid}')->middleware(Package::userAccountAccessMiddleware('account_uuid'))->group(function() {
        Route::get('/', Package::config('routes.accounts.show'))->name('show');
    });
    Route::get('key/{key}', Package::config('routes.accounts.by_authorization_key'))->name('byAuthorizationKey');
});