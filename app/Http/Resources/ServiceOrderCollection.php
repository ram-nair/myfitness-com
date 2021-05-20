<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\ServiceOrderResource;

class ServiceOrderCollection extends ResourceCollection
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
            'message' => trans('services.order.list'),
            'data' => $this->collection->isEmpty() ? null : ServiceOrderResource::collection($this->collection),
        ];
    }
}
