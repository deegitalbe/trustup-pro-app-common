<?php

namespace Deegitalbe\TrustupProAppCommon\Http\Middleware;

use Closure;
use Hyn\Tenancy\Environment;
use App\Http\AccountRequestManager;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Contracts\AuthenticationRelatedContract;

/**
 * Middleware setting up requested account as current environment.
 */
class SettingAccountAsEnvironment
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
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$account = $this->authentication_related->getAccount()):
            return response(['message' => "Could not understand which account should be used for this request."], 400);
        endif;

        Package::environment()->tenant($account);
        Package::environment()->hostname($account->hostnames()->first());

        return $next($request);
    }
}