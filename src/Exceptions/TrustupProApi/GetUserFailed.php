<?php
namespace Deegitalbe\TrustupProAppCommon\Exceptions\TrustupProApi;

use Henrotaym\LaravelApiClient\Exceptions\RequestRelatedException;

/**
 * Representing that request trying to retrieve user from trustup.pro api failed.
 */
class GetUserFailed extends RequestRelatedException {

    /**
     * Exception message.
     * 
     * @var string
     */
    protected $message = "Request getting user from trustup.pro failed.";
}