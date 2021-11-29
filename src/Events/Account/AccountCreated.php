<?php

namespace Deegitalbe\TrustupProAppCommon\Events\Account;

use Deegitalbe\TrustupProAppCommon\Events\ProjectorEvent;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Events\Traits\HavingAttributes;
use Deegitalbe\TrustupProAppCommon\Contracts\Events\HavingAttributesContract;

/**
 * Event when account is created.
 */
class AccountCreated extends ProjectorEvent implements HavingAttributesContract
{
    use HavingAttributes;

    /**
     * Instanciating a new account.
     * 
     * @return mixed
     */
    public function newAccount()
    {
        return app()->make(AccountContract::class, ['attributes' => $this->getAttributes()]);
    }
}