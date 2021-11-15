<?php
namespace Deegitalbe\TrustupProAppCommon\Http\Controllers\App;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Deegitalbe\TrustupProAppCommon\Http\Resources\Account;
use Deegitalbe\TrustupProAppCommon\Contracts\Query\AccountQueryContract;

/**
 * Handling basic requests about accounts.
 */
class AccountController extends Controller
{
    /**
     * Getting single account based on request.
     */
    public function show(Request $request)
    {
        return new Account($request->requested_account);
    }

    /**
     * Getting accounts by authenticated user's professional authorization key
     */
    public function byAuthorizationKey(Request $request)
    {
        $accounts = app()->make(AccountQueryContract::class)
            ->whereAuthorizationKey($request->authenticated_user->getProfessional()->getAuthorizationKey())
            ->get();

        return Account::collection($accounts);
    }
}