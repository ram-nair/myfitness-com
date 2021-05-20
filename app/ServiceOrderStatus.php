<?php

namespace App;

use App\ServiceOrder;
use App\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ServiceOrderStatus extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'service_order_status';

    protected $guarded = ['id'];

    public function orderStatus()
    {
        return $this->hasMany(ServiceOrder::class);
    }
}
