<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCommunity extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'community_id','user_id'
    ];

    protected $with = [
        'community'
    ];

    protected $visible = [
        'community_id','community'
    ];

    public function community(){
        return $this->belongsTo('App\Community');
    }

    public function getCommunityNameAttribute(){
        return $this->community->name;
    }

}
