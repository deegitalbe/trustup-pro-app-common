
<?php
namespace Deegitalbe\TrustupProAppCommon\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Deegitalbe\TrustupProAppCommon\Contracts\Models\ContactContract;

class Contact extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var ContactContract */
        $resource = $this->resource;

        return $resource->toArray();
    }
}