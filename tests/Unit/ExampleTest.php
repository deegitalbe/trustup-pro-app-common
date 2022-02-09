<?php
namespace Deegitalbe\TrustupProAppCommon\Tests\Unit;

use Illuminate\Support\Facades\Log;
use Deegitalbe\TrustupProAppCommon\Tests\TestCase;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Exceptions\AdminAppApi\GetAppsException;
use Deegitalbe\TrustupVersionedPackage\Contracts\VersionedPackageCheckerContract;
use Henrotaym\LaravelPackageVersioning\Testing\Traits\InstallPackageTest;

class ExampleTest extends TestCase
{
    use InstallPackageTest;
    
    /**
     * @test
     */
    public function returning_true()
    {
        Log::shouldReceive('error')
            ->once()
            ->withArgs(function($message) {
                return $message === (new GetAppsException)->getMessage();
            });
            
        $this->assertCount(0, Package::getProjects());
    }
}