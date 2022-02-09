<?php
namespace Deegitalbe\TrustupProAppCommon\Facades;

use Deegitalbe\TrustupProAppCommon\Package as Underlying;
use Henrotaym\LaravelPackageVersioning\Facades\Abstracts\VersionablePackageFacade;

/**
 * Trustup pro app common package facade.
 */
class Package extends VersionablePackageFacade
{
    public static function getPackageClass(): string
    {
        return Underlying::class;
    }
}