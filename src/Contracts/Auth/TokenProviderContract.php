<?php
namespace Deegitalbe\TrustupProAppCommon\Auth;

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
}