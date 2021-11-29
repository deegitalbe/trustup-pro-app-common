<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts\Projectors;

use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Events\AccountRelatedEventContract;

/**
 * Representing a projector linked to an account.
 */
interface AccountRelatedProjectorContract
{
    /**
     * Getting account linked to given event.
     */
    public function getAccount(AccountRelatedEventContract $event): AccountContract;
}