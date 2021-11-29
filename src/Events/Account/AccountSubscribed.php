<?php

namespace Deegitalbe\TrustupProAppCommon\Events\Account;

use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Events\ProjectorEvent;
use Deegitalbe\ChargebeeClient\Chargebee\Models\Subscription;
use Deegitalbe\TrustupProAppCommon\Events\Traits\HavingAttributes;
use Deegitalbe\TrustupProAppCommon\Events\Traits\AccountRelatedEvent;
use Deegitalbe\TrustupProAppCommon\Contracts\Events\HavingAttributesContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Events\AccountRelatedEventContract;

/**
 * Event when account is getting a new subscription.
 */
class AccountSubscribed extends ProjectorEvent implements AccountRelatedEventContract, HavingAttributesContract
{
    use AccountRelatedEvent, HavingAttributes;
}