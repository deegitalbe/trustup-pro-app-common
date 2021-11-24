<?php
namespace Deegitalbe\TrustupProAppCommon\Exceptions\AdminAppApi;

use Henrotaym\LaravelApiClient\Exceptions\RequestRelatedException;

/**
 * Representing a failed request getting apps from admin.
 */
class GetCurrentAppException extends RequestRelatedException {

    /**
     * Exception message
     * 
     * @var string
     */
    protected $message = "Request getting current app failed.";
    
}