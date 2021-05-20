<?php

namespace App\Http\Resources;

use App\Http\Resources\StoreProductResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StoreProductPlainCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {   
      return $this->collection->isEmpty() ? null : StoreProductResource::collection($this->collection);
    }
    
}
