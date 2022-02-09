<?php
namespace Deegitalbe\TrustupProAppCommon\Http\Controllers\Common\Webhook;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Events\Account\AccountUpdatedFromWebhook;
use Deegitalbe\TrustupProAppCommon\Exceptions\Webhooks\AccountUpdateFailed;
use Deegitalbe\TrustupProAppCommon\Http\Resources\Account as AccountResource;
use Henrotaym\LaravelHelpers\Contracts\HelpersContract;

/**
 * Account webhooks. 
 */
class AccountController extends Controller
{
    /**
     * Updating account.
     */
    public function update(HelpersContract $helpers, Request $request)
    {
        [$fail, $response] = $helpers->try(function() use ($request) {
            /** @var AccountContract */
            $account = $request->account;

            // Creating event and setting it up.
            /** @var AccountUpdatedFromWebhook */
            $update_event = app()->make(AccountUpdatedFromWebhook::class);
            $update_event->setAccountUuid($account->getUuid())
                ->setAttributes($request->except(['account']));

            // Firing event to trigger projector.
            event($update_event);

            // Returning updated account.
            return new AccountResource($account->refresh());
        });

        // If failing report error and return a 500 status code response.
        if ($fail):
            $error = new AccountUpdateFailed();
            report($error
                ->setAttributes($request->except(['account']))
                ->setAccount($request->account)
            );
            return response(['message' => "Account update failed."], 500);
        endif;

        // Return updated account if success.
        return $response;
    }
}