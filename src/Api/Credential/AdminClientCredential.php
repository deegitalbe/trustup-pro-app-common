<?php
namespace Deegitalbe\TrustupProAppCommon\Api\Credential;

use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Henrotaym\LaravelApiClient\Contracts\RequestContract;
use Henrotaym\LaravelApiClient\Contracts\CredentialContract;
use Deegitalbe\ServerAuthorization\Credential\AuthorizedServerCredential;

class AdminClientCredential extends AuthorizedServerCredential
{
    public function prepare(RequestContract &$request)
    {
        parent::prepare($request);
        $request->setBaseUrl(Package::adminUrl());
    }
}