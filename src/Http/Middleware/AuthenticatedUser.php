<?php
namespace Deegitalbe\TrustupProAppCommon\Http\Middleware;

use Closure;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\TrustupProApiContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AuthenticationRelatedContract;

/**
 * Middleware verifying that user is authenticated by trustup.pro
 */
class AuthenticatedUser
{
    /**
     * Authentication related actions.
     * 
     * @var AuthenticationRelatedContract
     */
    protected $authentication_related;
    
    public function __construct(AuthenticationRelatedContract $authentication_related)
    {
        $this->authentication_related = $authentication_related;
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
        if (!$this->authentication_related->getUser()):
            return response(['message' => "Unauthenticated."], 401);
        endif;

        return $next($request);
    }
}