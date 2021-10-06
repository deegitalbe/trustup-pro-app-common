<?php
namespace Deegitalbe\TrustupProAppCommon\Http\Middleware;

use Closure;
use Deegitalbe\TrustupProAppCommon\Facades\Package;

/**
 * Restricting request to authorized servers only.
 */
class AuthorizedServer
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
        $authorization_key = $request->header('X-SERVER-AUTHORIZATION');
        if (!$authorization_key || $authorization_key !== Package::serverAuthorizationKey()):
            return response(['message' => "Forbidden."], 401);
        endif;

        return $next($request);
    }
}