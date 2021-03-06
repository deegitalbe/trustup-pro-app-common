<?php
namespace Deegitalbe\TrustupProAppCommon\Models\Service;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Henrotaym\LaravelHelpers\Facades\Helpers;
use Deegitalbe\TrustupProAppCommon\Contracts\AccountContract;
use Deegitalbe\TrustupProAppCommon\Events\Account\AccountCreated;
use Deegitalbe\TrustupProAppCommon\Events\Hostname\HostnameCreated;
use Deegitalbe\TrustupProAppCommon\Events\Account\AccountSubscribed;
use Deegitalbe\TrustupProAppCommon\Rules\UserHavingAuthorizationKey;
use Deegitalbe\ChargebeeClient\Chargebee\Contracts\CustomerApiContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Query\AccountQueryContract;
use Deegitalbe\ChargebeeClient\Chargebee\Contracts\SubscriptionApiContract;
use Deegitalbe\ChargebeeClient\Chargebee\Models\Contracts\CustomerContract;
use Deegitalbe\TrustupProAppCommon\Contracts\AuthenticationRelatedContract;
use Deegitalbe\ChargebeeClient\Chargebee\Models\Contracts\SubscriptionContract;
use Deegitalbe\ChargebeeClient\Chargebee\Models\Contracts\SubscriptionPlanContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Service\EnvironmentSwitchContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Service\StoringAccountServiceContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Service\MeiliSearch\MeiliSearchIndexServiceContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Service\MeiliSearch\Models\MeiliSearchModelContract;
use Deegitalbe\TrustupProAppCommon\Facades\Package;

/**
 * Service storing account.
 */
class StoringAccountService implements StoringAccountServiceContract
{
    /**
     * Request made.
     * 
     * @var Request
     */
    protected $request;

    /**
     * Authentication related informations.
     * 
     * @var AuthenticationRelatedContract
     */
    protected $authentication_related;

    /**
     * Chargebee subscription API.
     * 
     * @var SubscriptionApiContract
     */
    protected $subscription_api;

    /**
     * Chargebee customer API.
     * 
     * @var CustomerApiContract
     */
    protected $customer_api;

    /**
     * Service storing accounts.
     * 
     * @var AccountQueryContract
     */
    protected $account_query;

    /**
     * MeiliSearch index service.
     * 
     * @var MeiliSearchIndexServiceContract
     */
    protected $meili_search_index_service;

    /**
     * MeiliSearch index service.
     * 
     * @var EnvironmentSwitchContract
     */
    protected $environment_switch;

    /**
     * Account being created
     * 
     * @var AccountContract
     */
    protected $account;

    /**
     * Subscription linked to account.
     * 
     * @var SubscriptionContract|null
     */
    protected $subscription;

    /**
     * Subscription plan linked to account.
     * 
     * @var SubscriptionPlanContract|null
     */
    protected $subscription_plan;

    /**
     * Customer linked to account.
     * 
     * @var CustomerContract|null
     */
    protected $customer;

    /**
     * Constructing instance.
     * 
     * @param AuthenticationRelatedContract $authentication_related
     * @param SubscriptionApiContract $subscription_api
     * @param CustomerApiContract $customer_api
     * @param AccountQueryContract $query
     * @param MeiliSearchIndexServiceContract $meili_search_index_service
     * @return void
     */
    public function __construct(
        AuthenticationRelatedContract $authentication_related, 
        SubscriptionApiContract $subscription_api, 
        CustomerApiContract $customer_api, 
        AccountQueryContract $account_query,
        MeiliSearchIndexServiceContract $meili_search_index_service,
        EnvironmentSwitchContract $environment_switch
    )
    {
        $this->authentication_related = $authentication_related;
        $this->subscription_api = $subscription_api;
        $this->customer_api = $customer_api;
        $this->account_query = $account_query;
        $this->meili_search_index_service = $meili_search_index_service;
        $this->environment_switch = $environment_switch;
    }

    /**
     * Storing account based on given request.
     * 
     * @param Request $request
     * @return AccountContract
     */
    public function store(Request $request): AccountContract
    {
        $this->request = $request;
        $this->account = $this->createAccount();

        if ($this->shouldSubscribeAccount()):
            $this->subscribeAccount();
        endif;

        $this->afterSubscription();

        // Storing meilisearch related data.
        $this->storeMeiliSearchIndexes();

        $this->finally();

        return $this->account;
    }

    /**
     * Telling if service should try to subscribe account.
     * 
     * @return bool
     */
    protected function shouldSubscribeAccount(): bool
    {
        // Subscribe if account is not having subscription id and current app is paid.
        return !$this->account->getSubscriptionId() 
            && optional($this->authentication_related->getCurrentApp())->getPaid();
    }

    /**
     * Creating account based on request.
     * 
     * @return AccountContract
     */
    protected function createAccount(): AccountContract
    {
        $attributes = $this->getAccountAttributes();
        event(
            app()->make(AccountCreated::class)
                ->setAttributes($attributes)
        );
        
        $account = $this->account_query->whereUuid($attributes['uuid'])->first();
        
        event(
            app()->make(HostnameCreated::class)
                ->setAttributes($this->getHostnameAttributes($account))
                ->setAccountUuid($account->getUuid())
        );

        return $account;
    }

    /**
     * Subscribing account.
     * 
     * @return void
     */
    protected function subscribeAccount()
    {
        if (!$this->subscription_plan = $this->getSubscriptionPlan()):
            return;
        endif;

        if (!$this->customer = $this->getCustomer()):
            return;
        endif;

        if (!$this->subscription = $this->createSubscription()):
            return;
        endif;

        event(
            app()->make(AccountSubscribed::class)
                ->setAttributes([
                    'chargebee_subscription_id' => $this->subscription->getId(),
                    'chargebee_subscription_status' => $this->subscription->getStatus(),
                ])
                ->setAccountUuid($this->account->getUuid())
        );

        $this->account->refresh();
    }

    /**
     * Getting customer to create subscription for
     * 
     * @return CustomerContract
     */
    protected function getCustomer(): CustomerContract
    {
        $customer = null;
        if ($customer_id = $this->authentication_related->getUser()->getProfessional()->getCustomerId()):
            $customer = $this->customer_api->find($customer_id);
        endif;

        if(!$customer):
            $user = $this->authentication_related->getUser();
            $customer = app()->make(CustomerContract::class)
                ->setFirstName($user->getFirstName())
                ->setLastName($user->getLastName())
                ->setEmail($user->getEmail());
        endif;

        return $customer;
    }

    /**
     * Getting subscription plan.
     * 
     * @return SubscriptionPlanContract|null Null if any error
     */
    protected function getSubscriptionPlan(): ?SubscriptionPlanContract
    {
        return $this->authentication_related->getCurrentApp()->getDefaultSubscriptionPlan();
    }

    /**
     * Creating subscription.
     * 
     * @return SubscriptionContract|null Null if error.
     */
    protected function createSubscription(): ?SubscriptionContract
    {
        $subscription = $this->subscription_api->create($this->subscription_plan, $this->customer);

        // if subscription was created and customer is known by chargebee, cancel at terms to avoid auto collection.
        if ($subscription && $this->customer->isPersisted()):
            return $this->subscription_api->cancelAtTerms($subscription);
        endif;
        
        return $subscription;
    }

    /**
     * Called automatically after account was subscribed.
     * 
     * @return void
     */
    protected function afterSubscription()
    {
        //
    }

    /**
     * Called automatically at the end of process.
     * 
     * @return void
     */
    protected function finally()
    {
        //
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
     * @return array
     */
    protected function getAccountAttributes(): array
    {
        $attributes = $this->request->validate([
            'authorization_key' => ['required', 'string', new UserHavingAuthorizationKey],
            'name' => ['nullable', 'string'],
            'chargebee_subscription_id' => ['sometimes', 'nullable', 'string'],
            'chargebee_subscription_status' => ['sometimes', 'nullable', 'string'],
            'uuid' => ['sometimes', 'nullable', 'string']
        ]);

        // Create uuid if none given or noll or empty string value.
        if (!isset($attributes['uuid']) || !$attributes['uuid']):
            $attributes['uuid'] = Helpers::uuid();
        endif;

        $attributes['name'] = $attributes['name'] ?? Str::slug($this->authentication_related->getUser()->getProfessional()->getCompany());

        // Removing null fields.
        return array_filter($attributes);
    }

    /**
     * Trying to store MeiliSearch index for current app.
     * 
     * @return bool
     */
    protected function storeMeiliSearchIndexes(): bool
    {
        // If no models, consider it as success.
        if (!count($this->getMeiliSearchModels())):
            return true;
        endif;

        // Creating indexes in account environment.
        return $this->environment_switch->callInAccountEnvironment($this->account, function() {
            $success = true;
            foreach($this->getMeiliSearchModels() as $model):
                if (!$this->meili_search_index_service->store($model)):
                    $success = false;
                endif;
            endforeach;
            
            return $success;
        });
    }

    /**
     * Getting models used by meilisearch.
     * 
     * @return MeiliSearchModelContract[]
     */
    protected function getMeiliSearchModels(): array
    {
        return collect(Package::meiliSearchModels())->map(function(string $class) {
            return app()->make($class);
        })->all();
    }
}