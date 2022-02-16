<?php
namespace Deegitalbe\TrustupProAppCommon\Models\Service;

use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Database\Connection;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AuthenticationRelatedContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Service\EnvironmentSwitchContract;
use Hyn\Tenancy\Contracts\CurrentHostname;
use Hyn\Tenancy\Contracts\Tenant;
use Illuminate\Database\DatabaseManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Config;

/** Environment Switch */
class EnvironmentSwitch implements EnvironmentSwitchContract
{
    /**
     * Authentication related actions.
     * 
     * @var AuthenticationRelatedContract
     */
    protected $authentication_related;

    /**
     * Application.
     * 
     * @var Application
     */
    protected $app;

    /**
     * Database manager.
     * 
     * @var DatabaseManager
     */
    protected $database_manager;

    /**
     * Injecting dependencies
     * 
     * @param AuthenticationRelatedContract $authentication_related
     * @param Application $app
     * @return void
     */
    public function __construct(AuthenticationRelatedContract $authentication_related, Application $app, DatabaseManager $database_manager)
    {
        $this->authentication_related = $authentication_related;
        $this->app = $app;
        $this->database_manager = $database_manager;
    }

    /**
     * Hyn environment.
     * 
     * @return Environment
     */
    protected function getHynEnvironment(): Environment
    {
        return $this->app->make(Environment::class);
    }

    /**
     * Hyn connection.
     * 
     * @return Connection
     */
    protected function getHynConnection(): Connection
    {
        return $this->app->make(Connection::class);
    }

    /**
     * Getting current enviroment
     * 
     * @return AccountContract|null Null means we use system connection.
     */
    public function getCurrentEnvironment(): ?AccountContract
    {
        return $this->getHynEnvironment()->tenant();
    }

    /**
     * Switching to given account environment.
     * 
     * @param AccountContract $account
     * @return static
     */
    public function toAccountEnvironment(AccountContract $account): EnvironmentSwitchContract
    {
        $this->getHynEnvironment()->tenant($account);
        $this->authentication_related->setAccount($account);

        return $this;
    }

    /**
     * Switching to system environment.
     * 
     * @return static
     */
    public function toSystemEnvironment(): EnvironmentSwitchContract
    {
        $this->resetHynModels()
            ->removeTenantConnection();

        $this->authentication_related->setAccount(null);

        return $this;
    }

    /**
     * Resetting Hyn models to default values.
     * 
     * @return static
     */
    protected function resetHynModels(): self
    {
        $empty = function () {
            return null;
        };

        $this->app->singleton(Tenant::class, $empty);
        $this->app->singleton(CurrentHostname::class, $empty);

        return $this;
    }

    /**
     * Removing tenant connection.
     * 
     * @return static
     */
    protected function removeTenantConnection(): self
    {
        $tenant_connection_name = config('tenancy.db.tenant-connection-name');
        
        // Purging cache concerning tenant connection.
        $this->database_manager->purge($tenant_connection_name);
        
        // Removing tenant connection from existing ones.
        $connections = collect(config('database.connections'))
            ->filter(function($value, $name) use ($tenant_connection_name) {
                return $name !== $tenant_connection_name;
            });
        
        // Setting connections as filtered ones.
        Config::set('database.connections', $connections);

        return $this;
    }

    /**
     * Calling given callback in system environment.
     * 
     * Afterwards it goes back to previous environment.
     * 
     * @param callable $callback Callback to execute in system environment.
     * @return mixed  Returning callback response.
     */
    public function callInSystemEnvironment(callable $callback)
    {
        return $this->callInEnvironment(null, $callback);
    }

    /**
     * Calling given callback in given account environment.
     * 
     * Afterwards it goes back to previous environment.
     * 
     * @param AccountContract Switching environment to given account.
     * @param callable $callback Callback to execute in account environment.
     * @return mixed  Returning callback response.
     */
    public function callInAccountEnvironment(AccountContract $account, callable $callback)
    {
        return $this->callInEnvironment($account, $callback);
    }

    /**
     * Calling in given environment.
     * 
     * Afterwards it goes back to previous environment.
     * 
     * @param AccountContract|null $environment Null considered as system environment.
     * @return mixed Returning callback response.
     */
    protected function callInEnvironment(?AccountContract $environment, callable $callback)
    {
        $previous  = $this->getCurrentEnvironment();

        // Switching to.
        $environment
            ? $this->toAccountEnvironment($environment) 
            : $this->toSystemEnvironment();
        
        // Calling callback.
        $response = $callback();

        // Switching back.
        $previous
            ? $this->toAccountEnvironment($previous) 
            : $this->toSystemEnvironment();

        return $response;
    }
}