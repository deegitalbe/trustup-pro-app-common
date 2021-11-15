<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts;

use Illuminate\Support\Carbon;
use Deegitalbe\TrustupProAppCommon\Contracts\Query\AccountQueryContract;

/**
 * Representing an account that's storable.
 */
interface AccountContract
{
    /**
     * Account uuid that should be used to retrieve account details.
     * @return string
     */
    public function getUuid(): string;
    
    /**
     * Application key linked to account.
     * @return string
     */
    public function getAppKey(): string;

    /**
     * Professional authorization_key linked to account.
     * @return string
     */
    public function getAuthorizationKey(): string;

    /**
     * Subscription id linked to account.
     * @return string|null
     */
    public function getSubscriptionId(): ?string;

    /**
     * Subscription status linked to account.
     * @return string|null
     */
    public function getSubscriptionStatus(): ?string;

    /**
     * Account creation date.
     * @return Carbon
     */
    public function getCreatedAt(): Carbon;
}