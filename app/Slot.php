<?php

namespace App;

use App\Uuids;
use App\BusinessType;
use App\BusinessTypeCategory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slot extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'slots';

    protected $guard = 'slot';

    protected $fillable = [
        'name', 'by_user_id', 'start_at', 'end_at', 'business_type_id', 'business_type_category_id'
    ];

    protected $casts = [
        'start_at' => 'time',
        'end_at' => 'time',
    ];

    public function businessType()
    {
        return $this->belongsTo(BusinessType::class, 'business_type_id', 'id');
    }

    public function businessTypeCategory()
    {
        return $this->belongsTo(BusinessTypeCategory::class, 'business_type_category_id', 'id');
    }

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
}
