<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $appends = ['timeAgo', 'likesCount', 'hasThisUserLiked', 'commentsCount'];

    public function user()
    {
        return $this->belongsTo('App\User')->orderBy('id', 'desc');
    }

    public function comments() {
        return $this->hasMany('App\Comment');
    }

    public function getTimeAgoAttribute()
    {
        $post = Post::where('id', $this->id)->first();
        return Carbon::parse($post->CREATED_AT)->diffForHumans();
    }

    public function getLikesCountAttribute()
    {
        $likes = Like::where('post_id', $this->id)->get();
        return count($likes);
    }

    public function getCommentsCountAttribute()
    {
        $comments = Comment::where('post_id', $this->id)->get();
        return count($comments);
    }

    public function getHasThisUserLikedAttribute()
    {
        $like = Like::where([
            'post_id' => $this->id,
            'user_id' => auth()->user()->id
        ])->first();

        if ($like) return true;
        return false;
    }

    public function canUserAccess($id)
    {
        if ($this->user->profile_visibility === 'PUBLIC'
            || $this->user->isFriend($id)
            || $this->user->id === $id
        )
            return true;
        return false;
    }
}
