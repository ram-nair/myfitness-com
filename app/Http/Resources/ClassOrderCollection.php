<?php

namespace App\Http\Resources;

use App\Http\Resources\ClassOrderResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClassOrderCollection extends ResourceCollection
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
            'message' => trans('api.classes.list'),
            'data' => $this->collection->isEmpty() ? null : ClassOrderResource::collection($this->collection),
        ];
    }
}
