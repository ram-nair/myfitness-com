<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\StoreServiceProducts;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCart extends Model
{
    use Uuids, SoftDeletes;
    
    protected $table = 'service_carts';

    protected $guarded = ['id'];

    public function storeItem()
    {
        return $this->belongsTo(StoreServiceProducts::class, 'product_id', 'product_id');
    }
}
