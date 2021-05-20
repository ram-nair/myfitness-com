<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    use Uuids;

    protected $table = 'notifications';

    protected $casts = [
        'data' => "array",
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

}
