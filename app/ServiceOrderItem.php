<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Uuids;
use App\ServiceOrder;
use App\ServiceProducts;
use App\StoreServiceProducts;

class ServiceOrderItem extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'service_order_items';

    protected $fillable = [
    'order_id', 'product_id', 'product_name', 'product_price', 'quantity', 'total_amount'
  ];

    public function orderItems()
    {
        return $this->belongsTo(ServiceOrder::class, 'order_id', 'id');
    }

    public function serviceStoreProduct()
    {
        return $this->belongsTo(StoreServiceProducts::class, 'product_id', 'id')->withTrashed();
    }
}
