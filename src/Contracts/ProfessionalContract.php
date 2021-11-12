<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Representing a professional.
 */
interface ProfessionalContract extends Arrayable
{
    /**
     * Getting id.
     * 
     * @return int
     */
    public function getId(): int;
    
    /**
     * Getting company name.
     * 
     * @return string
     */
    public function getCompany(): string;

    /**
     * Getting unique authorization key.
     * 
     * @return string
     */
    public function getAuthorizationKey(): string;

    /**
     * Getting professional from an attributes array.
     * 
     * @param array $attributes
     * @return ProfessionalContract
     */
    public function fromArray(array $attributes): ProfessionalContract;
}