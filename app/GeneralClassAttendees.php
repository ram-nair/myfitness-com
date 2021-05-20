<?php

namespace App;

use App\Uuids;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralClassAttendees extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'general_class_attendees';

    protected $guarded = ['id'];

    protected $casts = [
        'enrolled_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
