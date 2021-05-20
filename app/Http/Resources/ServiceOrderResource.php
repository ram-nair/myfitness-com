<?php

namespace App\Http\Resources;

use App\Http\Resources\ServiceOrderItemCollection;
use App\ServiceProducts;
use App\StoreServiceProducts;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
class ServiceOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $pickUpSlot = null;
        $dropOffSlot = null;
        if (strtolower($this->service_type) === 'service_type_1') {
            if ($this->pick_up_slot_id) {
                $pickUpSlot = [
                    'date' => $this->pick_up_date,
                    'time' => $this->pick_up_slot
                ];
            }
            if ($this->drop_off_slot_id) {
                $dropOffSlot = [
                    'time' => $this->drop_off_slot,
                ];
            }
        }
        $locationAddress = null;
        $serviceSchedule = null;
        if (strtolower($this->service_type) === 'service_type_2') {
            if ($this->location_type === "my_location") {
                $locationAddress = $this->delivery_address;
            }
            if ($this->pick_up_slot_id) {
                $serviceSchedule = [
                    'date' => $this->pick_up_date,
                    'time' => $this->pick_up_slot,
                ];
            }
        }
        $order_product_detail = null;
        if (strtolower($this->service_type) === 'service_type_3') {
            $store_product_id = $this->orderItem[0]->product_id;
            Log::info('store_product_id', ['Exception' =>$store_product_id]);
            Log::info('orderItem', ['Exception' =>$this->orderItem]);

            $store_product_detail = StoreServiceProducts::where('id',$store_product_id)->get();
            if(count($store_product_detail)>0){
                if($store_product_detail[0]){
                    $order_product_detail =  ServiceStoreProductResource::collection($store_product_detail)[0];
                }
            }

        }

        return [
            'order_id' => $this->id,
            'order_track_id' => $this->order_id,
            'speed' => (int) $this->rating, //order speed
            'accuracy' => (int) $this->accuracy, //order accuracy
            'store_id' => $this->store_id,
            'store_name' => $this->store->name,
            'store_email' => $this->store->email,
            'store_phone' => $this->store->mobile,
            'store_speed' => (int) $this->store->speed, //store_speed
            'store_accuracy' => (int) $this->store->accuracy, //store accuracy
            'service_type' => $this->service_type,
            'order_status' => $this->order_status,
            'delivery_address' => $this->location_type === "my_location" ? null : $this->delivery_address,
            'service_address' => $locationAddress,
            'service_schedule' => $serviceSchedule,
            'gender_preference' => $this->gender_preference ?? null,
            'vat_percentage' => $this->vat_percentage,
            'payment_type' => $this->payment_type,
            'pick_up_slot_id' => $this->pick_up_slot_id,
            'pick_up_slot' => $pickUpSlot,
            'drop_off_slot_id' => $this->drop_off_slot_id,
            'drop_off_slot' => $dropOffSlot,
            'max_cleaner' => $this->max_cleaner,
            'max_hour' => $this->max_hour,
            'cleaning_materials' => $this->cleaning_materials,
            'schedule_date' => $this->schedule_date,
            'schedule_time' => $this->schedule_time,
            'service_location' => $this->service_location,
            'service_place' => $this->location_type,
            'amount_exclusive_vat' => round_my_number($this->amount_exclusive_vat),
            'on_my_location_charge' => round_my_number($this->on_my_location_charge) ?? 0,
            'vat_amount' => round_my_number($this->vat_amount),
            'service_charge' => round_my_number($this->service_charge),
            'total_amount' => round_my_number($this->total_amount),
            'store' => new StoreResource($this->store),
            'order_product_detail' => $order_product_detail,
            'order_items' => new ServiceOrderItemCollection($this->orderItem),
            'order_substitution_expiry' => $this->created_at->addMinutes(config('settings.order_cancel_duration')),
            'created_at' => $this->created_at,
        ];
    }
}
