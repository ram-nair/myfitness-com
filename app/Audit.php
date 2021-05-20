<?php

namespace App;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    

    
    protected $table = 'audits';

    protected $guarded = ['id'];
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array'
        
    ];

    public function getAuditableTypeAttribute($value) {
         
       return str_replace('App\\',"",$value);


    }
     public function brand() {

        return $this->belongsTo('App\Brand', 'auditable_id', 'id');
           /* ->where('bank_type','=','RRB')->where('br_status','=','Active');
        return $this->hasMany(Audit::class);*/
    }

  
}
