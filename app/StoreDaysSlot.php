<?php

namespace App;

use App\Slot;
use App\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreDaysSlot extends Model
{
    use Uuids,SoftDeletes;
    
    protected $table = 'store_days_slots';

    protected $fillable = ['store_id', 'days', 'slot_id', 'capacity'];

    public function slots()
    {
        return $this->belongsTo(Slot::class, 'slot_id');
    }
}
