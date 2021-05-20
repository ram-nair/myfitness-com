<?php

namespace App;

use App\Store;
use App\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreFavourite extends Model
{
    use Uuids, SoftDeletes;

    protected $table = 'store_favourites';

    protected $guarded = ['id'];

    public function store(){
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
}
