<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $vat_amount = ($this->price + $this->service_charge) * ((float)$this->vat / 100);
        $total_amount = $vat_amount + ($this->price + $this->service_charge);
        return [
            "package_id" => $this->id,
            "name" => $this->name,
            "price" => round_my_number($this->price),
            "no_of_sessions" => $this->no_of_sessions,
            "service_fee" => round_my_number($this->service_charge) ?? null,
            "vat" => (float)$this->vat ?? null,
            'vat_amount' => round_my_number($vat_amount) ?? 0,
            'total_amount' => round_my_number($total_amount) ?? 0,
        ];
    }
}
