<?php

namespace App;

use App\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gift extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'gifts';

    protected $guard = 'gift';

    protected $fillable = [
        'code', 'balance_amt','expire_at','status','is_redeem','image',
    ];

   
}
