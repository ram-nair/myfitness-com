<?php

namespace App\Http\Resources;

use App\Http\Resources\OrderItemCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $slot = null;
        if (strtolower($this->order_type) === 'scheduled' && $this->slot_id) {
            $slot = [
                'date' => $this->storeDaySlot->days,
                'time' => $this->storeDaySlot->slots->slot_name,
            ];
        }
        return [
            'order_id' => $this->id,
            'order_tracking_id' => $this->order_id,
            'speed' => (int) $this->rating, //order speed
            'accuracy' => (int) $this->accuracy, //order accuracy
            'store_id' => $this->store->id,
            'store_speed' => (int) $this->store->speed, //store_speed
            'store_accuracy' => (int) $this->store->accuracy, //store accuracy
            'store_name' => $this->store->name,
            'store_email' => $this->store->email,
            'store_phone' => $this->store->mobile,
            'address_id' => $this->address_id,
            'vat_percentage' => $this->vat_percentage,
            'payment_method' => $this->payment_type,
            'order_type' => $this->order_type,
            'slot_id' => $this->slot_id,
            'slot' => $slot,
            'order_status' => $this->order_status,
            'amount_exclusive_vat' => round_my_number($this->amount_exclusive_vat),
            'vat_amount' => round_my_number($this->vat_amount),
            'service_charge' => round_my_number($this->service_charge),
            'total_amount' => round_my_number($this->total_amount),
            'scheduled_notes' => $this->scheduled_notes ?? null,
            'notes' => $this->notes ?? null,
            'store' => new StoreResource($this->store),
            'order_substitution_expiry' => $this->created_at->addMinutes(config('settings.order_cancel_duration')),
            'order_items' => new OrderItemCollection($this->orderItem),
            'created_at' => $this->created_at,
        ];
    }
}
