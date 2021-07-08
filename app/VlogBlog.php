<?php

namespace App;

use App\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class VlogBlog extends Model
{
    use SoftDeletes, Uuids;

    protected $table = 'vlog_blog';

    protected $fillable = ['author_id','category_id','image','banner_image','title','description','blog_type','reading_minute','status','by_user_id'];
    public function author()
    {
        return $this->belongsTo(VBAuthor::class, 'author_id', 'id');
    }
    public function category()
    {
        return $this->belongsTo(VBCategory::class, 'category_id', 'id');
    }
    public function getFollowingAuthor()
    {
        return $this->hasMany(VBAuthorFollower::class, 'author_id', 'author_id');
    }
    public function images()
    {
        return $this->hasMany(VBImages::class, 'vb_id', 'id');
    }
    public function setTitleAttribute($value){
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value,'-');
     }

}
