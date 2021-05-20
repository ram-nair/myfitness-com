<?php

namespace App;

use App\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\Helper;
class OfferBrandFollowers extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'brand_followers';

    protected $fillable = ['brand_id','user_id'];

}
