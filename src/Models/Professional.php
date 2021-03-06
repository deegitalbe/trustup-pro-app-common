<?php
namespace Deegitalbe\TrustupProAppCommon\Models;

use Deegitalbe\TrustupProAppCommon\Contracts\ProfessionalContract;
use Deegitalbe\TrustupProAppCommon\Models\Traits\HavingAttributes;

/**
 * Representing a professional.
 */
class Professional implements ProfessionalContract
{
    use HavingAttributes;

    /**
     * Professional id.
     * 
     * @var int
     */
    protected $id;

    /**
     * Professional company.
     * 
     * @var string
     */
    protected $company;

    /**
     * Professional authorization_key.
     * 
     * @var string
     */
    protected $authorization_key;

    /**
     * Chargebee customer id.
     * 
     * @return string|null
     */
    protected $chargebee_customer_id;
    
    /**
     * Getting id.
     * 
     * @return string
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * Getting company name.
     * 
     * @return string
     */
    public function getCompany(): string
    {
        return $this->company;
    }

    /**
     * Getting unique authorization key.
     * 
     * @return string
     */
    public function getAuthorizationKey(): string
    {
        return $this->authorization_key;
    }

    /**
     * Getting chargebee customer id.
     * 
     * @return string|null
     */
    public function getCustomerId(): ?string
    {
        return $this->chargebee_customer_id;
    }

    /**
     * Transforming to array.
     * 
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'company' => $this->company,
            'authorization_key' => $this->authorization_key,
            'chargebee_customer_id' => $this->chargebee_customer_id
        ];
    }

    /**
     * Getting professional from an attributes array.
     * 
     * @param array $attributes
     * @return ProfessionalContract
     */
    public function fromArray(array $attributes): ProfessionalContract
    {
        return $this->setAttributes($attributes);
    }
}