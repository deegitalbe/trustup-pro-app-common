<?php
namespace Deegitalbe\TrustupProAppCommon\Events\Traits;

use Deegitalbe\TrustupProAppCommon\Contracts\Events\HavingAttributesContract;

trait HavingAttributes
{
    /**
     * Model attributes
     * 
     * @var array
     */
    public $attributes;

    /**
     * Getting model attributes.
     * 
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Setting model attributes.
     * 
     * @return static
     */
    public function setAttributes(array $attributes): HavingAttributesContract
    {
        $this->attributes = $attributes;
        
        return $this;
    }
}