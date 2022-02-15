<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts;

use stdClass;
use Deegitalbe\TrustupProAppCommon\Contracts\AppContract;
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
     * Getting current app for current request.
     * 
     * @return AppContract|null
     */
    public function getCurrentApp(): ?AppContract;

    /**
     * Setting account for current request (should not be called manually).
     * 
     * @param AccountContract|null $account
     * @return static
     */
    public function setAccount(?AccountContract $account): AuthenticationRelatedContract;
}