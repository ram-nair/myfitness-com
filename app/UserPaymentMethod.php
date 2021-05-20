<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPaymentMethod extends Model {

    use Uuids;

    protected $table = 'user_payment_methods';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
