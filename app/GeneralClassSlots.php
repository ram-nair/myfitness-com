<?php

namespace App;

use App\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralClassSlots extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'general_class_slots';

    protected $guarded = ['id'];

    protected $casts = [
        'slot_date' => 'date',
        'start_at' => 'time',
        'end_at' => 'time',
        'booking_data' => 'array',
        'status' => 'boolean'
    ];

    public function setStartAtAttribute($value)
    {
        if (empty($value)) {
            return false;
        }
        $this->attributes['start_at'] = Carbon::createFromFormat("h:i A", $value)->format('H:i:s');
    }

    public function getStartAtAttribute($value)
    {
        if (empty($value)) {
            return false;
        }
        return Carbon::parse($value)->format('h:i A');
    }

    public function setEndAtAttribute($value)
    {
        if (empty($value)) {
            return false;
        }
        $this->attributes['end_at'] = Carbon::createFromFormat("h:i A", $value)->format('H:i:s');
    }

    public function getEndAtAttribute($value)
    {
        if (empty($value)) {
            return false;
        }
        return Carbon::parse($value)->format('h:i A');
    }

    public function getSlotNameAttribute()
    {
        $start = $this->start_at;
        $end = $this->end_at;
        if ($start && $end) {
            // if (substr($start, -2) === substr($end, -2)) {
            //     return substr($start, 0, -2) . "- " . substr($end, 0, -2) . "" . substr($start, -2);
            // }
            return $start . " - " . $end;
        }
        return null;
    }

    public function attendees()
    {
        return $this->hasMany(GeneralClassAttendees::class, 'slot_id', 'id');
    }
}
