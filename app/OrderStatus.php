<?php

namespace App;

use App\Order;
use App\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'order_status';

    protected $guarded = ['id'];
}
