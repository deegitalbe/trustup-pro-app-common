<?php
namespace Deegitalbe\TrustupProAppCommon;

use Illuminate\Support\Facades\Log;
use Henrotaym\LaravelApiClient\Contracts\ClientContract;
use Henrotaym\LaravelApiClient\Contracts\RequestContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Contracts\SynchronizerContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AdminClientContract;
use Deegitalbe\TrustupProAppCommon\Exceptions\Synchronizer\FailedRequest;

/**
 * Responsible to update/delete/create synchronized accounts.
 */
class Synchronizer implements SynchronizerContract
{
    /**
     * Client used to interact with synchronized database.
     * 
     * @var ClientContract
     */
    protected $client;

    public function __construct(AdminClientContract $client)
    {
        $this->client = $client;
    }

    /**
     * Creating account in database.
     * 
     * @param AccountContract $account Account that should be created.
     */
    public function create(AccountContract $account): self
    {
        $request = app()->make(RequestContract::class)
            ->setUrl("webhooks/professionals/{$account->getAuthorizationKey()}/accounts/{$account->getAppKey()}")
            ->setVerb("POST")
            ->addData([
                'uuid' => $account->getUuid()
            ]);
        
        $response = $this->client->start($request);
        if (!$response->ok()):
            report(
                (new FailedRequest)
                    ->setAccount($account)
                    ->setRequest($request)
                    ->setResponse($response)
            );
        endif;

        return $this;
    }

    /**
     * Updating account in database.
     * 
     * @param AccountContract $account Account that should be updated.
     */
    public function update(AccountContract $account): self
    {
        $request = app()->make(RequestContract::class)
            ->setUrl("webhooks/professionals/{$account->getAuthorizationKey()}/accounts/{$account->getAppKey()}/{$account->getUuid()}")
            ->setVerb("PUT");
        
        $response = $this->client->start($request);
        if (!$response->ok()):
            report(
                (new FailedRequest)
                    ->setAccount($account)
                    ->setRequest($request)
                    ->setResponse($response)
            );
        endif;

        return $this;
    }

    /**
     * Deleting account from database.
     * 
     * @param AccountContract $account Account that should be deleted.
     */
    public function delete(AccountContract $account): self
    {
        $request = app()->make(RequestContract::class)
            ->setUrl("webhooks/professionals/{$account->getAuthorizationKey()}/accounts/{$account->getAppKey()}/{$account->getUuid()}")
            ->setVerb("DELETE");
        
        $response = $this->client->start($request);
        if (!$response->ok()):
            report(
                (new FailedRequest)
                    ->setAccount($account)
                    ->setRequest($request)
                    ->setResponse($response)
            );
        endif;

        return $this;
    }
}