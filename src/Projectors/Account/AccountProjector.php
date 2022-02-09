<?php

namespace Deegitalbe\TrustupProAppCommon\Projectors\Account;

use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Projectors\Projector;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Deegitalbe\TrustupProAppCommon\Models\SynchronizeWhenSaved;
use Deegitalbe\TrustupProAppCommon\Events\Account\AccountCreated;
use Deegitalbe\TrustupProAppCommon\Events\Account\AccountSubscribed;
use Deegitalbe\TrustupProAppCommon\Events\Account\AccountUpdatedFromWebhook;
use Deegitalbe\TrustupProAppCommon\Projectors\Traits\AccountRelatedProjector;

/**
 * Projector handling account related events.
 */
class AccountProjector extends Projector
{
    use AccountRelatedProjector;

    /**
     * Storing account given by trustup.pro.
     * 
     * @param AccountCreated $event
     * @return void
     */
    public function storeAccount(AccountCreated $event)
    {
        $account = $event->newAccount();

        $this->muteAccountEvents(function() use ($account) {
            app()->make(WebsiteRepository::class)
                ->create($account);
        });
    }

    /**
     * Linking account to subscription.
     * 
     * @param AccountSubscribed $event
     * @return void
     */
    public function subscribeAccount(AccountSubscribed $event)
    {
        $this->updateAccount($event);
    }

    /**
     * Updating account from admin webhook.
     * 
     * @param AccountUpdatedFromWebhook $event
     * @return void
     */
    public function updateAccountFromWebhook(AccountUpdatedFromWebhook $event)
    {
        $this->muteAccountEvents(function() use ($event) {
            $this->updateAccount($event);
        });
    }

    /**
     * Not triggering admin webhooks by muting account events.
     * 
     * @param callable $callback Callback to execute without account events.
     * @return mixed Callback return value.
     */
    public function muteAccountEvents(callable $callback)
    {
        return Package::account()::withoutEvents($callback);
    }
}
