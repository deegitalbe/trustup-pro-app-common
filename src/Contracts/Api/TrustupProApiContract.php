<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts\Api;

use Deegitalbe\TrustupProAppCommon\Contracts\UserContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;

/**
 * Representing actions that are available with trustup.pro
 */
interface TrustupProApiContract
{
    /**
     * Getting authenticated user linked current request.
     * 
     * @return UserContract|null
     */
    public function getUser(): ?UserContract;
}