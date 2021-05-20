<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Uuids;

class UserAddress extends Model
{
    use SoftDeletes, Uuids;

    protected $table = "user_addresses";

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'location',
        'apartment',
        'building_name',
        'address_name',
        'instruction'
    ];

    function user() {
        return $this->belongsTo('App\User');
    }
}
