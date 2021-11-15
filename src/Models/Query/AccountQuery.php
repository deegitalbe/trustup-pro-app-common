<?php
namespace Deegitalbe\TrustupProAppCommon\Models\Query;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Query\AccountQueryContract;

/**
 * Representing queries that are available for account model.
 */
class AccountQuery implements AccountQueryContract
{
    /**
     * Underlying eloquent builder.
     * 
     * @var Builder
     */
    protected $query;

    /**
     * Constructing instance.
     * 
     * @param AccountContract $account
     */
    public function __construct(AccountContract $account)
    {
        $this->query = $account->newQuery();
    }

    /**
     * Restricting account to those having given authorization key.
     * 
     * @param string $authorization_key
     * @return AccountQueryContract
     */
    public function whereAuthorizationKey(string $authorization_key): AccountQueryContract
    {
        $this->query->where('authorization_key', $authorization_key);

        return $this;
    }

    /**
     * Restricting accounts to those having given uuid.
     * 
     * @param string $uuid
     * @return AccountQueryContract
     */
    public function whereUuid(string $uuid): AccountQueryContract
    {
        $this->query->where('uuid', $uuid);

        return $this;
    }

    /**
     * Getting first account matching query.
     * 
     * @return AccountContract|null
     */
    public function first(): ?AccountContract
    {
        return $this->query->first();
    }

    /**
     * Getting accounts matching query.
     * 
     * @return Collection
     */
    public function get(): Collection
    {
        return $this->query->get();
    }

    /**
     * Getting number of accounts matching query.
     * 
     * @return int
     */
    public function count(): int
    {
        return $this->query->count();
    }
}