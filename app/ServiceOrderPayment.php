<?php

namespace App;

use App\ServiceOrder;
use App\User;
use App\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ServiceOrderPayment extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'service_order_payments';

    protected $guarded = ['id'];

    protected $casts = [
        'full_response' => 'array',
    ];

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
