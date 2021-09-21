<?php
namespace Henrotaym\AccountSynchronizer\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Henrotaym\LaravelApiClient\Providers\ClientServiceProvider;
use Henrotaym\AccountSynchronizer\Providers\AppAccountServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ClientServiceProvider::class,
            AppAccountServiceProvider::class
        ];
    }
}