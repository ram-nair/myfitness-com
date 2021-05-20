<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SlotResource extends JsonResource
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
            "slot_id" =>  $this->id,
            "store_id" =>  $this->store_id,
            "days" =>  $this->days ?? false,
            "capacity" =>  $this->capacity ?? false,
            "slot_name" =>  $this->slots->name,
        ];
    }
}
