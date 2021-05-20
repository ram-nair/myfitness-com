<?php

namespace App;

use App\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VBCategory extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'vlog_blog_category';

    protected $fillable = ['name', 'status', 'by_user_id'];
}
