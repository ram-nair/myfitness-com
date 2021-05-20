<?php

namespace App;

use App\Slot;
use App\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceStoreSlot extends Model
{
    use Uuids, SoftDeletes;
    
    protected $table = 'service_store_slots';

    protected $fillable = ['store_id', 'slot_id'];

    public function slots()
    {
        return $this->belongsTo(Slot::class, 'slot_id');
    }

    public function dropSlot()
    {
        return $this->belongsTo(Slot::class, 'slot_id');
    }
}
