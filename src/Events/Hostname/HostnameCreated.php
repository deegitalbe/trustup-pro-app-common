<?php

namespace Deegitalbe\TrustupProAppCommon\Events\Hostname;

use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Events\ProjectorEvent;
use Deegitalbe\TrustupProAppCommon\Contracts\Query\AccountQueryContract;

/**
 * Event when hostname is created.
 */
class HostnameCreated extends ProjectorEvent
{
    /**
     * Related account uuid.
     * 
     * @return string
     */
    public $account_uuid;

    /**
     * Hostname attributes to create with.
     * 
     * @return array
     */
    public $attributes;

    /**
     * Constructing class.
     * 
     * @param array $attributes
     * @param string $account_uuid
     */
    public function __construct(array $attributes, string $account_uuid)
    {
        $this->account_uuid = $account_uuid;
        $this->attributes = $attributes;
    }

    /**
     * Retrieving related account.
     * 
     * @return mixed
     */
    public function getAccount()
    {
        return app()->make(AccountQueryContract::class)
            ->whereUuid($this->account_uuid)
            ->first();
    }

    /**
     * Instanciating a new hostname.
     * 
     * @return mixed
     */
    public function newHostname()
    {
        return app()->make(Package::hostname(), ['attributes' => $this->attributes]);
    }
}