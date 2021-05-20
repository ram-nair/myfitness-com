<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceProductResource extends JsonResource
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
            "category_id" => $this->category_id,
            "description" => $this->description,
            "max_hour" => $this->max_hour,
            "max_person" => $this->max_person,
            // "quantity" => $this->quantity,
            "status" => (boolean) $this->status,
            "featured" => (boolean) $this->featured,
            "image" => $this->image,
        ];
    }
}
