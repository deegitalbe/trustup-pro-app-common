<?php
namespace Deegitalbe\TrustupProAppCommon\Exceptions\Config;

use Exception;

class NoAuthorizationKeyException extends Exception
{
    protected $message = "No authorization key. Define it in config or in your env as TRUSTUP_SERVER_AUTHORIZATION.";
}