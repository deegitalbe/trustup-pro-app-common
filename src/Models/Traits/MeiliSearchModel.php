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
     * Defining meiliSearch searchable attributes.
     * 
     * Order is important since search would be performed respecting given order.
     *
     * @return array
     */
    public function getMeiliSearchSearchableAttributes(): array
    {
        return array_keys($this->toMeiliSearchModel());
    }

    /**
     * Defining meiliSearch filterable attributes.
     * 
     * These fields can be used as filter to do custom queries.
     *
     * @return array
     */
    public function getMeilisearchFilterableAttributes(): array
    {
        return $this->getMeiliSearchSearchableAttributes();
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

    /**
     * Get the value used to index the model.
     *
     * @return mixed
     */
    public function getScoutKey()
    {
        return $this->{$this->getScoutKeyName()};
    }
 
    /**
     * Get the key name used to index the model.
     *
     * @return mixed
     */
    public function getScoutKeyName()
    {
        return $this->getMeiliSearchIndexPrimaryKey();
    }

}