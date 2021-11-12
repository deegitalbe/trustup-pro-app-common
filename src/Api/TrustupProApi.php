<?php
namespace Deegitalbe\TrustupProAppCommon\Api;

use Deegitalbe\TrustupProAppCommon\Models\User;
use Deegitalbe\TrustupProAppCommon\Facades\Package;
use Deegitalbe\TrustupProAppCommon\Models\Professional;
use Henrotaym\LaravelApiClient\Contracts\RequestContract;
use Deegitalbe\TrustupProAppCommon\Contracts\UserContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Contracts\ProfessionalContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\TrustupProApiContract;
use Deegitalbe\TrustupProAppCommon\Exceptions\TrustupProApi\GetUserFailed;
use Deegitalbe\TrustupProAppCommon\Contracts\Api\Client\TrustupProClientContract;
use Deegitalbe\TrustupProAppCommon\Exceptions\TrustupProApi\GetExpectedAccountFailed;
use Deegitalbe\TrustupProAppCommon\Exceptions\TrustupProApi\UserNotHavingAccessToAccount;

class TrustupProApi implements TrustupProApiContract
{
    /**
     * Underlying client.
     * 
     * @var TrustupProClientContract
     */
    protected $client;

    /**
     * Constructing class.
     * 
     * @param TrustupProClientContract $client
     * @return void
     */
    public function __construct(TrustupProClientContract $client)
    {
        $this->client = $client;
    }

    /**
     * Getting authenticated user linked to current request.
     * 
     * @return UserContract|null
     */
    public function getUser(): ?UserContract
    {
        $request = app()->make(RequestContract::class)
            ->setVerb('GET')
            ->setUrl('api/user');
        
        $response = $this->client->try($request, new GetUserFailed);

        if ($response->failed()):
            report($response->error());
            return null;
        endif;

        $user = $response->response()->get(true)['user'];

        return $this->toUserModel($user);
    }

    /**
     * Getting account linked to current request.
     * 
     * @param Request $request
     * @return Account|null Null if any error occured.
     */
    public function getAccount(): ?AccountContract
    {
        $user = $this->getUser();
        
        if (!$user):
            return null;
        endif;
        
        $account = $this->getExpectedAccount();

        if (!$account):
            return null;
        endif;

        if(!$user->hasAccessToAccount($account)):
            report(UserNotHavingAccessToAccount::get($user, $account));
            return null;
        endif;

        return $account;
    }

    /**
     * Making sure current request can access given account.
     * 
     * @param AccountContract $account
     * @return bool Access success state.
     */
    public function hasAccessToAccount(AccountContract $account): bool
    {
        return optional($this->getUser())->hasAccessToAccount($account) || false;
    }

    /**
     * Getting expected account from current request.
     * 
     * @param Request $request
     * @return Account|null Null if not found.
     */
    protected function getExpectedAccount(): ?AccountContract
    {
        $account_uuid = request()->header(Package::requestedAccountHeader());
        
        if (!$account_uuid):
            return $this->expectedAccountNotFound();
        endif;

        $account = Package::account()::firstMatchingUuid($account_uuid);

        if ($account):
            return $this->expectedAccountNotFound();
        endif;
    }

    /**
     * Behavior when account is not found.
     * 
     * @return null
     */
    protected function expectedAccountNotFound()
    {
        report(new GetExpectedAccountFailed);
        
        return null;
    }

    /**
     * Transforming raw user attributes to user model.
     * 
     * @param array $raw_user
     * @return UserContract
     */
    protected function toUserModel(array $attributes)
    {
        // Setting up role.
        $attributes['role'] = $attributes['default_professional']['user_role'];
        
        // Setting up professional.
        $attributes['professional'] = app()->make(ProfessionalContract::class)->fromArray($attributes['default_professional']);
        unset($attributes['default_professional']);

        return app()->make(UserContract::class)->fromArray($attributes);
    }
}