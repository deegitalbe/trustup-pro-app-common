<?php
namespace Deegitalbe\TrustupProAppCommon;

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
        return config('trustup_pro_app_common.server_authorization_key');
    }
}