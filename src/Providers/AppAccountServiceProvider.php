<?php
namespace Deegitalbe\TrustupProAppCommon\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Deegitalbe\TrustupProAppCommon\Package;
use Deegitalbe\TrustupProAppCommon\Models\User;
use Deegitalbe\TrustupProAppCommon\Synchronizer;
use Deegitalbe\TrustupProAppCommon\Api\AdminAppApi;
use Deegitalbe\TrustupProAppCommon\Api\TrustupProApi;
use Deegitalbe\TrustupProAppCommon\Models\Professional;
use Henrotaym\LaravelApiClient\Contracts\ClientContract;
use Deegitalbe\TrustupProAppCommon\Api\Client\AdminClient;
use Deegitalbe\TrustupProAppCommon\Contracts\UserContract;
use Deegitalbe\TrustupProAppCommon\Observers\AccountObserver;
use Deegitalbe\TrustupProAppCommon\Api\Client\TrustupProClient;
use Deegitalbe\TrustupProAppCommon\Contracts\ProfessionalContract;
use Deegitalbe\TrustupProAppCommon\Contracts\SynchronizerContract;
use Deegitalbe\ServerAuthorization\Http\Middleware\AuthorizedServer;
use Deegitalbe\TrustupProAppCommon\Facades\Package as PackageFacade;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\AdminAppApiContract;
use Deegitalbe\TrustupProAppCommon\Api\Credential\TrustupProCredential;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\TrustupProApiContract;
use Deegitalbe\TrustupProAppCommon\Api\Credential\AdminClientCredential;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\Client\AdminClientContract;
use Deegitalbe\TrustupProAppCommon\Http\Middleware\UserHavingAccessToAccount;
use Deegitalbe\TrustupProAppCommon\Http\Middleware\SettingAccountAsEnvironment;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\Client\TrustupProClientContract;
use Deegitalbe\TrustupVersionedPackage\Contracts\VersionedPackageCheckerContract;

class AppAccountServiceProvider extends ServiceProvider
{
    /**
     * Provider register method
     * 
     * @return void
     */
    public function register()
    {
        $this->registerPackageFacade()
            ->registerConfig()
            ->registerTrustupProApi()
            ->registerAdminAppApi()
            ->registerSynchronizer()
            ->registerModels();
    }

    /**
     * Registering package facade.
     * 
     * @return self
     */
    protected function registerPackageFacade(): self
    {
        $this->app->bind(Package::$prefix, function($app) {
            return $app->make(Package::class);
        });

        return $this;
    }

    /**
     * Registering package configuration.
     * 
     * @return self
     */
    protected function registerConfig(): self
    {
        $this->mergeConfigFrom($this->getConfigPath(), PackageFacade::getPrefix());

        return $this;
    }

    /**
     * Registering trustup pro API.
     * 
     * @return self
     */
    protected function registerTrustupProApi(): self
    {
        $this->app->bind(TrustupProClientContract::class, function($app) {
            return new TrustupProClient(new TrustupProCredential);
        });

        $this->app->bind(TrustupProApiContract::class, TrustupProApi::class);

        return $this;
    }

    /**
     * Registering admin.trustup.pro app API.
     * 
     * @return self
     */
    protected function registerAdminAppApi(): self
    {
        $this->app->bind(AdminClientContract::class, function($app) {
            return new AdminClient(new AdminClientCredential);
        });

        $this->app->bind(AdminAppApiContract::class, AdminAppApi::class);

        return $this;
    }

    /**
     * Registering account synchronizer.
     * 
     * @return self
     */
    protected function registerSynchronizer(): self
    {
        $this->app->bind(SynchronizerContract::class, Synchronizer::class);

        return $this;
    }

    /**
     * Registering models.
     * 
     * @return self
     */
    protected function registerModels(): self
    {
        $this->app->bind(UserContract::class, User::class);
        $this->app->bind(ProfessionalContract::class, Professional::class);

        return $this;
    }
    
    /**
     * Booting provider.
     * 
     * @return void
     */
    public function boot()
    {
        $this->makeConfigPublishable()
            ->loadRoutes()
            ->registerPackageAsVersioned()
            ->createAccountRelatedMiddlewareGroup();
    }

    /**
     * Making config publishable.
     * 
     * @return self
     */
    protected function makeConfigPublishable(): self
    {
        if ($this->app->runningInConsole()):
            $this->publishes([
              $this->getConfigPath() => config_path(PackageFacade::getPrefix().'.php'),
            ], 'config');
        endif;

        return $this;
    }

    /**
     * Loading routes.
     * 
     * @return self
     */
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

    /**
     * Registering package to versioned package checker.
     * 
     * @return self
     */
    protected function registerPackageAsVersioned(): self
    {
        app()->make(VersionedPackageCheckerContract::class)
            ->addPackage(PackageFacade::getFacadeRoot());
        
        return $this;
    }

    /**
     * Setting up accountRelated middleware group.
     * 
     * @return self
     */
    protected function createAccountRelatedMiddlewareGroup(): self
    {
        $router = $this->app->make(Router::class)
            ->pushMiddlewareToGroup(PackageFacade::getAccountRelatedMiddlewareGroup(), UserHavingAccessToAccount::class)
            ->pushMiddlewareToGroup(PackageFacade::getAccountRelatedMiddlewareGroup(), SettingAccountAsEnvironment::class);
        
        return $this;
    }

    /**
     * Getting path to config.
     * 
     * @return string
     */
    protected function getConfigPath(): string
    {
        return __DIR__.'/../config/config.php';
    }
}