<?php
namespace Deegitalbe\TrustupProAppCommon\Models\Traits;

use Deegitalbe\TrustupProAppCommon\Contracts\AuthenticationRelatedContract;
use Deegitalbe\TrustupProAppCommon\Facades\Package;

/** Used for meilisearch models related to specific professional. */
trait MeiliSearchModel
{
    /**
     * Get the name of the index associated with the model. (concerning laravel scout)
     * 
     * Since each professional should have its own index, we use authorization_key as prefix.
     *
     * @return string
     */
    public function searchableAs(): string
    {
        /** @var AuthenticationRelatedContract */
        $authentication = app()->make(AuthenticationRelatedContract::class);

        return "{$authentication->getAccount()->getAuthorizationKey()}_". Package::appKey() ."_{$this->getTable()}" ;
    }

    /**
     * Getting meiliSearch model unique identifier.
     * 
     * Identifier can be considered as MYSQL row ID.
     *
     * @return mixed
     */
    public function getMeiliSearchIndexPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * Getting MeiliSearch model index.
     * 
     * Index can be considered as mySQL table name.
     *
     * @return string
     */
    public function getMeiliSearchIndexName(): string
    {
        return $this->searchableAs();
    }
}