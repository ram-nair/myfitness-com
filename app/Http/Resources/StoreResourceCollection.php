<?php

namespace App\Http\Resources;

use App\Http\Resources\StoreResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StoreResourceCollection extends ResourceCollection
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
            'message' => trans('api.stores.list'),
            'data' => $this->collection->isEmpty() ? null : StoreResource::collection($this->collection),
            // 'links' => null
        ];
    }
}
