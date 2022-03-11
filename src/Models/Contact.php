<?php
namespace Deegitalbe\TrustupProAppCommon\Models;

use Deegitalbe\TrustupProAppCommon\Contracts\Events\HavingAttributesContract;
use Deegitalbe\TrustupProAppCommon\Contracts\Models\ContactContract;
use Deegitalbe\TrustupProAppCommon\Events\Traits\HavingAttributes;

/**
 * Representing a contact retrieved from meilisearch.
 */
class Contact implements ContactContract, HavingAttributesContract
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
     * Getting types.
     * 
     * @return array
     */
    public function getTypes(): array
    {
        return $this->attributes['types'];
    }
}