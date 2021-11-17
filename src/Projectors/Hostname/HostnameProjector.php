<?php

namespace Deegitalbe\TrustupProAppCommon\Projectors\Hostname;

use Deegitalbe\TrustupProAppCommon\Projectors\Projector;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Events\Hostname\HostnameCreated;
use Deegitalbe\TrustupProAppCommon\Contracts\Query\AccountQueryContract;

/**
 * Projector handling hostname related events.
 */
class HostnameProjector extends Projector
{
    /**
     * Storing hostname from event.
     * 
     * @param HostnameCreated $event
     * @return void
     */
    public function storeHostname(HostnameCreated $event)
    {
        $repository = app()->make(HostnameRepository::class);
        $hostname = $event->newHostname();
        $repository->create($hostname);
        $account = $this->getAccount($event);

        $repository->attach(
            $hostname, 
            $account
        );
    }

    /**
     * Getting account from event.
     * 
     * @param HostnameCreated $event
     * @return AccountContract
     */
    protected function getAccount(HostnameCreated $event): AccountContract
    {
        return app()->make(AccountQueryContract::class)
            ->whereUuid($event->account_uuid)
            ->first();
    }
}
