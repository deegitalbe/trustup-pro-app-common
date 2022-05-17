<?php
namespace Deegitalbe\TrustupProAppCommon\Models\Traits;

use Deegitalbe\TrustupProAppCommon\Contracts\Service\EnvironmentSwitchContract;
use Laravel\Scout\Searchable;
use Deegitalbe\TrustupProAppCommon\Facades\Package;

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
        return join('_', [
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
        return "uuid";
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
        return array_keys($this->getMeilisearchAllModelAttributes());
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
     * Defining all meiliSearch model attributes.
     * 
     * These attributes would be stored to meilisearch server.
     *
     * @return array
     */
    public function getMeilisearchAllModelAttributes(): array
    {
        /** @var EnvironmentSwitchContract */
        $switch  = app()->make(EnvironmentSwitchContract::class);

        return array_merge($this->toMeiliSearchModel(), [
            'authorization_key' => $switch->getCurrentEnvironment()->getAuthorizationKey()
        ]);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->getMeilisearchAllModelAttributes();
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

    /**
     * Preventing meilisearch update until callback is done.
     *
     * @param callable $callback Callback performing model updates.
     * @return void
     */
    public static function muteMeiliSearchUntil(callable $callback)
    {
        static::withoutSyncingToSearch($callback);
    }

}