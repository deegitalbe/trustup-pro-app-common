<?php
namespace Deegitalbe\TrustupProAppCommon\Http\Middleware;

use Closure;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\TrustupProApiContract;

class UserHavingAccessToAccount
{
    /**
     * Api representing actions available with trustup.pro.
     * 
     * @var TrustupProApiContract
     */
    protected $api;
    
    public function __construct(TrustupProApiContract $api)
    {
        $this->api = $api;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param string|null If defined, we'll try to get account based on this route parameter instead of header.
     * @return mixed
     */
    public function handle($request, Closure $next, $route_parameter = null)
    {
        $account_uuid = $route_parameter
            ? $request->route()->parameter($route_parameter)
            : null;
            
        if (!$account = $this->api->getAccount($account_uuid)):
            return response("You don't have access to this account.", 403);
        endif;

        return $next($request->merge(['requested_account' => $account]));
    }
}