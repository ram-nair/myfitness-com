<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    protected $connection = 'offlineclass';

    protected $table = 'view_amenity_master';

    // protected $primaryKey = 'ClientID';

    // public function slots()
    // {
    //     return $this->hasMany(AminitySlots::class, 'id_amenity', 'id_community_asset');
    // }
}
