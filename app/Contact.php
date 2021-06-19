<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model 
{
    protected $table = 'contacts';

    protected $fillable = [
        'email', 'name','phone',
    ];

    
}
