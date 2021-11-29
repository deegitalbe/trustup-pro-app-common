<?php

namespace Deegitalbe\TrustupProAppCommon\Projectors\Account;

use Deegitalbe\TrustupProAppCommon\Projectors\Projector;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Events\Account\AccountCreated;
use Deegitalbe\TrustupProAppCommon\Events\Account\AccountSubscribed;
use Deegitalbe\TrustupProAppCommon\Contracts\Query\AccountQueryContract;
use Deegitalbe\TrustupProAppCommon\Projectors\Traits\AccountRelatedProjector;

/**
 * Projector handling account related events.
 */
class AccountProjector extends Projector
{
    use AccountRelatedProjector;

    /**
     * Storing account from event.
     * 
     * @param AccountCreated $event
     * @return void
     */
    public function storeAccount(AccountCreated $event)
    {
        $account = $event->newAccount();
        app()->make(WebsiteRepository::class)
            ->create($account);
    }

    /**
     * Linking account to subscription.
     * 
     * @param AccountSubscribed $event
     * @return void
     */
    public function subscribeAccount(AccountSubscribed $event)
    {
        if (!$account = $this->getAccount($event)):
            return;
        endif;

        $account->fill($event->getAttributes())->save();
    }
}
