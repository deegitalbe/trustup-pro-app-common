<?php
namespace Deegitalbe\TrustupProAppCommon;

use Henrotaym\LaravelApiClient\Contracts\RequestContract;
use Henrotaym\LaravelApiClient\Contracts\CredentialContract;

class ClientCredential implements CredentialContract
{
    public function prepare(RequestContract &$request)
    {
        $request->setBaseUrl(config('trustup_pro_app_common.admin_url'));
    }
}