<?php

namespace App;

use App\Helpers\Helper;
use App\Traits\NestableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Childcategory extends Model
{
    protected $fillable = ['subcategory_id','name'];
    public $timestamps = false;

    public function subcategory()
    {
    	return $this->belongsTo('App\Subcategory');
    }

    public function products()
    {
        return $this->hasMany('App\Product');
    }
    
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = str_replace(' ', '-', $value);
    }

}
