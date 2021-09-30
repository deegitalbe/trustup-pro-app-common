<?php
namespace Deegitalbe\TrustupProAppCommon\Http\Middleware;

use Closure;

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
        $account = config('trustup_pro_app_common.account_model')::where('uuid', $request->route()->parameter('account'))
            ->firstOrFail();

        return $next($request->merge(['account' => $account]));
    }
}