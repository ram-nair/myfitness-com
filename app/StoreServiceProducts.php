<?php

namespace App;

use App\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\ServiceProducts;

class StoreServiceProducts extends Model
{
    use SoftDeletes, Uuids;
    
    protected $table = 'service_store_products';
    
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(ServiceProducts::class, 'product_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
}
