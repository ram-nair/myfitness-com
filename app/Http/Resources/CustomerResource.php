<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    protected $token;

    public function token($value){
        $this->token = $value;
        return $this;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "token" => $this->token,
            "provis_user_id" => $this->provis_user_id,
            "email" => $this->email,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "address" => $this->address,
            "phone" => $this->phone,
            "photo" => $this->photo,
            "gender" => $this->gender,
            "email_verified_at" => $this->email_verified_at,
        ];
        // return parent::toArray($request);
    }
}
