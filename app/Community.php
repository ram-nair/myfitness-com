<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    //
    protected $fillable = [
        'name','banner_url','id_community'
    ];

    protected $visible = [
        'name','banner_url','id_community'
    ];
}
