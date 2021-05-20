<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;

class Vendor extends Authenticatable
{
    use Notifiable, Uuids, HasRoles, LogsActivity;

    protected $guard_name = 'vendor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','status','image','mobile','description','by_user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static $logName = 'vendor'; 
    protected static $logFillable  = true;
    protected static $logOnlyDirty = true;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */   
    public function setPasswordAttribute($password) {
        $this->attributes['password'] = bcrypt($password);
    }
    
    public function sendPasswordResetNotification($token) {
        //$this->notify(new Notifications\UserResetPassword($token));
    }

    public function getImageAttribute($value) {
        return ($value != NULL) ? Helper::imageUrl('vendor', $value) : NULL;
    }

}
