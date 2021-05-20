<?php

namespace App;

use App\Uuids;
use App\Category;
use App\GeneralClassSlots;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralClass extends Model
{
    use SoftDeletes, Uuids;
    /**
     * @var string
     */
    protected $table = 'general_classes';

    /**
     * @var array
     */
    protected $fillable = [
        'title', 'instructor', 'image', 'overview', 'cancelation_policy',
        'status', 'category_id','vendor_id', 'start_date', 'end_date', 'by_user_id', 'type', 'community_id', 'community_name', 'amenity_id', 'amenity_name'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status'    =>  'boolean',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    /**
     * @param $value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'general_class_packages', 'class_id', 'package_id')->withPivot('type')->withTimestamps();
    }

    public function slots()
    {
        return $this->hasMany(GeneralClassSlots::class, 'class_id', 'id');
    }

    public function getImageAttribute($value)
    {
        return ($value != null) ? Helper::imageUrl('generalClass', $value) : null;
    }
}
