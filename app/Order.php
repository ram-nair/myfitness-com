<?php

namespace App;

use App\Cart;
use App\OrderItem;
use App\OrderPayment;
use App\Store;
use App\User;
use App\UserAddress;
use App\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'orders';

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'id');
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'address_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function orderItem()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function payments()
    {
        return $this->hasMany(OrderPayment::class, 'order_id', 'id');
    }

    public function orderStatusHistory()
    {
        return $this->hasMany(OrderStatus::class, 'order_id', 'id');
    }
}
