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
     * @param string|null $uuid If null, account header will be used.
     * @return Account|null Null if any error occured.
     */
    public function getAccount(?string $account_uuid = null): ?AccountContract
    {
        $user = $this->getUser();
        
        if (!$user):
            return null;
        endif;
        
        $account = $this->getExpectedAccount($account_uuid);

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
     * Getting expected account for current request.
     * 
     * @param string|null $account_uuid If null, account header will be used.
     * @return Account|null Null if not found.
     */
    protected function getExpectedAccount(?string $account_uuid): ?AccountContract
    {
        $account_uuid = $account_uuid || request()->header(Package::requestedAccountHeader());
        
        if (!$account_uuid):
            return $this->expectedAccountNotFound($account_uuid);
        endif;

        $account = Package::account()::firstMatchingUuid($account_uuid);

        if ($account):
            return $this->expectedAccountNotFound($account_uuid);
        endif;
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