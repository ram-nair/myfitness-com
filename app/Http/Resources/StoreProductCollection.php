<?php

namespace App\Http\Resources;

use App\Http\Resources\StoreProductResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StoreProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {   
        return [
            'statusCode' => 200,
            'message' => trans('api.products.list'),
            'data' => $this->collection->isEmpty() ? null : StoreProductResource::collection($this->collection),
        ];
    }
    
}
