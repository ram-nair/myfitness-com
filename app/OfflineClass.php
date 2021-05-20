<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfflineClass extends Model
{
    protected $connection = 'offlineclass';

    protected $table = 'view_amenity_master';

    // protected $primaryKey = 'ClientID';
}
