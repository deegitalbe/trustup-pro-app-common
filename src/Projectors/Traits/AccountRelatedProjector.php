<?php
namespace Deegitalbe\TrustupProAppCommon\Projectors\Traits;

use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Query\AccountQueryContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Events\AccountRelatedEventContract;

trait AccountRelatedProjector
{
    /**
     * Getting account linked to given event.
     * 
     * @param AccountRelatedEventContract $event
     * @return AccountContract
     */
    public function getAccount(AccountRelatedEventContract $event): AccountContract
    {
        return app()->make(AccountQueryContract::class)
            ->whereUuid($event->getAccountUuid())
            ->first();
    }
}