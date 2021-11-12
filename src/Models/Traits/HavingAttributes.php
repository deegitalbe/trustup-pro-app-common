<?php
namespace Deegitalbe\TrustupProAppCommon\Models\Traits;

trait havingAttributes
{
    /**
     * Setting model based on given attributes.
     * 
     * @param array $attributes
     * @return self
     */
    protected function setAttributes(array $attributes): self
    {
        foreach($attributes as $name => $value):
            $this->{$name} = $value;
        endforeach;

        return $this;
    }
}