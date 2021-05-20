<?php

namespace App;

use App\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\Helper;
class OfferBrand extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'offer_brand';

    protected $fillable = ['name','category_id','cover_image','image','working_hour','phone_number','email','working_start_hour','working_end_hour','description','location','latitude','longitude','status','by_user_id'];
    public function getCoverImageAttribute($value)
    {
        return ($value != null) ? Helper::imageUrl('offerBrand', $value) : null;
    }
    public function getImageAttribute($value)
    {
        return ($value != null) ? Helper::imageUrl('offerBrand', $value) : null;
    }
}
