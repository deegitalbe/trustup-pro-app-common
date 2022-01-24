<?php
namespace Deegitalbe\TrustupProAppCommon\Http\Controllers\Common\Webhook;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Exceptions\Webhooks\AccountUpdateFailed;
use Deegitalbe\TrustupProAppCommon\Http\Resources\Account as AccountResource;

/**
 * Account webhooks. 
 */
class AccountController extends Controller
{
    /**
     * Updating account.
     */
    public function update(Request $request)
    {
        $success = Package::account()::withoutEvents(function () use ($request) {
            return $request->account
                ->fill($request->except(['account']))
                ->save();
        });
        
        if(!$success):
            $error = new AccountUpdateFailed();
            report($error
                ->setAttributes($request->except(['account']))
                ->setAccount($request->account)
            );
            return response(['message' => "Account update failed."], 500);
        endif;

        return new AccountResource($request->account);
    }
}