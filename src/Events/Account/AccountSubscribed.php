<?php

namespace Deegitalbe\TrustupProAppCommon\Events\Account;

use Deegitalbe\TrustupProAppCommon\Events\ProjectorEvent;
use Deegitalbe\TrustupProAppCommon\Events\Traits\HavingAttributes;
use Deegitalbe\TrustupProAppCommon\Events\Traits\AccountRelatedEvent;
use Deegitalbe\TrustupProAppCommon\Contracts\Events\AccountUpdateEventContract;

/**
 * Event when account is getting a new subscription.
 */
class AccountSubscribed extends ProjectorEvent implements AccountUpdateEventContract
{
    use AccountRelatedEvent, HavingAttributes;
}