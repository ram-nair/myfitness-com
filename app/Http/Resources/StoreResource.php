<?php

namespace App\Http\Resources;

use App\BusinessType;
use App\BusinessTypeCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //check wether request coming from favorite list
        $fav = $request->fav ?? false;
        return [
            "store_id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "phone" => $this->mobile,
            "image" => $this->image,
            "description" => $this->description,
            "location" => $this->location,
            'business_type' => $this->businessType->slug,
            'business_type_category_id' => $this->business_type_category_id,
            // "store_timing" => $this->store_timing,
            "store_timing" => $this->store_hours,
            "location_type" => $this->location_type,
            "in_store" => $this->in_store ? true : false,
            "in_my_location" => $this->my_location ? true : false,
            "male" => (bool) $this->male,
            "female" => (bool) $this->female,
            "any" => (bool) $this->any,
            "in_my_location_charge" => round_my_number($this->on_my_location_charge),
            "credit_card" => (bool) $this->credit_card,
            "cash_accept" => (bool) $this->cash_accept,
            "bring_card" => (bool) $this->bring_card,
            "service_type" => $this->service_type,
            "min_order_amount" => round_my_number($this->min_order_amount),
            "service_charge" => round_my_number($this->service_charge),
            "latitude" => $this->latitude,
            "longitude" => $this->longitude,
            "speed" => (int) $this->speed,
            "accuracy" => (int) $this->accuracy,
            "time_to_deliver" => $this->time_to_deliver,
            "distance" => $this->distance,
            "status" => (bool) $this->status,
            "featured" => (bool) $this->featured,
            "favorite" => $fav == "list" ? true : $this->favorite_store ? true : false,
        ];
    }
}
