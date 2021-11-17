<?php
namespace Deegitalbe\TrustupProAppCommon\Http\Controllers\Common;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Http\Resources\Account as AccountResource;
use Deegitalbe\TrustupProAppCommon\Exceptions\Webhooks\AccountUpdateFailed;

/**
 * Account related webhooks. 
 */
class AccountController extends Controller
{
    /**
     * Updating account.
     */
    public function update(Request $request)
    {
        $request->account->fill($request->except(['account']));
        
        if(!$request->account->saveQuietly()):
            $error = new AccountUpdateFailed();
            report($error
                ->setAttributes($request->except(['account']))
                ->setAccount($request->account)
            );
            return response(['message' => "Account update failed."], 500);
        endif;

        return new AccountResource($request->account);
    }

    /**
     * Getting list of accounts.
     */
    public function index()
    {
        return AccountResource::collection(Package::account()::all());
    }
}