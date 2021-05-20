<?php

namespace App;

use App\Uuids;
use App\ClassOrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassOrder extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'classorder';

    public function orderSlots()
    {
        return $this->hasMany(ClassOrderSlot::class, 'order_id', 'id');
    }

    public function generalClass()
    {
        return $this->belongsTo(GeneralClass::class, 'class_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(ClassOrderPayment::class, 'order_id', 'id');
    }

    public function classOrderStatusHistory()
    {
        return $this->hasMany(ClassOrderStatus::class, 'order_id', 'id');
    }
}
