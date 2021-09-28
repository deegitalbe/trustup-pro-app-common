<?php
namespace Deegitalbe\TrustupProAppCommon\Exceptions\Synchronizer;

use Exception;
use Henrotaym\LaravelApiClient\Contracts\RequestContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;

class FailedRequest extends Exception
{
    /**
     * Exception message.
     * 
     * @var string
     */
    protected $message = "Synchronization failed.";

    /**
     * Error message that administration sent back.
     * 
     * @var string
     */
    protected $error_message;
    /**
     * Account concerned by failure.
     * 
     * @var AccountContract
     */
    protected $account;

    /**
     * Request that failed.
     * 
     * @var RequestContract
     */
    protected $request;

    public function setRequest(RequestContract $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function setErrorMessage($error_message): self
    {
        $this->error_message = $error_message;

        return $this;
    }

    public function setAccount(AccountContract $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function context()
    {
        return [
            'request' => $this->request->toArray(),
            'error_message' => $this->error_message,
            'account' => $this->account->getUuid()
        ];
    }
}