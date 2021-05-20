<?php

namespace App;

use App\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\Helper;
class VBImages extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'vlog_blog_images';

    protected $fillable = ['vb_id', 'image','cover_image','upload_type'];

    public function getCoverImageAttribute($value)
    {
        return ($value != null) ? Helper::imageUrl('vlogBlog', $value) : null;
    }
    public function getImageAttribute($value)
    {
        return ($value != null) ? $value : null;
    }
}
