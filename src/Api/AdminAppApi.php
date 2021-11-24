<?php
namespace Deegitalbe\TrustupProAppCommon\Api;

use Illuminate\Support\Collection;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Contracts\AppContract;
use Henrotaym\LaravelApiClient\Contracts\RequestContract;
use Henrotaym\LaravelApiClient\Contracts\ResponseContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\AdminAppApiContract;
use Deegitalbe\TrustupProAppCommon\Exceptions\AdminAppApi\GetAppsException;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\Client\AdminClientContract;
use Deegitalbe\TrustupProAppCommon\Exceptions\AdminAppApi\GetCurrentAppException;

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

        return $this->toApps($response);
    }

    /**
     * Getting apps available in administration that are not dashboard related.
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

        return $this->toApps($response);
    }

    /**
     * Getting current app.
     * 
     * @return AppContract|null null if not found.
     */
    public function getCurrentApp(): ?AppContract
    {
        $request = app()->make(RequestContract::class)
            ->setVerb('GET')
            ->setUrl("api/apps")
            ->addQuery(['is' => Package::appKey()]);

        $response = $this->client->try($request, new GetCurrentAppException);

        if ($response->failed()):
            report($response->error());
            return null;
        endif;

        return $this->toApps($response->response())->first();
    }

    /**
     * Transforming raw apps to app models
     * 
     * @param array[array] $raw_apps
     * @return Collection[AppContract]
     */
    protected function toApps(ResponseContract $response): Collection
    {
        return collect($response->get(true)['data'])->map(function(array $raw_app) {
            return app()->make(AppContract::class)->setAttributes($raw_app);
        });
    }
}