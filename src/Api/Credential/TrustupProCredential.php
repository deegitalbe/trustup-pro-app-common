<?php
namespace Deegitalbe\TrustupProAppCommon\Api\Credential;

use Henrotaym\LaravelApiClient\JsonCredential;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Henrotaym\LaravelApiClient\Contracts\RequestContract;

/**
 * Credential preparing request to communicate with trustup.pro
 */
class TrustupProCredential extends JsonCredential
{
    /**
     * Preparing request.
     * 
     * @param RequestContract $request
     * @return void
     */
    public function prepare(RequestContract &$request)
    {
        parent::prepare($request);
        $request->setBaseUrl(Package::trustupProUrl())
            ->addQuery([
                'token' => request()->header(Package::trustupAuthorizationHeader())
            ]);
    }
}