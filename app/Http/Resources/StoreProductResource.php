<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreProductResource extends JsonResource
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
            "out_of_stock" => ($this->stock < 1 || $this->out_of_stock == 1) ? (bool) true : (bool) false,
            "quantity_per_person" => (int)$this->quantity_per_person,
            "product" => new ProductResource($this->product),
            // "product_id" => $this->product_id,
        ];
        // return parent::toArray($request);
    }
}
