<?php
namespace Deegitalbe\TrustupProAppCommon\Api;

use Deegitalbe\TrustupProAppCommon\Models\User;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Models\Professional;
use Henrotaym\LaravelApiClient\Contracts\RequestContract;
use Deegitalbe\TrustupProAppCommon\Contracts\UserContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Contracts\ProfessionalContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\TrustupProApiContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Query\AccountQueryContract;
use Deegitalbe\TrustupProAppCommon\Exceptions\TrustupProApi\GetUserFailed;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\Client\TrustupProClientContract;
use Deegitalbe\TrustupProAppCommon\Exceptions\TrustupProApi\GetExpectedAccountFailed;
use Deegitalbe\TrustupProAppCommon\Exceptions\TrustupProApi\UserNotHavingAccessToAccount;

/**
 * Representing actions that are available with trustup.pro
 */
class TrustupProApi implements TrustupProApiContract
{
    /**
     * Underlying client.
     * 
     * @var TrustupProClientContract
     */
    protected $client;

    /**
     * Constructing class.
     * 
     * @param TrustupProClientContract $client
     * @return void
     */
    public function __construct(TrustupProClientContract $client)
    {
        $this->client = $client;
    }

    /**
     * Getting authenticated user linked to current request.
     * 
     * @return UserContract|null
     */
    public function getUser(): ?array
    {
        $request = app()->make(RequestContract::class)
            ->setVerb('GET')
            ->setUrl('api/user');
        
        $response = $this->client->try($request, new GetUserFailed);

        if ($response->failed()):
            report($response->error());
            return null;
        endif;

        return $response->response()->get(true)['user'];
    }
}