<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EcommerceBanner extends Model
{
    use SoftDeletes, Uuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'ecommerce_banner';

    protected $fillable = [
        'name', 'status','in_category','in_product','url', 'image',  'description', 'by_user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    

    public function getImageAttribute($value)
    {
        return ($value != null) ? Helper::imageUrl('categorybanner', $value) : null;
    }

}
