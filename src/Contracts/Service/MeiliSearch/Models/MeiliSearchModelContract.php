<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts\Service\MeiliSearch\Models;

/**
 * Representing a model that can be stored on Meilisearch DB.
 */
interface MeiliSearchModelContract
{
    /**
     * Defining meiliSearch searchable attributes.
     * 
     * Order is important since search would be performed respecting given order.
     *
     * @return array
     */
    public function getMeiliSearchSearchableAttributes(): array;

    /**
     * Defining meiliSearch filterable attributes.
     * 
     * These fields can be used as filter to do custom queries.
     *
     * @return array
     */
    public function getMeilisearchFilterableAttributes(): array;

    /**
     * Transforming model to meilisearch entity.
     * 
     * It should be associative array. In default configuration order DO matter for future search.
     *
     * @return array
     */
    public function toMeiliSearchModel(): array;

    /**
     * Getting meiliSearch model unique identifier.
     * 
     * Identifier can be considered as MYSQL row ID.
     *
     * @return mixed
     */
    public function getMeiliSearchIndexPrimaryKey(): string;

    /**
     * Getting MeiliSearch model index.
     * 
     * Index can be considered as mySQL table name.
     *
     * @return string
     */
    public function getMeiliSearchIndexName(): string;

    /**
     * Preventing meilisearch update until callback is done.
     *
     * @param callable $callback Callback performing model updates.
     * @return void
     */
    public static function muteMeiliSearchUntli(callable $callback);
}