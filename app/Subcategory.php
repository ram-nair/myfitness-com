<?php

namespace App;

use App\Helpers\Helper;
use App\Traits\NestableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategory extends Model
{
    protected $fillable = ['category_id','name'];
    public $timestamps = false;

    public function childs()
    {
    	return $this->hasMany('App\Childcategory')->where('status','=',1);
    }

    public function category()
    {
    	return $this->belongsTo('App\Category');
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
