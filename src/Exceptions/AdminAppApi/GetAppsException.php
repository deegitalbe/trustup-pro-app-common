<?php
namespace Deegitalbe\TrustupProAppCommon\Exceptions\AdminAppApi;

use Henrotaym\LaravelApiClient\Exceptions\RequestRelatedException;

class GetAppsException extends RequestRelatedException {
    protected $message = "Request getting apps from admin failed.";
}