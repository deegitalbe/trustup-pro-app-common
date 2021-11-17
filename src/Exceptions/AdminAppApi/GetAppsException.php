<?php
namespace Deegitalbe\TrustupProAppCommon\Exceptions\AdminAppApi;

use Henrotaym\LaravelApiClient\Exceptions\RequestRelatedException;

/**
 * Representing a failed request getting apps from admin.
 */
class GetAppsException extends RequestRelatedException {

    /**
     * Exception message
     * 
     * @var string
     */
    protected $message = "Request getting apps from admin failed.";
}