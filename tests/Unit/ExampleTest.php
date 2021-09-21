<?php
namespace Henrotaym\AccountSynchronizer\Tests\Unit;

use Henrotaym\LaravelApiClient\Client;
use Henrotaym\AccountSynchronizer\Synchronizer;
use Henrotaym\AccountSynchronizer\Tests\TestCase;
use Henrotaym\LaravelApiClient\Contracts\ClientContract;
use Henrotaym\LaravelApiClient\Contracts\RequestContract;

class ExampleTest extends TestCase
{
    /**
     * @test
     */
    public function returning_true()
    {
        $this->assertTrue(true);
    }
}