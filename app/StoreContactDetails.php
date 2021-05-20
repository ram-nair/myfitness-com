<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Uuids;

class StoreContactDetails extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'store_contact_details';

    protected $fillable = ['email', 'phone'];
}
