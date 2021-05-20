<?php

namespace App;

use App\StoreProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Cart extends Model
{
    use Uuids, SoftDeletes;
    
    protected $guarded = ['id'];

    protected $table = 'carts';

    public function storeItem()
    {
        return $this->belongsTo(StoreProduct::class, 'product_id', 'product_id');
    }
}
