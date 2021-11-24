<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts;

use Illuminate\Support\Collection;
use Deegitalbe\ChargebeeClient\Chargebee\Models\Contracts\SubscriptionPlanContract;

/**
 * Representing an app.
 */
interface AppContract
{
    /**
     * Getting key.
     * 
     * @return int
     */
    public function getKey(): int;

    /**
     * Getting url.
     * 
     * @return string
     */
    public function getUrl(): string;
    
    /**
     * Getting paid status.
     * 
     * @return string
     */
    public function getPaid(): bool;

    /**
     * Getting raw plans.
     * 
     * @return array
     */
    public function getPlans(): array;

    /**
     * Getting subscription plans for application.
     * 
     * @return Collection
     */
    public function getSubscriptionPlans(): Collection;

    /**
     * Getting default subscription plan.
     * 
     * @return SubscriptionPlanContract|null
     */
    public function getDefaultSubscriptionPlan(): ?SubscriptionPlanContract;

    /**
     * Setting model based on given attributes.
     * 
     * @param array $attributes
     * @return AppContract
     */
    public function setAttributes(array $attributes): AppContract;
}