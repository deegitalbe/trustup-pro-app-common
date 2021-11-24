<?php

namespace Deegitalbe\TrustupProAppCommon\Events\Account;

use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Events\ProjectorEvent;
use Deegitalbe\ChargebeeClient\Chargebee\Models\Subscription;

/**
 * Event when account is getting a new subscription.
 */
class AccountSubscribed extends ProjectorEvent
{
    /**
     * Related account uuid.
     * 
     * @return string
     */
    public $account_uuid;

    /**
     * Related subscription id.
     * 
     * @return string
     */
    public $subscription_id;

    /**
     * Related subscription status.
     * 
     * @return string
     */
    public $subscription_status;

    /**
     * Constructing class.
     * 
     * @param string $subscription_id
     * @param string $subscription_status
     * @param string $account_uuid
     */
    public function __construct(string $subscription_id, string $subscription_status, string $account_uuid)
    {
        $this->subscription_id = $subscription_id;
        $this->subscription_status = $subscription_status;
        $this->account_uuid = $account_uuid;
    }
}