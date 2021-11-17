<?php
namespace Deegitalbe\TrustupProAppCommon\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\TrustupProApiContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Query\AccountQueryContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AuthenticationRelatedContract;
use Deegitalbe\TrustupProAppCommon\Exceptions\Middleware\UserHavingAccessToAccount\GetExpectedAccountFailed;
use Deegitalbe\TrustupProAppCommon\Exceptions\Middleware\UserHavingAccessToAccount\UserNotHavingAccessToAccount;

/**
 * Middleware making sure that authenticated user is having access to expected account environment.
 */
class UserHavingAccessToAccount
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
    public function handle($request, Closure $next, $route_parameter = null)
    {
        $account = $this->getExpectedAccount($request, $route_parameter);
        $user = $this->authentication_related->getUser();

        if (!$account || !$user):
            return $this->notHavingAccess();
        endif;

        if(!$user->hasAccessToAccount($account)):
            report(UserNotHavingAccessToAccount::get($user, $account));
            return $this->notHavingAccess();
        endif;

        $this->authentication_related->setAccount($account);

        return $next($request);
    }

    /**
     * Getting expected account for current request.
     * 
     * @param Request $request
     * @param string|null $account_uuid If null, account header will be used.
     * @return Account|null Null if not found.
     */
    protected function getExpectedAccount(Request $request, ?string $route_parameter = null): ?AccountContract
    {
        $account_uuid = $route_parameter
            ? $request->route()->parameter($route_parameter)
            : $request->header(Package::requestedAccountHeader());
        
        if (!$account_uuid):
            return $this->expectedAccountNotFound($account_uuid);
        endif;

        $account = app()->make(AccountQueryContract::class)
            ->whereUuid($account_uuid)
            ->first();

        if (!$account):
            return $this->expectedAccountNotFound($account_uuid);
        endif;

        return $account;
    }

    /**
     * Behavior when account is not found.
     * 
     * @param string|null $account_uuid
     * @return null
     */
    protected function expectedAccountNotFound(?string $account_uuid)
    {
        report(GetExpectedAccountFailed::forUuid($account_uuid));
        
        return null;
    }

    protected function notHavingAccess()
    {
        return response(['message' => "You don't have access to this account."], 403);
    }
}