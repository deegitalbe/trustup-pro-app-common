<?php
namespace Deegitalbe\TrustupProAppCommon\Models;

use Illuminate\Support\Carbon;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Models\SynchronizeWhenSaved;
use Deegitalbe\TrustupProAppCommon\Contracts\Query\AccountQueryContract;

/**
 * AccountContract implementation + account saved event watcher (SynchronizeWhenSaved).
 */
trait Synchronizable
{
    use SynchronizeWhenSaved;

    /**
     * Account database id.
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * Account uuid that should be used to retrieve account details.
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }
    
    /**
     * Application key linked to account.
     * @return string
     */
    public function getAppKey(): string
    {
        return Package::appKey();
    }

    /**
     * Professional authorization_key linked to account.
     * @return string
     */
    public function getAuthorizationKey(): string
    {
        return $this->authorization_key;
    }

    /**
     * Subscription id linked to account.
     * @return string|null
     */
    public function getSubscriptionId(): ?string
    {
        return $this->chargebee_subscription_id;
    }

    /**
     * Subscription status linked to account.
     * @return string|null
     */
    public function getSubscriptionStatus(): ?string
    {
        return $this->chargebee_subscription_status;
    }

    /**
     * Account creation date.
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->created_at ?? now();
    }
}