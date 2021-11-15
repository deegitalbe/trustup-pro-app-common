<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts\Api;

use Deegitalbe\TrustupProAppCommon\Contracts\UserContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;

interface TrustupProApiContract
{
    /**
     * Making sure current request can access given account.
     * 
     * @param AccountContract $account
     * @return bool Access success state.
     */
    public function hasAccessToAccount(AccountContract $account): bool;

    /**
     * Getting authenticated user linked current request.
     * 
     * @return UserContract|null
     */
    public function getUser(): ?UserContract;

    /**
     * Getting account linked to current request.
     * 
     * @param string|null $uuid If null, account header will be used.
     * @param UserContract|null $user If null, user will be retrieved from trustup pro.
     * @return Account|null Null if any error occured.
     */
    public function getAccount(?string $uuid = null, ?UserContract $user = null): ?AccountContract;
}