<?php

namespace App;

use App\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\Helper;
class VBAuthor extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'vlog_blog_author';

    protected $fillable = ['vendor_name', 'email','phone_number','cover_image','profile_image','description','status','by_user_id'];
    public function getCoverImageAttribute($value)
    {
        return ($value != null) ? Helper::imageUrl('blogAuthor', $value) : null;
    }
    public function getProfileImageAttribute($value)
    {
        return ($value != null) ? Helper::imageUrl('blogAuthor', $value) : null;
    }
}
