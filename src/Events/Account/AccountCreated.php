<?php

namespace Deegitalbe\TrustupProAppCommon\Events\Account;

use Deegitalbe\TrustupProAppCommon\Events\ProjectorEvent;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;

/**
 * Event when account is created.
 */
class AccountCreated extends ProjectorEvent
{
    /**
     * Attributes to create account with.
     * 
     * @return array
     */
    public $attributes;

    /**
     * Instanciating class.
     * 
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Instanciating a new account.
     * 
     * @return mixed
     */
    public function newAccount()
    {
        return app()->make(AccountContract::class, ['attributes' => $this->attributes]);
    }
}