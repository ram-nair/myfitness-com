<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Uuids;

class StoreSuperVisorContactDetails extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'store_supervisors';

    protected $fillable = ['email', 'phone', 'name'];
}
