<?php

namespace Deegitalbe\TrustupProAppCommon\Projectors\Account;

use Deegitalbe\TrustupProAppCommon\Projectors\Projector;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Events\Account\AccountCreated;
use Deegitalbe\TrustupProAppCommon\Events\Account\AccountSubscribed;
use Deegitalbe\TrustupProAppCommon\Contracts\Query\AccountQueryContract;

/**
 * Projector handling account related events.
 */
class AccountProjector extends Projector
{
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
        $account = app(AccountQueryContract::class)->whereUuid($event->account_uuid)->first();
        
        if (!$account):
            return;
        endif;

        $account->chargebee_subscription_id = $event->subscription_id;
        $account->chargebee_subscription_status = $event->subscription_status;
        $account->save();
    }
}
