<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserActiveToken extends Model {

    use Uuids;

    protected $table = 'user_active_tokens';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = ['user_id', 'token'];

}
