<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts\Api;

use Illuminate\Support\Collection;

/**
 * Representing what is possible to do with admin app API.
 */
interface AdminAppApiContract
{
    /**
     * Getting apps available in administration.
     * 
     * @return Collection|null null if any error.
     */
    public function getApps(): ?Collection;

    /**
     * Getting apps available in administration that are not dashboard.
     * 
     * @return Collection|null null if any error.
     */
    public function getAppsExceptDashboard(): ?Collection
}