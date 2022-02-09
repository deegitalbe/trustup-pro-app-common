<?php
namespace Deegitalbe\TrustupProAppCommon\Projectors\Traits;

use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Query\AccountQueryContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Events\AccountRelatedEventContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Events\AccountUpdateEventContract;

trait AccountRelatedProjector
{
    /**
     * Getting account linked to given event.
     * 
     * @param AccountRelatedEventContract $event
     * @return AccountContract
     */
    public function getAccount(AccountRelatedEventContract $event): ?AccountContract
    {
        return app()->make(AccountQueryContract::class)
            ->whereUuid($event->getAccountUuid())
            ->first();
    }

    /**
     * Updating account based on given event attributes.
     * 
     * @param AccountUpdateEventContract $event Related event.
     * @return AccountContract|null Null if error.
     */
    public function updateAccount(AccountUpdateEventContract $event): ?AccountContract
    {
        if (!$account = $this->getAccount($event)):
            return null;
        endif;

        return tap($account->fill($event->getAttributes()))->save();
    }
}