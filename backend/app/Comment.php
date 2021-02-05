<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $appends = ['timeAgo'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getTimeAgoAttribute()
    {
        $comment = Comment::where('id', $this->id)->first();
        return Carbon::parse($comment->CREATED_AT)->diffForHumans();
    }

    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}
