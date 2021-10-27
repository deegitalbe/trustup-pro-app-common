<?php
namespace Deegitalbe\TrustupProAppCommon\Api;

use Illuminate\Support\Collection;
use Henrotaym\LaravelApiClient\Contracts\RequestContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AdminClientContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\AdminAppApiContract;
use Deegitalbe\TrustupProAppCommon\Exceptions\AdminAppApi\GetAppsException;

/**
 * Representing what is possible to do with admin API.
 */
class AdminAppApi implements AdminAppApiContract
{
    /**
     * Underlying client.
     * 
     * @var AdminClientContract
     */
    protected $client;

    /**
     * Constructing class.
     * 
     * @param AdminClientContract $client
     * @return void
     */
    public function __construct(AdminClientContract $client)
    {
        $this->client = $client;
    }

    /**
     * Getting apps available in administration.
     * 
     * @return Collection|null null if any error.
     */
    public function getApps(): ?Collection
    {
        $request = app()->make(RequestContract::class)
            ->setVerb('GET')
            ->setUrl("api/apps")
            ->addQuery(['available' => true]);
        
        $response = $this->client->start($request);

        if (!$response->ok()):
            report(
                (new GetAppsException)
                    ->setResponse($response)
                    ->setRequest($request)    
            );
            return null;
        endif;

        return collect($response->get()->data);
    }

    /**
     * Getting apps available in administration.
     * 
     * @return Collection|null null if any error.
     */
    public function getAppsExceptDashboard(): ?Collection
    {
        $request = app()->make(RequestContract::class)
            ->setVerb('GET')
            ->setUrl("api/apps")
            ->addQuery([
                'available' => true,
                'not' => "dashboard"
            ]);
        
        $response = $this->client->start($request);

        if (!$response->ok()):
            report(
                (new GetAppsException)
                    ->setResponse($response)
                    ->setRequest($request)    
            );
            return null;
        endif;

        return collect($response->get()->data);
    }
}