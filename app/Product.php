<?php

namespace App;

use App\Brand;
use App\Store;
use App\Uuids;
use App\Category;
use App\ProductImage;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use SoftDeletes, Uuids, LogsActivity;
    /**
     * @var string
     */
    protected $table = 'products';

    /**
     * @var array
     */
   /* protected $fillable = [
        'brand_id', 'sku', 'name', 'description', 'quantity', 'unit', 'unit_price', 'status', 'featured', 'category_id', 'sub_category_id'
    ];*/


    protected $fillable = ['brand_id','sku','user_id','category_id', 'sub_category_id', 'child_category_id','quantity',
    'name', 'size','size_qty','size_price', 'color', 'description','unit_price','tax','short_description',
    'discount_price','in_stock','status','hot_deal','hot_sale','colors','product_condition','meta_title',
    'meta_tag','featured','meta_description','discount_start_date','tags','discount_end_date','is_discount'];

    protected static $logName = 'product'; //Should be short and identical
    protected static $logFillable  = true;
    protected static $logOnlyDirty = true;

    /**
     * @var array
     */
    protected $casts = [
        'brand_id'  =>  'integer',
        'status'    =>  'boolean',
        'featured'  =>  'boolean',
    ];

    /**
     * @param $value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value,'-');
    }
   

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id')->where('full', '!=', '');
    }

    // public function categories()
    // {
    //     return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    // }
    public function mainCategory()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'sub_category_id', 'id');
    }

    public function childcategory()
    {
        return $this->belongsTo('App\Childcategory');
    }

   /* public function storeProducts()
    {
        return $this->belongsToMany(Store::class, 'product_stores', 'product_id', 'store_id');
    }*/
/* FOR FUTURE ACTION ON 20-05-2021 

public function galleries()
    {
        return $this->hasMany('App\Models\Gallery');
    }

    public function ratings()
    {
        return $this->hasMany('App\Models\Rating');
    }

    public function wishlists()
    {
        return $this->hasMany('App\Models\Wishlist');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function clicks()
    {
        return $this->hasMany('App\Models\ProductClick');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function getSizeAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }   

    public function getSizeQtyAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }   

    public function getSizePriceAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }   

    public function getColorAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }  

    public function getTagsAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }  

    public function getMetaTagAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }  

    public function getFeaturesAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }  

    public function getColorsAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }  

    public function getLicenseAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',,', $value);
    }  

    public function getLicenseQtyAttribute($value)
    {
        if($value == null)
        {
            return '';
        }
        return explode(',', $value);
    }  


    public function vendorPrice() {
        $gs = Generalsetting::findOrFail(1);
        $price = $this->price;
        $tax = $this->tax;
        if($this->user_id != 0){
        $price = $this->price +$this->price *( $tax/100) + ($this->price/100) * $this->admin_commission;
        } else{
         $price = $this->price +$this->price *( $tax/100);
        }
        return $price;
    }

    public function vendorSizePrice() {
        $gs = Generalsetting::findOrFail(1);
        $price = $this->price;
        $tax = $this->tax;
        if($this->user_id != 0){
         $price = $this->price + $this->price *( $tax/100) +($this->price/100) * $this->admin_commission;
        }
        if(!empty($this->size)){
            $price += $this->size_price[0]+$this->size_price[0] *( $tax/100);
        }            
        return $price;
    }


    public  function setCurrency() {
        $gs = Generalsetting::findOrFail(1);
        $price = $this->price;
        $tax = $this->tax;
        $price= $price + $price*( $tax/100);
        if (Session::has('currency')) 
        {
            $curr = Currency::find(Session::get('currency'));
        }
        else
        {
            $curr = Currency::where('is_default','=',1)->first();
        }
        $price = round($price * $curr->value,2);
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $curr->sign.$price;
        }
    }


    public function showPrice() {
        $gs = Generalsetting::findOrFail(1);
        $tax = $this->tax;
        $price = $this->price + $this->price *( $tax/100);
        
       // $price= $price + $price*( $tax/100);
        if($this->user_id != 0){
          $price = $this->price + $this->price *( $tax/100) +($this->price/100) * $this->admin_commission;
        }
        if(!empty($this->size)){
            $price += $this->size_price[0];
        }
        if (Session::has('currency')) 
        {
            $curr = Currency::find(Session::get('currency'));
        }
        else
        {
            $curr = Currency::where('is_default','=',1)->first();
        }
        $price = round($price * $curr->value,2);
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $curr->sign.$price;
        }
    }
     public function showOrginalPrice() {
        $gs = Generalsetting::findOrFail(1);
        $price = $this->price;
        
       // $price= $price + $price*( $tax/100);
        if($this->user_id != 0){
          $price = $this->price +($this->price/100) * $this->admin_commission;
        }
        if(!empty($this->size)){
            $price += $this->size_price[0];
        }
        if (Session::has('currency')) 
        {
            $curr = Currency::find(Session::get('currency'));
        }
        else
        {
            $curr = Currency::where('is_default','=',1)->first();
        }
        $price = round($price * $curr->value,2);
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $curr->sign.$price;
        }
    }
   
    
     public function showTax() {
        $gs = Generalsetting::findOrFail(1);
        $tax = $this->tax;
        $price=0;
        if($this->tax){
          $price =$this->price *( $tax/100);
        }
      if (Session::has('currency')) 
        {
            $curr = Currency::find(Session::get('currency'));
        }
        else
        {
            $curr = Currency::where('is_default','=',1)->first();
        }
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $curr->sign.$price;
        }
    }
    
    
    

    public function showPreviousPrice() {
        $gs = Generalsetting::findOrFail(1);
         $tax = $this->tax;
        $price = $this->previous_price + $this->previous_price *( $tax/100);
       
        if($this->user_id != 0){
        $price = $this->previous_price + $this->previous_price *( $tax/100)+($this->previous_price/100) * $this->admin_commission ;
        }
        
        if(!empty($this->size)){
            $price += $this->size_price[0];
        }
        if (Session::has('currency')) 
        {
            $curr = Currency::find(Session::get('currency'));
        }
        else
        {
            $curr = Currency::where('is_default','=',1)->first();
        }
        $price = round($price * $curr->value,2);
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $curr->sign.$price;
        }
    }

    public static function convertPrice($price) {
        $gs = Generalsetting::findOrFail(1);
        if (Session::has('currency')) 
        {
            $curr = Currency::find(Session::get('currency'));
        }
        else
        {
            $curr = Currency::where('is_default','=',1)->first();
        }
        $price = round($price * $curr->value,2);
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $curr->sign.$price;
        }
    }

    public static function vendorConvertPrice($price) {
        $gs = Generalsetting::findOrFail(1);
          
        $curr = Currency::where('is_default','=',1)->first();
        $price = round($price * $curr->value,2);
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $curr->sign.$price;
        }
    }

    public static function convertPreviousPrice($price) {
        $gs = Generalsetting::findOrFail(1);
        if (Session::has('currency')) 
        {
            $curr = Currency::find(Session::get('currency'));
        }
        else
        {
            $curr = Currency::where('is_default','=',1)->first();
        }
        $price = round($price * $curr->value,2);
        if($gs->currency_format == 0){
            return $curr->sign.$price;
        }
        else{
            return $curr->sign.$price;
        }
    }

    public function showName() {
        $name = strlen($this->name) > 55 ? substr($this->name,0,55).'...' : $this->name;
        return $name;
    }
     public  static function Iscod() {
          return $this->is_cod;
    }

    public static function showTags() {
        $tags = null;
        $tagz = '';
        $name = Product::where('status','=',1)->pluck('tags')->toArray();
        foreach($name as $nm)
        {
            if(!empty($nm))
            {
                foreach($nm as $n)
                {
                    $tagz .= $n.',';
                }
            }
        }
        $tags = array_unique(explode(',',$tagz));
        return $tags;
    }

    public static function shippingCost($user_id, $pincode) {

        $user = \DB::table('pincode_groups')
        ->where('user_id', $user_id)
        ->whereRaw("pincodes REGEXP '[[:<:]]". $pincode ."[[:>:]]'")->get()
        ->first();

        if (!empty($user) && $pincode!=0) {
            return $user->shipping_cost;
        } else {
            return 0;
        }
    }


public function offerPercPrice() {
         $gs = Generalsetting::findOrFail(1);
         $tax = $this->tax;
         $price = $this->previous_price + $this->previous_price *( $tax/100);
       
        if($this->user_id != 0){
         $price = $this->previous_price + $this->previous_price *( $tax/100)+($this->previous_price/100) * $this->admin_commission ;
        }
        
        if(!empty($this->size)){
            $price += $this->size_price[0];
        }
        
        if (Session::has('currency')) 
        {
            $curr = Currency::find(Session::get('currency'));
        }
        else
        {
            $curr = Currency::where('is_default','=',1)->first();
        }
        $previousprice = round($price * $curr->value,2);

        //orginal price


        $prices = $this->price;
        
       // $price= $price + $price*( $tax/100);
        if($this->user_id != 0){
          $prices = $this->price +($this->price/100) * $this->admin_commission;
        }
        if(!empty($this->size)){
            $prices += $this->size_price[0];
        }
        
         $Orginalprice = round($prices * $curr->value,2);

         $perc=  (($previousprice -$Orginalprice)/$previousprice )*100;
         return round($perc,2);
               
    }

    END HERE */





}
