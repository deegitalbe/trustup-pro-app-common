<?php
namespace Henrotaym\AppAccountsSync\Contracts;

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
}