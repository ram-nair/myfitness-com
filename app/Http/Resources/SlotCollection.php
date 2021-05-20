<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SlotCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $slotsResponse = null;
        if(!$this->collection->isEmpty()){
            $slots = SlotResource::collection($this->collection)->collection->groupBy('days');
            foreach($slots as $key => $slot){
                $slotsResponse[] = ['date' => $key, 'slots' => $slot];
            }
        }
        return [
            'statusCode' => 200,
            'message' => trans('api.slots.list'),
            'data' => $slotsResponse,
        ];
    }
}
