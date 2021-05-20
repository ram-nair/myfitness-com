<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GeneralClassResource extends JsonResource
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
            "title" => $this->title,
            "instructor" => $this->instructor,
            "overview" => $this->overview,
            "image" => $this->image,
            "cancelation_policy" => $this->cancelation_policy,
            "start_date" => $this->start_date,
            "end_date" => $this->end_date,
            "type" => $this->type,
            "community_name" => $this->community_name,
            "amenity_name" => $this->amenity,
            "category_id" => $this->category_id,
            "packages" => $this->packages->isEmpty() ? null : PackageResource::collection($this->packages),
            // "type" => $this->type,
            // "type" => $this->type,
            // "type" => $this->type,
        ];
    }
}
