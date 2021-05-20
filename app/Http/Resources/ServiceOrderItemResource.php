<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceOrderItemResource extends JsonResource
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
            'order_item_id' => $this->id,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'product_price' => round_my_number($this->product_price),
            'quantity' => (int) $this->quantity,
            'total_amount' => round_my_number($this->total_amount),
            'product_image' => $this->serviceStoreProduct->product->image ?? null,
        ];
    }
}
