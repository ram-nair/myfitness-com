<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Uuids;

class StoreManagerContactDetails extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'store_managers';

    protected $fillable = ['email', 'phone', 'name'];
}
