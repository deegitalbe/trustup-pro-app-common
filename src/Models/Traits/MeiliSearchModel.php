<?php
namespace Deegitalbe\TrustupProAppCommon\Models\Traits;

use Laravel\Scout\Searchable;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Contracts\AuthenticationRelatedContract;

/** Used for meilisearch models related to specific professional. */
trait MeiliSearchModel
{
    use Searchable;
    
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

        return join('_', [
            $authentication->getAccount()->getAuthorizationKey(),
            Package::appKey(),
            $this->getTable()
        ]);
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

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->toMeiliSearchModel();
    }

}