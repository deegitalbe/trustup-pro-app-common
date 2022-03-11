<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts\Models;

use Deegitalbe\TrustupProAppCommon\Events\Traits\HavingAttributes;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Representing a contact retrieved from meilisearch.
 */
interface ContactContract extends Arrayable
{
    /**
     * Getting uuid.
     * 
     * @return string
     */
    public function getUuid(): string;

    /**
     * Getting id.
     * 
     * @return string
     */
    public function getId(): string;

    /**
     * Getting model id.
     * 
     * @return string
     */
    public function getModelId(): string;

    /**
     * Getting name.
     * 
     * @return string
     */
    public function getName(): string;

    /**
     * Getting company name.
     * 
     * @return string|null
     */
    public function getCompanyName(): ?string;

    /**
     * Getting vat number.
     * 
     * @return string|null
     */
    public function getVatNumber(): ?string;
    /**
     * Getting types.
     * 
     * @return array
     */
    public function getTypes(): array;
}