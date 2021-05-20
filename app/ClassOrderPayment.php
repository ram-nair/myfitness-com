<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Uuids;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassOrderPayment extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'class_order_payments';

    protected $guarded = ['id'];

    protected $casts = [
        'full_response' => 'array',
    ];

    public function classOrder()
    {
        return $this->belongsTo(ClassOrder::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
