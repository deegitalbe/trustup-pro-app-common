<?php
namespace Deegitalbe\TrustupProAppCommon\Exceptions\TrustupProApi;

use Henrotaym\LaravelApiClient\Exceptions\RequestRelatedException;

class GetUserFailed extends RequestRelatedException {
    protected $message = "Request getting user from trustup.pro failed.";
}