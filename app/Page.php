<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['title', 'slug', 'description','meta_tag','meta_description'];
    public $timestamps = false;

    public function setSlugAttribute(){
        $this->attributes['slug'] = str_slug($this->title , "-");
    }

}
