<?php
namespace Deegitalbe\TrustupProAppCommon\Providers;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Lcobucci\JWT\Signer\Key\InMemory;
use Illuminate\Foundation\Application;
use Deegitalbe\TrustupProAppCommon\Package;
use Deegitalbe\TrustupProAppCommon\Auth\Token;
use Deegitalbe\TrustupProAppCommon\Models\App;
use Deegitalbe\TrustupProAppCommon\Models\User;
use Deegitalbe\TrustupProAppCommon\Synchronizer;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Deegitalbe\TrustupProAppCommon\Api\AdminAppApi;
use Deegitalbe\TrustupProAppCommon\Auth\TokenGuard;
use Deegitalbe\TrustupProAppCommon\Auth\TokenParser;
use Deegitalbe\TrustupProAppCommon\Api\TrustupProApi;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use Deegitalbe\TrustupProAppCommon\Auth\TokenProvider;
use Deegitalbe\TrustupProAppCommon\Models\Professional;
use Deegitalbe\TrustupProAppCommon\AuthenticationRelated;
use Deegitalbe\TrustupProAppCommon\Contracts\AppContract;
use Deegitalbe\TrustupProAppCommon\Api\Client\AdminClient;
use Deegitalbe\TrustupProAppCommon\Contracts\UserContract;
use Deegitalbe\TrustupProAppCommon\Commands\InstallPackage;
use Deegitalbe\TrustupProAppCommon\Auth\Internals\Clockable;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Models\Query\AccountQuery;
use Deegitalbe\TrustupProAppCommon\Api\Client\TrustupProClient;
use Deegitalbe\TrustupProAppCommon\Contracts\Auth\TokenContract;
use Deegitalbe\TrustupProAppCommon\Contracts\ProfessionalContract;
use Deegitalbe\TrustupProAppCommon\Contracts\SynchronizerContract;
use Deegitalbe\ServerAuthorization\Http\Middleware\AuthorizedServer;
use Deegitalbe\TrustupProAppCommon\Facades\Package as PackageFacade;
use Deegitalbe\TrustupProAppCommon\Models\Service\EnvironmentSwitch;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\AdminAppApiContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Auth\TokenParserContract;
use Deegitalbe\TrustupProAppCommon\Api\Credential\TrustupProCredential;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\TrustupProApiContract;
use Deegitalbe\TrustupProAppCommon\Projectors\Account\AccountProjector;
use Deegitalbe\TrustupProAppCommon\Api\Credential\AdminClientCredential;
use Deegitalbe\TrustupProAppCommon\Contracts\Auth\TokenProviderContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Query\AccountQueryContract;
use Deegitalbe\TrustupProAppCommon\Projectors\Hostname\HostnameProjector;
use Deegitalbe\TrustupProAppCommon\Contracts\AuthenticationRelatedContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\Client\AdminClientContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Service\EnvironmentSwitchContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\Client\TrustupProClientContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Models\ContactContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Service\MeiliSearch\Contacts\ContactServiceContract;
use Deegitalbe\TrustupVersionedPackage\Contracts\VersionedPackageCheckerContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Service\StoringAccountServiceContract;
use Deegitalbe\TrustupProAppCommon\Models\Service\MeiliSearch\MeiliSearchIndexService;
use Henrotaym\LaravelPackageVersioning\Providers\Abstracts\VersionablePackageServiceProvider;
use Deegitalbe\TrustupProAppCommon\Contracts\Service\MeiliSearch\MeiliSearchIndexServiceContract;
use Deegitalbe\TrustupProAppCommon\Models\Contact;
use Deegitalbe\TrustupProAppCommon\Models\Service\MeiliSearch\Contacts\ContactService;

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
            ->registerEnvironmentSwitch()
            ->registerServices()
            ->registerJWTServices();
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
     * Registering services.
     * 
     * @return self
     */
    protected function registerServices(): self
    {
        return $this->registerEnvironmentSwitch()
            ->registerMeiliSearchServices()
            ->registerStoringAccountService();
    }

    /**
     * Registering environment switch.
     * 
     * @return self
     */
    protected function registerEnvironmentSwitch(): self
    {
        $this->app->bind(EnvironmentSwitchContract::class, EnvironmentSwitch::class);

        return $this;
    }

    /**
     * Registering meilisearch services.
     * 
     * @return self
     */
    protected function registerMeiliSearchServices(): self
    {
        return $this->registerMeilisearchContactService()
            ->registerMeiliSearchIndexService();
    }

    /**
     * Registering meilisearch contact service.
     * 
     * @return self
     */
    protected function registerMeilisearchContactService(): self
    {
        $this->app->bind(ContactServiceContract::class, ContactService::class);
        $this->app->bind(ContactContract::class, Contact::class);

        return $this;
    }

    /**
     * Registering meilisearch index service.
     * 
     * @return self
     */
    protected function registerMeiliSearchIndexService(): self
    {
        $this->app->bind(MeiliSearchIndexServiceContract::class, MeiliSearchIndexService::class);

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
     * Registering everythin related to JWT tokens.
     */
    protected function registerJWTServices(): self
    {
        $this->registerJWTConfig();

        $this->app->bind(TokenContract::class, Token::class);
        $this->app->bind(TokenParserContract::class, TokenParser::class);
        $this->app->bind(TokenProviderContract::class, TokenProvider::class);

        return $this;
    }

    /**
     * Registering JWT config and bind it to container.
     *
     * @return self
     */
    protected function registerJWTConfig(): self
    {
        $this->app->bind(Configuration::class, function(Application $app) {
            $config = Configuration::forAsymmetricSigner(
                new Sha256(),
                InMemory::empty(),
                InMemory::file(PackageFacade::jwtPublicKeyPath())
            );

            $config->setValidationConstraints(
                new SignedWith($config->signer(), $config->verificationKey()),
                new StrictValidAt($app->make(Clockable::class))
            );

            return $config;
        });

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
            ->registerPackageAsVersioned()
            ->registerTokenAuth();
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
                // if config class is not valid => fail and stop.
                if (!class_exists($class)):
                    $success = false;
                    return;
                endif;

                // If alias already defined => stop.
                if (class_exists($alias)):
                    return;
                endif;
                
                // Register alias.
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

    protected function registerTokenAuth(): self
    {
        Auth::provider('trustup-pro-token', function ($app) {
            return $app->make(TokenProviderContract::class);
        });

        Auth::extend('trustup-pro', function ($app) {
            return $app->make(TokenGuard::class);
        });

        return $this;
    }
}