<?php
namespace Henrotaym\AccountSynchronizer\Models;

use Henrotaym\AccountSynchronizer\Contracts\AccountContract;
use Henrotaym\AccountSynchronizer\Contracts\SynchronizerContract;

/**
 * Synchronizing account when saved event occurs.
 */
trait SynchronizeWhenSaved
{
    protected static function bootSynchronizeWhenSaved()
    {
        static::created(function (AccountContract $account) {
            app()->make(SynchronizerContract::class)->create($account);
        });

        static::updated(function (AccountContract $account) {
            app()->make(SynchronizerContract::class)->update($account);
        });

        static::deleted(function (AccountContract $account) {
            app()->make(SynchronizerContract::class)->delete($account);
        });
    }
}