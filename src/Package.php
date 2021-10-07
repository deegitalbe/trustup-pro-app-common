<?php
namespace Deegitalbe\TrustupProAppCommon;

use Deegitalbe\TrustupProAppCommon\Exceptions\Config\NoAuthorizationKeyException;

class Package
{
    /**
     * Account model className.
     * 
     * @return string
     */
    public function account(): string
    {
        return config('trustup_pro_app_common.account_model');
    }

    /**
     * Server authorization_key.
     * 
     * @return string
     */
    public function serverAuthorizationKey(): string
    {
        if (!$key = config('trustup_pro_app_common.server_authorization_key')):
            report(new NoAuthorizationKeyException);
        endif;
        
        return $key ?? '';
    }
}