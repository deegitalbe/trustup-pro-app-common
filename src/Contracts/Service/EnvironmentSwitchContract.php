<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts\Service;

use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;

/** Environment Switch */
interface EnvironmentSwitchContract
{
    /**
     * Getting current enviroment
     * 
     * @return AccountContract|null Null means we use system connection.
     */
    public function getCurrentEnvironment(): ?AccountContract;

    /**
     * Switching to given account environment.
     * 
     * @param AccountContract $account
     * @return static
     */
    public function toAccountEnvironment(AccountContract $account): EnvironmentSwitchContract;

    /**
     * Switching to system environment.
     * 
     * @return static
     */
    public function toSystemEnvironment(): EnvironmentSwitchContract;

    /**
     * Calling given callback in system environment.
     * 
     * Afterwards it goes back to previous environment.
     * 
     * @param callable $callback Callback to execute in system environment.
     * @return mixed  Returning callback response.
     */
    public function callInSystemEnvironment(callable $callback);

    /**
     * Calling given callback in given account environment.
     * 
     * Afterwards it goes back to previous environment.
     * 
     * @param AccountContract Switching environment to given account.
     * @param callable $callback Callback to execute in account environment.
     * @return mixed  Returning callback response.
     */
    public function callInAccountEnvironment(AccountContract $account, callable $callback);
}