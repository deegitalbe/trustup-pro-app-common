<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts\Auth;

use Illuminate\Contracts\Auth\UserProvider;
use Deegitalbe\TrustupProAppCommon\Contracts\UserContract;

interface TokenProviderContract extends UserProvider
{
    /**
     * Getting user from incoming request expected header.
     * 
     * @return UserContract
     */
    public function getUser(): ?UserContract;

    /**
     * Retrieving professional super admin by its authorization key.
     *
     * @param string $authorizationKey Professional authorization key
     * @return UserContract|null
     */
    public function retrieveByProfessionalAuthorizationKey(string $authorizationKey): ?UserContract;
}