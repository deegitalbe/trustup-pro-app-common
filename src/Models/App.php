<?php
namespace Deegitalbe\TrustupProAppCommon\Models;

use stdClass;
use Illuminate\Support\Collection;
use Deegitalbe\TrustupProAppCommon\Contracts\AppContract;
use Deegitalbe\TrustupProAppCommon\Models\Traits\HavingAttributes;
use Deegitalbe\ChargebeeClient\Chargebee\Models\Contracts\SubscriptionPlanContract;

/**
 * Representing a professional.
 */
class App implements AppContract
{
    use HavingAttributes;

    /**
     * Application key.
     * 
     * @var string
     */
    protected $key;

    /**
     * Application url.
     * 
     * @var string
     */
    protected $url;

    /**
     * Application raw plans.
     * 
     * @var array
     */
    protected $plans;

    /**
     * Application paid status.
     * 
     * @var string
     */
    protected $paid;

    /**
     * Getting key.
     * 
     * @return int
     */
    public function getKey(): int
    {
        return $this->key;
    }

    /**
     * Getting raw plans.
     * 
     * @return array
     */
    public function getPlans(): array
    {
        return $this->plans;
    }
    
    /**
     * Getting paid status.
     * 
     * @return string
     */
    public function getPaid(): bool
    {
        return $this->paid;
    }

    /**
     * Getting url.
     * 
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Getting subscription plans for application.
     * 
     * @return Collection
     */
    public function getSubscriptionPlans(): Collection
    {
        return collect($this->plans)->map(function(array $plan) {
            return app()->make(SubscriptionPlanContract::class)
                ->setId($plan['name'])
                ->setTrialDuration($plan['trial_duration']);
        }); 
    }

    /**
     * Getting default subscription plan.
     * 
     * @return SubscriptionPlanContract|null
     */
    public function getDefaultSubscriptionPlan(): ?SubscriptionPlanContract
    {
        $default_plan = collect($this->plans)->first(function(array $plan) {
            return $plan['is_default'];
        });

        return $this->getSubscriptionPlans()->first(function(SubscriptionPlanContract $plan) use ($default_plan) {
            return $plan->getId() === $default_plan['name'];
        });
    }
}