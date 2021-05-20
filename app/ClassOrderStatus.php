<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassOrderStatus extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'class_order_statuses';

    protected $guarded = ['id'];
}
