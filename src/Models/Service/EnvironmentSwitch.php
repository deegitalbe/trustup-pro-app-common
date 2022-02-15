<?php
namespace Deegitalbe\TrustupProAppCommon\Models\Service;

use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Database\Connection;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AuthenticationRelatedContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Service\EnvironmentSwitchContract;
use Hyn\Tenancy\Contracts\CurrentHostname;
use Hyn\Tenancy\Contracts\Tenant;

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
     * Injecting dependencies
     * 
     * @param AuthenticationRelatedContract $authentication_related
     * @return void
     */
    public function __construct(AuthenticationRelatedContract $authentication_related)
    {
        $this->authentication_related = $authentication_related;
    }

    /**
     * Hyn environment.
     * 
     * @return Environment
     */
    protected function getHynEnvironment(): Environment
    {
        return app()->make(Environment::class);
    }

    /**
     * Hyn connection.
     * 
     * @return Connection
     */
    protected function getHynConnection(): Connection
    {
        return app()->make(Connection::class);
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
        $this->resetHynModels();
        $connection = $this->getHynConnection();
        $connection->set(null, $connection->systemName());
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