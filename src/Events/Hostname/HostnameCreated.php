<?php

namespace Deegitalbe\TrustupProAppCommon\Events\Hostname;

use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Events\ProjectorEvent;
use Deegitalbe\TrustupProAppCommon\Events\Traits\HavingAttributes;
use Deegitalbe\TrustupProAppCommon\Events\Traits\AccountRelatedEvent;
use Deegitalbe\TrustupProAppCommon\Contracts\Events\HavingAttributesContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Events\AccountRelatedEventContract;

/**
 * Event when hostname is created.
 */
class HostnameCreated extends ProjectorEvent implements AccountRelatedEventContract, HavingAttributesContract
{
    use AccountRelatedEvent, HavingAttributes;

    /**
     * Instanciating a new hostname.
     * 
     * @return mixed
     */
    public function newHostname()
    {
        return app()->make(Package::hostname(), ['attributes' => $this->getAttributes()]);
    }
}