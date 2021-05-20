<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\AdminResetPasswordNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
class Admin extends Authenticatable {



    use Notifiable;
    use HasRoles;
    use SoftDeletes;
    use Uuids;

    protected $guard_name = 'admin';
    protected $primaryKey = 'id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_id', 'name', 'email', 'password', 'status', 'type', 'last_login'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setPasswordAttribute($password) {
        $this->attributes['password'] = bcrypt($password);
    }

    public function sendPasswordResetNotification($token) {
        $this->notify(new AdminResetPasswordNotification($token));
    }

    public function audits() {

        return $this->hasMany('App\Audit', 'admin_id', 'id')->orderBy('created_at', 'DESC');;
           /* ->where('bank_type','=','RRB')->where('br_status','=','Active');
        return $this->hasMany(Audit::class);*/
    }
    

}
