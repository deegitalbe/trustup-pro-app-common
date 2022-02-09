<?php
namespace Deegitalbe\TrustupProAppCommon\Contracts\Events;

/**
 * Representing an event having model attributes.
 */
interface HavingAttributesContract
{
    /**
     * Getting model attributes.
     * 
     * @return array
     */
    public function getAttributes(): array;

    /**
     * Setting model attributes.
     * 
     * @return static
     */
    public function setAttributes(array $attributes): HavingAttributesContract;
}