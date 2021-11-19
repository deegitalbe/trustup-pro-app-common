<?php
namespace Deegitalbe\TrustupProAppCommon\Http\Controllers\Common;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Deegitalbe\TrustupProAppCommon\Contracts\Query\AccountQueryContract;
use Deegitalbe\TrustupProAppCommon\Http\Resources\Account as AccountResource;

/**
 * Account related commons. 
 */
class AccountController extends Controller
{
    /**
     * Getting list of accounts.
     */
    public function index(Request $request, AccountQueryContract $query)
    {
        // available filters.
        $filters = $request->validate([
            'authorization_key' => "nullable|string"
        ]);
        
        // authorization key filter.
        $authorization_key = $filters['authorization_key'] ?? null;
        if ($authorization_key):
            $query->whereAuthorizationKey($authorization_key);
        endif;

        return AccountResource::collection($query->get());
    }
}