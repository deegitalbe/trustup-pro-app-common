<?php
namespace Deegitalbe\TrustupProAppCommon\Models;

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
        return config('account_synchronizer.app_key');
    }

    /**
     * Professional authorization_key linked to account.
     * @return string
     */
    public function getAuthorizationKey(): string
    {
        return $this->authorization_key;
    }
}