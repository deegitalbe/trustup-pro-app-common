<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts;

use Illuminate\Support\Carbon;
use Hyn\Tenancy\Contracts\Hostname;
use Hyn\Tenancy\Contracts\Website;

/**
 * Representing an account that's storable.
 */
interface AccountContract extends Website
{
    /**
     * Account database id.
     * 
     * @return int
     */
    public function getId(): int;

    /**
     * Account uuid that should be used to retrieve account details.
     * 
     * @return string
     */
    public function getUuid(): string;
    
    /**
     * Application key linked to account.
     * 
     * @return string
     */
    public function getAppKey(): string;

    /**
     * Professional authorization_key linked to account.
     * 
     * @return string
     */
    public function getAuthorizationKey(): string;

    /**
     * Subscription id linked to account.
     * 
     * @return string|null
     */
    public function getSubscriptionId(): ?string;

    /**
     * Subscription status linked to account.
     * 
     * @return string|null
     */
    public function getSubscriptionStatus(): ?string;

    /**
     * Default account hostname.
     * 
     * @return Hostname|null
     */
    public function getDefaultHostname(): ?Hostname;

    /**
     * Account creation date.
     * 
     * @return Carbon
     */
    public function getCreatedAt(): Carbon;
}