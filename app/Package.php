<?php

namespace App;

use App\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'packages';

    protected $fillable = ['name', 'price', 'service_charge', 'vat', 'no_of_sessions', 'by_user_id'];
}
