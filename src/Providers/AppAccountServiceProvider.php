<?php
namespace Deegitalbe\TrustupProAppCommon\Providers;

use Illuminate\Support\Facades\Route;
use Deegitalbe\TrustupProAppCommon\Package;
use Deegitalbe\TrustupProAppCommon\Models\App;
use Deegitalbe\TrustupProAppCommon\Models\User;
use Deegitalbe\TrustupProAppCommon\Synchronizer;
use Deegitalbe\TrustupProAppCommon\Api\AdminAppApi;
use Deegitalbe\TrustupProAppCommon\Api\TrustupProApi;
use Deegitalbe\TrustupProAppCommon\Models\Professional;
use Deegitalbe\TrustupProAppCommon\AuthenticationRelated;
use Deegitalbe\TrustupProAppCommon\Contracts\AppContract;
use Deegitalbe\TrustupProAppCommon\Api\Client\AdminClient;
use Deegitalbe\TrustupProAppCommon\Contracts\UserContract;
use Deegitalbe\TrustupProAppCommon\Commands\InstallPackage;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Models\Query\AccountQuery;
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
use Deegitalbe\TrustupProAppCommon\Projectors\Hostname\HostnameProjector;
use Deegitalbe\TrustupProAppCommon\Contracts\AuthenticationRelatedContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\Client\AdminClientContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\Client\TrustupProClientContract;
use Deegitalbe\TrustupVersionedPackage\Contracts\VersionedPackageCheckerContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Service\StoringAccountServiceContract;
use Henrotaym\LaravelPackageVersioning\Providers\Abstracts\VersionablePackageServiceProvider;

class AppAccountServiceProvider extends VersionablePackageServiceProvider
{
    public static function getPackageClass(): string
    {
        return Package::class;
    }

    /**
     * Adding this to service provider register() method.
     * 
     * @return void
     */
    protected function addToRegister(): void
    {
        $this
            ->registerTrustupProApi()
            ->registerAdminAppApi()
            ->registerSynchronizer()
            ->registerModels()
            ->registerQueryBuilders()
            ->registerAuthenticationRelated()
            ->registerStoringAccountService();
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
        $this->app->bind(AppContract::class, App::class);
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
     * Adding this to service provider boot() method.
     * 
     * @return void
     */
    protected function addToBoot(): void
    {
        $this->registerCommands()
            ->loadRoutes()
            ->registerProjectors()
            ->registerPackageAsVersioned();
    }

    /**
     * Registering commands
     * 
     * @return self
     */
    protected function registerCommands(): self
    {
        $this->registerCommand(InstallPackage::class);

        return $this;
    }

    /**
     * Loading routes.
     * 
     * @return self
     */
    protected function loadRoutes(): self
    {
        return $this->loadCommonRoutes()
            ->loadApiRoutes();
    }

    /**
     * Loading web routes.
     * 
     * @return self
     */
    protected function loadCommonRoutes(): self
    {
        Route::prefix('common-package')
            ->name('common-package.')
            ->middleware(AuthorizedServer::class)
            ->group(function () {
                $this->loadRoutesFrom(__DIR__.'/../routes/common.php');
            });

        return $this;
    }

    /**
     * Loading api routes.
     * 
     * @return self
     */
    protected function loadApiRoutes(): self
    {
        Route::prefix('api')
            ->name('api.')
            ->middleware(['api', PackageFacade::authenticatedUserMiddleware()])
            ->group(function () {
                $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
            });

        return $this;
    }

    /**
     * Registering projectors used by this package.
     * 
     * @see https://spatie.be/docs/laravel-event-sourcing for more details.
     * @return self
     */
    protected function registerProjectors(): self
    {
        $success = $this->defineSpatieRelatedAliases();
        if (!$success):
            return $this;
        endif;

        $facade = PackageFacade::spatieEventSourcingFacade();
        if(!class_exists($facade)):
            return $this;
        endif;

        $facade::addProjectors([
            AccountProjector::class,
            HostnameProjector::class
        ]);

        return $this;
    }

    /**
     * Registering spatie related aliases.
     * 
     * It was needed since our applications do not use same spatie package version.
     * 
     * @return bool Success state concerning aliases
     */
    protected function defineSpatieRelatedAliases(): bool
    {
        $success = true;
        
        collect([
            \Deegitalbe\TrustupProAppCommon\Events\ProjectorEvent::class => PackageFacade::spatieEventSourcingEvent(),
            \Deegitalbe\TrustupProAppCommon\Projectors\Projector::class => PackageFacade::spatieEventSourcingProjector()
        ])
            ->each(function($class, $alias) use (&$success) {
                if (!class_exists($class)):
                    $success = false;
                    return;
                endif;
                class_alias($class, $alias);
            });

        return $success;
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
}