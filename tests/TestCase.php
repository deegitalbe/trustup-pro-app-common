<?php
namespace Deegitalbe\TrustupProAppCommon\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Henrotaym\LaravelHelpers\Providers\HelperServiceProvider;
use Henrotaym\LaravelApiClient\Providers\ClientServiceProvider;
use Deegitalbe\TrustupProAppCommon\Providers\AppAccountServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ClientServiceProvider::class,
            AppAccountServiceProvider::class,
            HelperServiceProvider::class
        ];
    }
}