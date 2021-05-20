<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Uuids;
use App\Order;
use App\StoreProduct;

class OrderItem extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id', 'product_id', 'product_name', 'product_price','quantity', 'total_amount'
    ];

    public function orderItems()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function storeProduct()
    {
        return $this->belongsTo(StoreProduct::class, 'product_id', 'id')->withTrashed();
    }
}
