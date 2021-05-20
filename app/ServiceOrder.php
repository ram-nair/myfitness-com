<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Uuids;
use App\UserAddress;
use App\Slot;
use App\ServiceProducts;
use App\ServiceOrderItem;
use App\StoreDaysSlot;
use App\ServiceStoreSlot;

class ServiceOrder extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'service_orders';

    public function cart()
    {
        return $this->belongsTo(ServiceCart::class, 'cart_id', 'id');
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'address_id', 'id');
    }

    public function slot()
    {
        return $this->belongsTo(Slot::class, 'slot_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function orderItem()
    {
        return $this->hasMany(ServiceOrderItem::class, 'order_id', 'id');
    }

    public function storeDaySlot()
    {
        return $this->belongsTo(StoreDaysSlot::class, 'pick_up_slot_id', 'id');
    }

    public function storeDropSlot()
    {
        return $this->belongsTo(ServiceStoreSlot::class, 'drop_off_slot_id', 'id');
    }

    public function serviceOrderStatusHistory()
    {
        return $this->hasMany(ServiceOrderStatus::class, 'order_id', 'id');
    }
}
