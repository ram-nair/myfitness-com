<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceBanner extends Model
{
    use SoftDeletes, Uuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'service_banner';

    protected $fillable = [
        'name', 'status','in_category','in_product','url', 'image', 'service_type', 'description', 'by_user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    public function store()
    {
        return $this->belongsToMany(Store::class, 'service_banner_stores', 'banner_id', 'store_id')->withTimestamps();
    }

    public function getImageAttribute($value)
    {
        return ($value != null) ? Helper::imageUrl('serviceBanner', $value) : null;
    }

}
