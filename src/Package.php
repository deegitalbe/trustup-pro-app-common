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
     * Admin url.
     * 
     * @return string
     */
    public function adminUrl(): string
    {
        return config('trustup_pro_app_common.admin_url');
    }
}