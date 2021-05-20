<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $communityName = null;
        $amenityName = null;
        if ($this->generalClass) {
            $communityName = $this->generalClass->community_name ?? null;
            $amenityName = $this->generalClass->amenity_name ?? null;
        }
        return [
            'order_id' => $this->id,
            'order_tracking_id' => $this->order_id,
            'class_type' => $this->class_type,
            'class_id' => $this->class_id,
            'class_name' => $this->class_name,
            'community_name' => $communityName,
            'amenity_name' => $amenityName,
            'package_id' => $this->package_id,
            'package_name' => $this->package_name,
            'payment_method' => $this->payment_type,
            'order_type' => $this->order_type,
            'order_status' => $this->order_status,
            'vat_percentage' => $this->vat_percentage,
            'amount_exclusive_vat' => round_my_number($this->amount_exclusive_vat),
            'vat_amount' => round_my_number($this->vat_amount),
            'service_charge' => round_my_number($this->service_charge),
            'total_amount' => round_my_number($this->total_amount),
            'notes' => $this->notes ?? null,
            'sessions' => $this->orderSlots->isEmpty() ? null : ClassOrderSlotResource::collection($this->orderSlots),
            'created_at' => $this->created_at,
        ];
    }
}
