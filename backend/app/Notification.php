<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $appends = ['timeAgo', 'formattedDate'];
    protected $attributes = [
        'read_at' => null
    ];

    public function getTimeAgoAttribute()
    {
        $notification = Notification::where('id', $this->id)->first();
        return Carbon::parse($notification->created_at)->diffForHumans();
    }

    public function getFormattedDateAttribute()
    {
        $notification = Notification::where('id', $this->id)->first();
        return Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->format('j M, H:i');
    }
}
