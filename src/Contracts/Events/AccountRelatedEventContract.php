<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts\Events;

/**
 * Representing an event linked to an existing account.
 */
interface AccountRelatedEventContract
{
    /**
     * Getting account uuid linked to this event.
     */
    public function getAccountUuid(): string;

    /**
     * Setting up account uuid.
     * 
     * @param string $account_uuid
     * @return static
     */
    public function setAccountUuid(string $account_uuid): AccountRelatedEventContract;
}