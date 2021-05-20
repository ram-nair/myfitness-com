<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ReportProblem extends Model
{
    use Uuids;

    protected $table = 'report_problem';

    protected $fillable = [
        'name', 'description', 'store_id', 'user_id', 'order_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
