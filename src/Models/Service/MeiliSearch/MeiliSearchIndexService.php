<?php
namespace Deegitalbe\TrustupProAppCommon\Models\Service\MeiliSearch;

use Deegitalbe\TrustupProAppCommon\Contracts\Service\MeiliSearch\MeiliSearchIndexServiceContract;
use MeiliSearch\Client as MeiliSearchClient;
use Deegitalbe\TrustupProAppCommon\Contracts\Service\MeiliSearch\Models\MeiliSearchModelContract;

/**
 * Service related to MeiliSearch indexes.
 */
class MeiliSearchIndexService implements MeiliSearchIndexServiceContract
{
    /**
     * Underlying MeiliSearch client.
     * @var MeiliSearchClient $client
     */
    protected $client;

    /**
     * Instanciating dependencies.
     * 
     * @param MeiliSearchClient $client
     * @return void
     */
    public function __construct(MeiliSearchClient $client)
    {
        $this->client = $client;
    }

    /**
     * Storing meiliSearch index for specified model.
     * 
     * @param MeiliSearchModelContract $model Model to create index for.
     * @return bool Success state.
     */
    public function store(MeiliSearchModelContract $model): bool
    {
        // Creating index respecting primary key.
        $this->client->createIndex($model->getMeiliSearchIndexName(), ['primaryKey' => $model->getMeiliSearchIndexPrimaryKey()]);
        // Defining index searchable attributes (order matters).
        $this->client->index($model->getMeiliSearchIndexName())
            ->updateSearchableAttributes($model->getMeiliSearchSearchableAttributes());
        // Defining index filterable attributes
        $this->client->index($model->getMeiliSearchIndexName())
            ->updateFilterableAttributes($model->getMeilisearchFilterableAttributes());

        return true;
    }
}