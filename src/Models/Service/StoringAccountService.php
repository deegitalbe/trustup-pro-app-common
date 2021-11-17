<?php
namespace Deegitalbe\TrustupProAppCommon\Models\Service;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Henrotaym\LaravelHelpers\Facades\Helpers;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Events\Account\AccountCreated;
use Deegitalbe\TrustupProAppCommon\Events\Hostname\HostnameCreated;
use Deegitalbe\TrustupProAppCommon\Rules\UserHavingAuthorizationKey;
use Deegitalbe\TrustupProAppCommon\Contracts\Query\AccountQueryContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AuthenticationRelatedContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Service\StoringAccountServiceContract;

/**
 * Service storing account.
 */
class StoringAccountService implements StoringAccountServiceContract
{
    /**
     * Authentication related informations.
     * 
     * @var AuthenticationRelatedContract
     */
    protected $authentication_related;

    /**
     * Service storing accounts.
     * 
     * @var AccountQueryContract
     */
    protected $account_query;

    /**
     * Constructing instance.
     * 
     * @param AuthenticationRelatedContract $authentication_related
     * @param AccountQueryContract $query
     * @return void
     */
    public function __construct(
        AuthenticationRelatedContract $authentication_related, 
        AccountQueryContract $account_query
    )
    {
        $this->authentication_related = $authentication_related;
        $this->account_query = $account_query;
    }

    /**
     * Storing account based on given request.
     * 
     * @param Request $request
     * @return AccountContract
     */
    public function store(Request $request): AccountContract
    {
        $attributes = $this->getAccountAttributes($request);
        event(new AccountCreated($attributes));
        $account = $this->account_query->whereUuid($attributes['uuid'])->first();
        event(new HostnameCreated($this->getHostnameAttributes($account), $account->getUuid()));

        return $account;
    }

    /**
     * Getting hostname attributes based on given account.
     * 
     * @param AccountContract $account
     * @return array Attributes to create hostname with.
     */
    protected function getHostnameAttributes(AccountContract $account): array
    {
        return ['fqdn' => $account->getUuid() . "." . str_replace('https://', '', config('app.url'))];
    }

    /**
     * Validating and getting account attributes based on given request.
     * 
     * @param Request $request
     * @return array
     */
    protected function getAccountAttributes(Request $request): array
    {
        $attributes = $request->validate([
            'authorization_key' => ['required', 'string', new UserHavingAuthorizationKey],
            'name' => ['nullable', 'string']
        ]);

        $attributes['uuid'] = Helpers::uuid();
        $attributes['name'] = $attributes['name'] ?? Str::slug($this->authentication_related->getUser()->getProfessional()->getCompany());

        return $attributes;
    }
}