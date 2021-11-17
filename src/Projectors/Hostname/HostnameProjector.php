<?php

namespace Deegitalbe\TrustupProAppCommon\Projectors\Hostname;

use Deegitalbe\TrustupProAppCommon\Projectors\Projector;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Deegitalbe\TrustupProAppCommon\Events\Hostname\HostnameCreated;

/**
 * Projector handling hostname related events.
 */
class HostnameProjector extends Projector
{
    public function storeHostname(HostnameCreated $event)
    {
        $repository = app()->make(HostnameRepository::class);
        $hostname = $event->newHostname();
        $repository->attach(
            $repository->create($hostname), 
            $event->getAccount()
        );
    }
}
