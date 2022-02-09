<?php

namespace Deegitalbe\TrustupProAppCommon\Events\Account;

use Deegitalbe\TrustupProAppCommon\Events\ProjectorEvent;
use Deegitalbe\TrustupProAppCommon\Events\Traits\HavingAttributes;
use Deegitalbe\TrustupProAppCommon\Events\Traits\AccountRelatedEvent;
use Deegitalbe\TrustupProAppCommon\Contracts\Events\AccountUpdateEventContract;

/**
 * Event when account is getting updated from admin.trustup.pro.
 */
class AccountUpdatedFromWebhook extends ProjectorEvent implements AccountUpdateEventContract
{
    use AccountRelatedEvent, HavingAttributes;
}