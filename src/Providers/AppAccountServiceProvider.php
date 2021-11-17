<?php
namespace Deegitalbe\TrustupProAppCommon\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Deegitalbe\TrustupProAppCommon\Package;
use App\Projectors\Hostname\HostnameProjector;
use Deegitalbe\TrustupProAppCommon\Synchronizer;
use Deegitalbe\TrustupProAppCommon\Api\AdminAppApi;
use Deegitalbe\TrustupProAppCommon\Api\TrustupProApi;
use Deegitalbe\TrustupProAppCommon\Models\Professional;
use Henrotaym\LaravelApiClient\Contracts\ClientContract;
use Deegitalbe\TrustupProAppCommon\AuthenticationRelated;
use Deegitalbe\TrustupProAppCommon\Api\Client\AdminClient;
use Deegitalbe\TrustupProAppCommon\Contracts\UserContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Models\Query\AccountQuery;
use Deegitalbe\TrustupProAppCommon\Observers\AccountObserver;
use Deegitalbe\TrustupProAppCommon\Api\Client\TrustupProClient;
use Deegitalbe\TrustupProAppCommon\Contracts\ProfessionalContract;
use Deegitalbe\TrustupProAppCommon\Contracts\SynchronizerContract;
use Deegitalbe\ServerAuthorization\Http\Middleware\AuthorizedServer;
use Deegitalbe\TrustupProAppCommon\Facades\Package as PackageFacade;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\AdminAppApiContract;
use Deegitalbe\TrustupProAppCommon\Api\Credential\TrustupProCredential;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\TrustupProApiContract;
use Deegitalbe\TrustupProAppCommon\Projectors\Account\AccountProjector;
use Deegitalbe\TrustupProAppCommon\Api\Credential\AdminClientCredential;
use Deegitalbe\TrustupProAppCommon\Contracts\Query\AccountQueryContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AuthenticationRelatedContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\Client\AdminClientContract;
use Deegitalbe\TrustupProAppCommon\Http\Middleware\UserHavingAccessToAccount;
use Deegitalbe\TrustupProAppCommon\Http\Middleware\SettingAccountAsEnvironment;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\Client\TrustupProClientContract;
use Deegitalbe\TrustupVersionedPackage\Contracts\VersionedPackageCheckerContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Service\StoringAccountServiceContract;

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
            ->registerModels()
            ->registerQueryBuilders()
            ->registerAuthenticationRelated()
            ->registerStoringAccountService();
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
        $this->app->bind(AccountContract::class, PackageFacade::account());
        $this->app->bind(UserContract::class, User::class);
        $this->app->bind(ProfessionalContract::class, Professional::class);

        return $this;
    }

    /**
     * Registering models query builders.
     * 
     * @return self
     */
    protected function registerQueryBuilders(): self
    {
        $this->app->bind(AccountQueryContract::class, AccountQuery::class);

        return $this;
    }

    /**
     * Registering Authentication related singleton.
     * 
     * @return self
     */
    protected function registerAuthenticationRelated(): self
    {
        $this->app->singleton(AuthenticationRelatedContract::class, AuthenticationRelated::class);

        return $this;
    }

    /**
     * Registering storing account service.
     * 
     * @return self
     */
    protected function registerStoringAccountService(): self
    {
        $this->app->bind(StoringAccountServiceContract::class, PackageFacade::storingAccountService());

        return $this;
    }


    /**
     * Registering spatie related aliases.
     * 
     * It was needed since our applications do not use same spatie package version.
     * 
     * @return self
     */
    protected function defineSpatieRelatedAliases()
    {
        class_alias(PackageFacade::spatieEventSourcingEvent(), \Deegitalbe\TrustupProAppCommon\Events\ProjectorEvent::class);
        class_alias(PackageFacade::spatieEventSourcingProjector(), \Deegitalbe\TrustupProAppCommon\Projectors\Projector::class);

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
            ->registerPackageAsVersioned();
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
     * Getting path to config.
     * 
     * @return string
     */
    protected function getConfigPath(): string
    {
        return __DIR__.'/../config/config.php';
    }
}