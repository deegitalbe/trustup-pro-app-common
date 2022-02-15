<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts\Service\MeiliSearch;

use Deegitalbe\TrustupProAppCommon\Contracts\Service\MeiliSearch\Models\MeiliSearchModelContract;

/**
 * Service related to MeiliSearch indexes.
 */
interface MeiliSearchIndexServiceContract
{
    /**
     * Storing meiliSearch index for specified model.
     * 
     * @param MeiliSearchModelContract $model Model to create index for.
     * @return bool Success state.
     */
    public function store(MeiliSearchModelContract $model): bool;
}