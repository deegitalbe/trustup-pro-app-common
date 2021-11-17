<?php

namespace Deegitalbe\TrustupProAppCommon\Projectors\Account;

use Deegitalbe\TrustupProAppCommon\Projectors\Projector;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Deegitalbe\TrustupProAppCommon\Events\Account\AccountCreated;

/**
 * Projector handling account related events.
 */
class AccountProjector extends Projector
{
    /**
     * Storing account from event.
     * 
     * @param AccountCreated
     * @return void
     */
    public function storeAccount(AccountCreated $event)
    {
        $account = $event->newAccount();
        app()->make(WebsiteRepository::class)
            ->create($account);
    }
}
