<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
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
            "category_id" => $this->category_id,
            "author_id " => $this->author_id,
            "title" => $this->title,
            "description" => $this->description ,
            "blog_type" => $this->blog_type ,
            "reading_minute" => $this->reading_minute ,
            "status" => (bool) $this->status,
            "category" => $this->category,
            "author" => $this->author,
            "images" => ($this->images->count() > 0) ? new BlogImageCollection($this->images) : null,

        ];
        // return parent::toArray($request);
    }
}
