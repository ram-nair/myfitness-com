<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceCategoryResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'service_type' => $this->service_type,
            'show_disclaimer' => $this->show_disclaimer,
            'disclaimer' => strip_tags($this->disclaimer),
            'business_type_category_id' => $this->business_type_category_id,
            'parent_cat_id' => $this->parent_cat_id,
            'child_categories' => (bool) ($this->service_type == "service_type_2" &&
                $this->catHasChild($this->business_type_category_id)->count() > 0)
            ? true : false,
        ];
    }
}
