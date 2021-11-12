<?php

namespace App\Http\Middleware;

use Closure;
use Hyn\Tenancy\Environment;
use App\Http\AccountRequestManager;
use Deegitalbe\TrustupProAppCommon\Facades\Package;

class SettingAccountAsEnvironment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Package::environment()->tenant($request->requested_account);
        Package::environment()->hostname($request->requested_account->hostnames()->first());

        return $next($request);
    }
}