<?php
namespace Deegitalbe\TrustupProAppCommon\Exceptions\Webhooks;

use Exception;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;

/**
 * Representing exception when request updating account fails.
 */
class AccountUpdateFailed extends Exception
{
    /**
     * Exception message.
     * 
     * @var string
     */
    protected $message = "Account update using webhook failed.";

    /**
     * Parameters sent by webhook.
     * 
     * @var array
     */
    protected $attributes;
    
    /**
     * Account concerned by webhook.
     * 
     * @var AccountContract
     */
    protected $account;

    public function setAccount(AccountContract $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function context()
    {
        return [
            'attributes' => $this->attributes,
            'account' => $this->account
        ];
    }
}