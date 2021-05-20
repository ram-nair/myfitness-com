<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Uuids;

class ClassReportProblem extends Model
{
    use Uuids;

    protected $table = 'class_report_problem';

    protected $fillable = [
        'name', 'description', 'user_id', 'order_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
