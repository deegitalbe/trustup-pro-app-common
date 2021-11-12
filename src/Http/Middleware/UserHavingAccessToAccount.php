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
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$account = $this->api->getAccount()):
            return response("You don't have access to this account.", 403);
        endif;

        return $next($request->merge(['requested_account' => $account]));
    }
}