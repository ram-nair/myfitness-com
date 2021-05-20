<?php

namespace App;

use App\Helpers\Helper;
use App\Traits\NestableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'image',
    ];

    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_cat_id');
    }

    public function catHasChild($businessTypeCat = false)
    {
       /* if (!empty($businessTypeCat)) {
            return $this->hasMany(Category::class, 'parent_cat_id')->where('business_type_category_id', $businessTypeCat);
        }*/
        return $this->hasMany(Category::class, 'parent_cat_id');
    }

    // public function parent()
    // {
    //     return $this->belongsTo(BusinessTypeCategory::class, 'parent_id');
    // }

    // public function children()
    // {
    //     return $this->hasMany(BusinessTypeCategory::class, 'parent_id');
    // }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function getImageAttribute($value)
    {
        return ($value != null) ? Helper::imageUrl('category', $value) : null;
    }
}
