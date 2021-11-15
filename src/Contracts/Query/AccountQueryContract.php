<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts\Query;

use Illuminate\Database\Eloquent\Collection;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;

/**
 * Representing queries that are available for account model.
 */
interface AccountQueryContract
{
    /**
     * Restricting account to those having given authorization key.
     * 
     * @param string $authorization_key
     * @return AccountQueryContract
     */
    public function whereAuthorizationKey(string $authorization_key): AccountQueryContract;

    /**
     * Restricting accounts to those having given uuid.
     * 
     * @param string $uuid
     * @return AccountQueryContract
     */
    public function whereUuid(string $uuid): AccountQueryContract;

    /**
     * Getting first account matching query.
     * 
     * @return AccountContract|null
     */
    public function first(): ?AccountContract;

    /**
     * Getting accounts matching query.
     * 
     * @return Collection
     */
    public function get(): Collection;


    /**
     * Getting number of accounts matching query.
     * 
     * @return int
     */
    public function count(): int;
}