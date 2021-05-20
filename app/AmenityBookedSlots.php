<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AmenityBookedSlots extends Model
{
    protected $connection = 'offlineclass';

    protected $table = 'view_amenity_booked_slots';

    public function slot()
    {
        return $this->belongsTo(Aminity::class, 'id_slot', 'id_slot');
    }
}
