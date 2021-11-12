<?php
namespace Deegitalbe\TrustupProAppCommon\Models;

use Illuminate\Support\Carbon;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Models\SynchronizeWhenSaved;

/**
 * AccountContract implementation + account saved event watcher (SynchronizeWhenSaved).
 */
trait Synchronizable
{
    use SynchronizeWhenSaved;
    
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

    /**
     * Getting first account matching given uuid.
     * 
     * @param string $uuid
     * @return AccountContract|null Null if no account found.
     */
    public static function firstMatchingUuid(string $uuid): ?AccountContract
    {
        return self::query()
            ->where('uuid', $uuid)
            ->first();
    }
}