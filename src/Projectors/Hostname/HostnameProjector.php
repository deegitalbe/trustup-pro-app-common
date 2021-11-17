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
    public function createHostname(HostnameCreated $event)
    {
        $repository = app()->make(HostnameRepository::class);
        $repository->attach(
            $repository->create($event->newHostname()), 
            $event->getAccount()
        );
    }
}
