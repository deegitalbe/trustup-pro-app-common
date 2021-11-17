<?php
namespace Deegitalbe\TrustupProAppCommon\Facades;

use Illuminate\Support\Facades\Facade;
use Deegitalbe\TrustupProAppCommon\Package as UnderlyingPackage;

/**
 * Trustup pro app common package facade.
 */
class Package extends Facade
{
    public static function getFacadeAccessor()
    {
        return UnderlyingPackage::$prefix;
    }
}