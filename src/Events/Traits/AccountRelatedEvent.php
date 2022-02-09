<?php
namespace Deegitalbe\TrustupProAppCommon\Events\Traits;

use Deegitalbe\TrustupProAppCommon\Contracts\Events\AccountRelatedEventContract;

trait AccountRelatedEvent
{
    /**
     * Account uuid linked to event.
     * 
     * @var string
     */
    public $account_uuid;
    
    /**
     * Getting account uuid linked to this event.
     */
    public function getAccountUuid(): string
    {
        return $this->account_uuid;
    }

    /**
     * Setting up account uuid.
     * 
     * @param string $account_uuid
     * @return static
     */
    public function setAccountUuid(string $account_uuid): AccountRelatedEventContract
    {
        $this->account_uuid = $account_uuid;

        return $this;
    }
}