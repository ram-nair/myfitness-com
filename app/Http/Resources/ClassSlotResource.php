<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassSlotResource extends JsonResource
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
            'slot_id' => $this->id,
            'slot_date' => $this->slot_date,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'slot_name' => $this->slot_name,
            'capacity' => $this->capacity,
        ];
    }
}
