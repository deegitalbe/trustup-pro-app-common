<?php
namespace Deegitalbe\TrustupProAppCommon\Exceptions\Synchronizer;

use Exception;
use Henrotaym\LaravelApiClient\Contracts\RequestContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Henrotaym\LaravelApiClient\Exceptions\RequestRelatedException;

/**
 * Representing a synchronizer failed request.
 */
class FailedRequest extends RequestRelatedException
{
    /**
     * Exception message.
     * 
     * @var string
     */
    protected $message = "Synchronization failed.";

    /**
     * Account concerned by failure.
     * 
     * @var AccountContract
     */
    protected $account;

    /**
     * Setting account concerned by failure.
     * 
     * @param AccountContract $account
     * @return self
     */
    public function setAccount(AccountContract $account): self
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Exception additional context.
     * 
     * @return array
     */
    public function additionalContext(): array
    {
        return ['account' => $this->account->getUuid()];
    }
}