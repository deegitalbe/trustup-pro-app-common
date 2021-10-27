<?php
namespace Deegitalbe\TrustupProAppCommon\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Deegitalbe\TrustupProAppCommon\Package;
use Deegitalbe\TrustupProAppCommon\AdminClient;
use Deegitalbe\TrustupProAppCommon\Synchronizer;
use Deegitalbe\TrustupProAppCommon\Api\AdminAppApi;
use Henrotaym\LaravelApiClient\Contracts\ClientContract;
use Deegitalbe\TrustupProAppCommon\AdminClientCredential;
use Deegitalbe\TrustupProAppCommon\Observers\AccountObserver;
use Deegitalbe\TrustupProAppCommon\Contracts\AdminClientContract;
use Deegitalbe\TrustupProAppCommon\Contracts\SynchronizerContract;
use Deegitalbe\ServerAuthorization\Http\Middleware\AuthorizedServer;
use Deegitalbe\TrustupProAppCommon\Facades\Package as PackageFacade;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\AdminAppApiContract;
use Deegitalbe\TrustupVersionedPackage\Contracts\VersionedPackageCheckerContract;

class AppAccountServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerConfig();

        $this->app->bind(AdminClientContract::class, function($app) {
            return new AdminClient(new AdminClientCredential);
        });
        $this->app->bind(SynchronizerContract::class, Synchronizer::class);
        $this->app->bind(AdminAppApiContract::class, AdminAppApi::class);
        $this->app->bind('trustup_pro_app_common', function($app) {
            return $app->make(Package::class);
        });
    }

    public function boot()
    {
        $this->makeConfigPublishable()
            ->loadRoutes()
            ->registerPackage();
    }

    protected function registerPackage(): self
    {
        app()->make(VersionedPackageCheckerContract::class)
            ->addPackage(PackageFacade::getFacadeRoot());
        
        return $this;
    }

    protected function loadRoutes(): self
    {
        Route::group([
            'prefix' => 'common-package',
            'name' => "common-package.",
            'middleware' => AuthorizedServer::class
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/routes.php');
        });

        return $this;
    }

    protected function registerConfig(): self
    {
        $this->mergeConfigFrom($this->getConfigPath(), 'trustup_pro_app_common');

        return $this;
    }

    protected function makeConfigPublishable(): self
    {
        if ($this->app->runningInConsole()):
            $this->publishes([
              $this->getConfigPath() => config_path('trustup_pro_app_common.php'),
            ], 'config');
        endif;

        return $this;
    }

    protected function getConfigPath(): string
    {
        return __DIR__.'/../config/config.php';
    }
}