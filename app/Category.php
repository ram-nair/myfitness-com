<?php

namespace App;

use App\Helpers\Helper;
use App\Traits\NestableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    //
    
    use SoftDeletes, NestableTrait;
    //
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
        'parent_cat_id',
        'image','featured','status','banner_image','slug'
    ];


    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = $this->createSlug($value);
    }
   

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    
    private function createSlug($name)
    {
        if (static::whereSlug($slug = Str::slug($name))->exists()) {

            $max = static::whereName($name)->latest('id')->skip(1)->value('slug');

            if (isset($max[-1]) && is_numeric($max[-1])) {

                return preg_replace_callback('/(\d+)$/', function ($mathces) {

                    return $mathces[1] + 1;
                }, $max);
            }
            return "{$slug}-2";
        }
        return $slug;
    }
   
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_cat_id');
    }

    public function catHasChild()
    {
        return $this->hasMany(Category::class, 'parent_cat_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
/*
    public function getImageAttribute($value)
    {
        return ($value != null) ? Helper::imageUrl('category', $value) : null;
    }*/
}
