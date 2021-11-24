<?php
namespace Deegitalbe\TrustupProAppCommon\Models\Traits;

trait HavingAttributes
{
    /**
     * Setting model based on given attributes.
     * 
     * @param array $attributes
     * @return self
     */
    public function setAttributes(array $attributes): self
    {
        foreach($attributes as $name => $value):
            $this->{$name} = $value;
        endforeach;

        return $this;
    }
}