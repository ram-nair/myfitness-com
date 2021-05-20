<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\Helper;
class BlogImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $image = null;
        if($this->upload_type == "video_url"){
            $image = $this->image;
        }else{
            $image = ($this->image != null) ? Helper::imageUrl('vlogBlog', $this->image) : null;
        }
        return [
            "id" => $this->id,
            "vb_id" => $this->vb_id,
            "image" => $image,
            "upload_type" => $this->upload_type,
            "cover_image" => $this->cover_image,
        ];
    }
}
