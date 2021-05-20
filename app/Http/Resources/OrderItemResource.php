<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Product;

class OrderItemResource extends JsonResource
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
            'quantity' => (int)$this->quantity,
            'total_amount' => round_my_number($this->total_amount),
            'quantity_label' => $this->storeProduct->product->quantity,
            'unit_label' => $this->storeProduct->product->unit ?? null,
            'category_id' => $this->storeProduct->product->category_id ?? null,
            'sub_category_id' => $this->storeProduct->product->sub_category_id ?? null,
            'product_images' =>  $this->storeProduct->product->images->count() > 0 ? new ProductImageCollection($this->storeProduct->product->images) : null,
        ];
    }
}
