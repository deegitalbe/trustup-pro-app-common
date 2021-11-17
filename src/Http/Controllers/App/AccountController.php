<?php
namespace Deegitalbe\TrustupProAppCommon\Http\Controllers\App;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Http\Resources\Account;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Query\AccountQueryContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AuthenticationRelatedContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Service\StoringAccountServiceContract;

/**
 * Handling basic requests about accounts.
 */
class AccountController extends Controller
{
    /**
     * Getting single account based on request.
     */
    public function show(Request $request, AuthenticationRelatedContract $authentication_related)
    {
        return $this->getAccountResource($authentication_related->getAccount());
    }

    /**
     * Storing account based on given data.
     */
    public function store(Request $request, StoringAccountServiceContract $storing_service)
    {
        return $this->getAccountResource($storing_service->store($request));
    }

    /**
     * Getting accounts by authenticated user's professional authorization key
     */
    public function byAuthorizationKey(Request $request, AuthenticationRelatedContract $authentication_related)
    {
        $accounts = app()->make(AccountQueryContract::class)
            ->whereAuthorizationKey($authentication_related->getUser()->getProfessional()->getAuthorizationKey())
            ->get();

        return Package::accountResource()::collection($accounts);
    }

    /**
     * Getting account resource based on given account.
     * 
     * @return mixed Account resource.
     */
    protected function getAccountResource(AccountContract $account)
    {
        return app()->make(Package::accountResource(), ['resource' => $account]);
    }
}