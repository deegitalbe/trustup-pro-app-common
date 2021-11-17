<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts;

use Deegitalbe\TrustupProAppCommon\Contracts\UserContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\TrustupProApiContract;

/**
 * Useful information concerning current authentication environment.
 */
interface AuthenticationRelatedContract
{
    /**
     * Getting authenticated user.
     * 
     * @return UserContract|null
     */
    public function getUser(): ?UserContract;


    /**
     * Getting account for current request.
     * 
     * @return AccountContract|null
     */
    public function getAccount(): ?AccountContract;

    /**
     * Setting account for current request (should not be called manually).
     * 
     * @param AccountContract $account
     * @return AuthenticationRelatedContract
     */
    public function setAccount(AccountContract $account): AuthenticationRelatedContract;
}