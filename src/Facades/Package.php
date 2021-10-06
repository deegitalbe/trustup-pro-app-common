<?php
namespace Deegitalbe\TrustupProAppCommon\Facades;

use Illuminate\Support\Facades\Facade;

class Package extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'trustup_pro_app_common';
    }
}