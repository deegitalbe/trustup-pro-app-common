<?php
namespace Deegitalbe\TrustupProAppCommon\Http\Middleware;

use Closure;
use Deegitalbe\TrustupProAppCommon\Facades\Package;

/**
 * Middleware setting up account to request.
 */
class AccountRelated
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
        $account = Package::account()::where('uuid', $request->route()->parameter('account'))
            ->firstOrFail();

        return $next($request->merge(['account' => $account]));
    }
}