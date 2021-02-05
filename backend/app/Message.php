<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $appends = ['timeAgo', 'formattedDate'];
    protected $attributes = [
        'read_at' => null
    ];

    public function getTimeAgoAttribute()
    {
        $message = Message::where('id', $this->id)->first();
        return Carbon::parse($message->CREATED_AT)->diffForHumans();
    }

    public function getFormattedDateAttribute()
    {
        $message = Message::where('id', $this->id)->first();
        return Carbon::createFromFormat('Y-m-d H:i:s', $message->CREATED_AT)->format('j M, H:i');
    }
}
