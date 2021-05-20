<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use SoftDeletes, Uuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'status', 'image', 'url', 'description', 'by_user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    public function getImageAttribute($value)
    {
        return ($value != null) ? Helper::imageUrl('banner', $value) : null;
    }
}
