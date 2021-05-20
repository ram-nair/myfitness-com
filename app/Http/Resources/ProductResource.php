<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            "name" => $this->name,
            "brand_id" => $this->brand->name ?? null,
            "sub_category_id" => $this->sub_category_id,
            "sku" => $this->sku,
            "description" => $this->description,
            "quantity_label" => $this->quantity,
            "unit_label" => (string) $this->unit,
            "status" => $this->status,
            "featured" => $this->featured,
            "images" => ($this->images->count() > 0) ? new ProductImageCollection($this->images) : null,
        ];
    }
}
