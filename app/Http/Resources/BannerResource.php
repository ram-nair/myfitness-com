<?php

namespace App\Http\Resources;

use App\StoreFavourite;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            'banner_id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'url' => $this->url,
            'image' => ($this->image != "") ? $this->image : null,
            'store' => !empty($this->store) ? new StoreResource($this->store) : null,
        ];
    }
}
