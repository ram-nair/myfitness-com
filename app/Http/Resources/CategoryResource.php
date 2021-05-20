<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            "id" => $this->id,
            "business_type_category_id" => (int) $this->business_type_category_id,
            "name" => $this->name,
            "description" => $this->description,
            "parent_cat_id" => (int) $this->parent_cat_id,
            "image" => $this->image ?? null,
            "is_service" => (bool) $this->is_service,
            "service_type" => $this->service_type,
            "show_disclaimer" => (bool) $this->show_disclaimer,
            "disclaimer" => strip_tags($this->disclaimer),
        ];
        // return parent::toArray($request);
    }
}
