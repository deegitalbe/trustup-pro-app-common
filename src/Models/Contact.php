<?php
namespace Deegitalbe\TrustupProAppCommon\Models;

use Deegitalbe\TrustupProAppCommon\Contracts\Events\HavingAttributesContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Models\ContactContract;
use Deegitalbe\TrustupProAppCommon\Events\Traits\HavingAttributes;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Representing a contact retrieved from meilisearch.
 */
class Contact implements ContactContract, HavingAttributesContract, Arrayable
{
    use HavingAttributes;

    /**
     * Getting uuid.
     * 
     * @return string
     */
    public function getUuid(): string
    {
        return $this->attributes['uuid'];
    }

    /**
     * Getting id.
     * 
     * @return string
     */
    public function getId(): string
    {
        return $this->attributes['id'];
    }

    /**
     * Getting model id.
     * 
     * @return string
     */
    public function getModelId(): string
    {
        return $this->attributes['model_id'];
    }

    /**
     * Getting name.
     * 
     * @return string
     */
    public function getName(): string
    {
        return $this->attributes['name'];
    }

    /**
     * Getting company name.
     * 
     * @return string|null
     */
    public function getCompanyName(): ?string
    {
        return $this->attributes['company_name'];
    }

    /**
     * Getting vat number.
     * 
     * @return string|null
     */
    public function getVatNumber(): ?string
    {
        return $this->attributes['vat_number'];
    }

    /**
     * Getting vat number.
     * 
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->attributes['email'];
    }

    /**
     * Getting types.
     * 
     * @return array
     */
    public function getTypes(): array
    {
        return $this->attributes['types'];
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }
}