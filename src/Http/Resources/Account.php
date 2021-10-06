<?php
namespace Deegitalbe\TrustupProAppCommon\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Account extends JsonResource
{
    /**
     * Transform the resource into an array. (underlying $this refers to AccountContract)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->getUuid(),
            'authorization_key' => $this->getAuthorizationKey(),
            'app_key' => $this->getAppKey()
        ];
    }
}