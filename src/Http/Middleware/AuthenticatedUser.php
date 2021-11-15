<?php
namespace Deegitalbe\TrustupProAppCommon\Http\Middleware;

use Closure;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\TrustupProApiContract;

class AuthenticatedUser
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
    public function handle($request, Closure $next)
    {       
        if (!$user = $this->api->getUser()):
            return response("Unauthenticated.", 401);
        endif;

        return $next($request->merge(['authenticated_user' => $user]));
    }
}