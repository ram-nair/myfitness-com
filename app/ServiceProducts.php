<?php

namespace App;

use App\Uuids;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ServiceProducts extends Model
{
    use SoftDeletes, Uuids, LogsActivity;

    protected $table = 'service_products';

    protected $guarded = ['id'];
    protected $fillable = [
        'name', 'description', 'image',
        'category_id', 'unit_price', 'max_person', 'max_hour','bring_material_charge','status', 'featured', 'by_user_id', 'service_type'
    ];

    protected static $logName = 'service_products'; 
    protected static $logFillable  = true;
    protected static $logOnlyDirty = true;

    public function mainCategory()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    // public function storeProducts()
    // {
    //     return $this->belongsToMany(Store::class, 'product_stores', 'product_id', 'store_id');
    // }

    public function getImageAttribute($value)
    {
        return ($value != NULL) ? Helper::imageUrl('serviceProduct', $value) : NULL;
    }
}
