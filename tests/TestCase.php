<?php
namespace Deegitalbe\TrustupProAppCommon\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Henrotaym\LaravelHelpers\Providers\HelperServiceProvider;
use Henrotaym\LaravelApiClient\Providers\ClientServiceProvider;
use Deegitalbe\ChargebeeClient\Providers\ChargebeeClientProvider;
use Deegitalbe\TrustupProAppCommon\Providers\AppAccountServiceProvider;
use Deegitalbe\ServerAuthorization\Providers\ServerAuthorizationServiceProvider;
use Deegitalbe\TrustupVersionedPackage\Providers\TrustupVersionedPackageServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            TrustupVersionedPackageServiceProvider::class,
            ServerAuthorizationServiceProvider::class,
            ClientServiceProvider::class,
            ChargebeeClientProvider::class,
            AppAccountServiceProvider::class,
            HelperServiceProvider::class
        ];
    }
}