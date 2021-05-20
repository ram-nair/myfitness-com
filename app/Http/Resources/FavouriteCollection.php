<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FavouriteCollection extends ResourceCollection
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
            'message' => trans('api.favourites.list'),
            'data' => $this->collection->isEmpty() ? null : FavouriteResource::collection($this->collection),
        ];
    }
}
