<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Page extends Model
{
    protected $fillable = ['title', 'description','meta_tag','meta_description'];
    public $timestamps = false;

    public function setTitleAttribute($value){
    $this->attributes['title'] = $value;
    $this->attributes['slug'] = Str::slug($value,'-');

   }




}
