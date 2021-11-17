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
    public function storeAccount(AccountCreated $event)
    {
        app()->make(WebsiteRepository::class)
            ->create($event->newAccount());
    }
}
