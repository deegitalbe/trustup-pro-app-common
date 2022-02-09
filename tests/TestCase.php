<?php
namespace Deegitalbe\TrustupProAppCommon\Tests;

use Henrotaym\LaravelApiClient\Providers\ClientServiceProvider;
use Deegitalbe\ChargebeeClient\Providers\ChargebeeClientProvider;
use Deegitalbe\TrustupProAppCommon\Providers\AppAccountServiceProvider;
use Henrotaym\LaravelPackageVersioning\Testing\VersionablePackageTestCase;
use Deegitalbe\ServerAuthorization\Providers\ServerAuthorizationServiceProvider;
use Deegitalbe\TrustupProAppCommon\Package;
use Deegitalbe\TrustupVersionedPackage\Providers\TrustupVersionedPackageServiceProvider;

class TestCase extends VersionablePackageTestCase
{
    public static function getPackageClass(): string
    {
        return Package::class;
    }

    public function getServiceProviders(): array
    {
        return [
            TrustupVersionedPackageServiceProvider::class,
            ServerAuthorizationServiceProvider::class,
            ClientServiceProvider::class,
            ChargebeeClientProvider::class,
            AppAccountServiceProvider::class,
        ];
    }
}