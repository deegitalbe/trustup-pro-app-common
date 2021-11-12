<?php
namespace Deegitalbe\TrustupProAppCommon\Exceptions\TrustupProApi;

use Exception;
use Deegitalbe\TrustupProAppCommon\Facades\Package;

class GetExpectedAccountFailed extends Exception {
    /**
     * Exception message.
     * 
     * @var string
     */
    protected $message = "Getting expected account from request failed.";

    /**
     * Exception context.
     * 
     * @return array
     */
    public function context()
    {
        return [
            'expected_account_uuid' => request()->header(Package::requestedAccountHeader())
        ];
    }
}