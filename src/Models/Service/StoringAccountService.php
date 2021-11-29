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
use Deegitalbe\TrustupProAppCommon\Contracts\Service\StoringAccountServiceContract;

// install chargebee package DONE
// authenticated user should have email first name and last name and professional should have customer id DONE
// update admin common package concerning app query
// this service should check if app is free or not and create chargebee subscription if needed
// emit event when subscription created and projector updating account chargebee status.

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
     * @return void
     */
    public function __construct(
        AuthenticationRelatedContract $authentication_related, 
        SubscriptionApiContract $subscription_api, 
        CustomerApiContract $customer_api, 
        AccountQueryContract $account_query
    )
    {
        $this->authentication_related = $authentication_related;
        $this->subscription_api = $subscription_api;
        $this->customer_api = $customer_api;
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
        $this->request = $request;
        $this->account = $this->createAccount();

        if (optional($this->authentication_related->getCurrentApp())->getPaid()):
            $this->subscribeAccount();
        endif;

        $this->afterSubscription();

        return $this->account;
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
            'name' => ['nullable', 'string']
        ]);

        $attributes['uuid'] = Helpers::uuid();
        $attributes['name'] = $attributes['name'] ?? Str::slug($this->authentication_related->getUser()->getProfessional()->getCompany());

        return $attributes;
    }
}