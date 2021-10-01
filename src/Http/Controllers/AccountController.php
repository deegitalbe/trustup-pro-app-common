<?php
namespace Deegitalbe\TrustupProAppCommon\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Deegitalbe\TrustupProAppCommon\Exceptions\Webhooks\AccountUpdateFailed;

class AccountController extends Controller
{
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

        return response([
            'data' => $request->account
        ]);
    }
}