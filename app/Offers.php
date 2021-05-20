<?php

namespace App;

use App\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\Helper;
class Offers extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'offer';

    protected $fillable = ['title','brand_id','description','image','cover_image','redeem_text','start_date','end_date','status','by_user_id'];
    public function getCoverImageAttribute($value)
    {
        return ($value != null) ? Helper::imageUrl('offers', $value) : null;
    }
    public function getImageAttribute($value)
    {
        return ($value != null) ? Helper::imageUrl('offers', $value) : null;
    }
    public function brandDetail()
    {
        return $this->belongsTo(OfferBrand::class, 'brand_id', 'id');
    }
}
