<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model 
{
    protected $table = 'subscribers';

    protected $fillable = [
        'email', 
    ];

    
}
