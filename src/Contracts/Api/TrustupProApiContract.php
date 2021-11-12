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
     * @param Request $request
     * @return Account|null Null if any error occured.
     */
    public function getAccount(): ?AccountContract;
}