<?php

namespace App;

use App\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AmenitySlot extends Model
{
    use Uuids;

    protected $connection = 'offlineclass';

    protected $table = 'view_amenity_slots';

    protected $guarded = ['id'];

    protected $casts = [
        'slot_date' => 'date',
    ];

    public function getStartAtAttribute($value)
    {
        return Carbon::parse($value)->format('h:i A');
    }

    public function getEndAtAttribute($value)
    {
        return Carbon::parse($value)->format('h:i A');
    }

    public function booked()
    {
        return $this->hasMany(AmenityBookedSlots::class, 'id_slot', 'id_slot');
    }
}
