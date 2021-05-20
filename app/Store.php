<?php

namespace App;

use App\BusinessType;
use App\Product;
use App\StoreContactDetails;
use App\StoreManagerContactDetails;
use App\StoreSuperVisorContactDetails;
use App\Vendor;
use Carbon\Carbon;
use Helper;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use Spatie\Activitylog\Traits\LogsActivity;

class Store extends Authenticatable
{
    use Notifiable, Uuids, HasRoles, SoftDeletes, LogsActivity;

    protected $guard_name = 'store';

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
        'start_at' => 'time',
        'end_at' => 'time',
    ];

    protected static $logName = 'store'; //Should be short and identical
    protected static $logFillable  = true;
    protected static $logOnlyDirty = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'business_type_id', 'business_type_category_id', 'service_type',
        'name', 'email', 'password', 'status', 'image', 'mobile', 'description', 'store_timing',
        'location', 'credit_card', 'cash_accept', 'bring_card', 'featured', 'speed', 'accuracy',
        'min_order_amount', 'latitude', 'longitude', 'vendor_id', 'by_user_id', 'by_user_type',
        'time_to_deliver', 'on_my_location_charge', 'in_store', 'my_location',
        'sap_id', 'service_charge', 'payment_charge', 'payment_charge_store_dividend',
        'payment_charge_provis_dividend', 'contract_start_date', 'contract_end_date', 'sla', 'start_at', 'end_at', 'active', 'male', 'female', 'any'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function getImageAttribute($value)
    {
        return ($value != null) ? Helper::imageUrl('store', $value) : null;
    }

    public function businessType()
    {
        return $this->belongsTo(BusinessType::class, 'business_type_id', 'id');
    }
    public function businessTypeCategory()
    {
        return $this->belongsTo(BusinessTypeCategory::class, 'business_type_category_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories', 'store_id', 'product_id');
    }

    public function storeProducts()
    {
        return $this->belongsToMany(Product::class, 'product_stores', 'store_id', 'product_id');
    }

    public function serviceStoreProducts()
    {
        return $this->belongsToMany(ServiceProducts::class, 'service_store_products', 'store_id', 'product_id');
    }

    public function storeVendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function storeContacts()
    {
        return $this->hasMany(StoreContactDetails::class, 'store_id', 'id');
    }

    public function storeSupervisorContacts()
    {
        return $this->hasMany(StoreSuperVisorContactDetails::class, 'store_id', 'id');
    }

    public function storeManagerContacts()
    {
        return $this->hasMany(StoreManagerContactDetails::class, 'store_id', 'id');
    }

    public function setStartAtAttribute($value)
    {
        if (empty($value)) {
            return false;
        }
        $this->attributes['start_at'] = Carbon::createFromFormat("h:i A", $value)->format('H:i:s');
    }

    public function getStartAtAttribute($value)
    {
        if (empty($value)) {
            return false;
        }
        return Carbon::parse($value)->format('h:i A');
    }

    public function setEndAtAttribute($value)
    {
        if (empty($value)) {
            return false;
        }
        $this->attributes['end_at'] = Carbon::createFromFormat("h:i A", $value)->format('H:i:s');
    }

    public function getEndAtAttribute($value)
    {
        if (empty($value)) {
            return false;
        }
        return Carbon::parse($value)->format('h:i A');
    }

    public function getStoreHoursAttribute()
    {
        $start = $this->start_at;
        $end = $this->end_at;
        if ($start && $end) {
            // if (substr($start, -2) === substr($end, -2)) {
            //     return substr($start, 0, -2) . "- " . substr($end, 0, -2) . "" . substr($start, -2);
            // }
            return $start . " - " . $end;
        }
        return null;
    }

    public function getOrderCount()
    {
        return $this->hasMany(Order::class, 'store_id', 'id');

    }
    public function boundaries()
    {
        return $this->hasMany(Boundary::class, 'store_id', 'id');
    }

    public function getStoreNameAttribute()
    {
        return $this->store_fullname ?? $this->name;
    }
}
