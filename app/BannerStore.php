<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\EcommerceBanner;
use Illuminate\Database\Eloquent\SoftDeletes;

class BannerStore extends Model
{
    use Uuids, SoftDeletes;
    
    protected $table = 'banner_stores';

    protected $guarded = ['id'];
}
