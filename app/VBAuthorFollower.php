<?php

namespace App;

use App\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VBAuthorFollower extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'vlog_blog_author_followers';

    protected $fillable = ['author_id', 'user_id'];
}
