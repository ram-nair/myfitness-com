<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;

class Productreview extends Model
{
    public $table = 'productreviews';
	public $fillable = ['name','email','title','message','productid','rate'];
	
	public function product()
    {
        return $this->belongsTo('App\Product','productid','id');
    }
    
}
