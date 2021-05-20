<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceStoreProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "product_id" => $this->id,
            "unit_price" => round_my_number($this->unit_price),
            "store_id" => $this->store_id,
            "product" => new ServiceProductResource($this->product),
        ];
    }
}
