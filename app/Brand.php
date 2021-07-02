<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class Brand extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    

    protected $table = 'brands';

    protected $fillable = [
        'name', 'image','description','status',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

   

    
}
