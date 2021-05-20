<?php

namespace App;

use App\Store;
use App\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class StoreProduct extends Model
{
    use SoftDeletes, Uuids, LogsActivity;
    
    protected $table = 'product_stores';
    
    protected $guarded = ['id'];

    protected static $logName = 'store_product'; 
    protected static $logAttributes = ['product_id', 'store_id', 'unit_price', 'ask_price', 'stock', 'price_approved', 'out_of_stock', 'quantity_per_person'];
    protected static $logOnlyDirty = true;

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
}
