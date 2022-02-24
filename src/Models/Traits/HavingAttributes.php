<?php
namespace Deegitalbe\TrustupProAppCommon\Models\Traits;

trait HavingAttributes
{
    /**
     * Setting model based on given attributes.
     * 
     * @param array $attributes
     * @return static
     */
    public function setAttributes(array $attributes): self
    {
        foreach($attributes as $name => $value):
            $this->{$name} = $value;
        endforeach;

        return $this;
    }
}