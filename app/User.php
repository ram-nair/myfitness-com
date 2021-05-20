<?php

namespace App;

use App\Uuids;
use App\UserAddress;
use App\UserCommunity;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {

    use HasApiTokens, Uuids, Notifiable, HasRoles;

    protected $guard_name = 'customer';

    protected $primaryKey = 'id';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','id_login',
        'provis_user_id','first_name','last_name',
        'phone','photo','gender','precinct',
        'units','community_id'
    ];

    protected $with = [
        'community'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'precinct' => 'array',
        'units' => 'array'
    ];

    public function paymentMethods() {
        return $this->hasMany(UserPaymentMethod::class, 'user_id', 'id');
    }

    public function sendPasswordResetNotification($token) {
        $this->notify(new Notifications\UserResetPassword($token));
    }

    public function community(){
        return $this->belongsToMany(Community::class, 'user_communities', 'user_id', 'community_id');
    }

    public function address(){
        //dd(123);
        return $this->hasMany(UserAddress::class);
    }

    
    public function orderCount(){
        return  $this->hasMany(Order::class, 'user_id', 'id');

    }
    public function orders()
    {
        return $this->hasMany('App\Order');
    }
   
}
