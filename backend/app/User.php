<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Cashier\Billable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use Billable;

    protected $fillable = [
        'plate_number', 'email', 'password', 'phone'
    ];

    protected $hidden = [
        'password',
    ];

    protected $attributes = [
        'profile_pic' => 'default.png',
        'profile_visibility' => 'PUBLIC',
        'balance' => 0.00,
        'verified' => true,
        'verified_driver' => 'no'
    ];

    protected $appends = [
        'isOnline'
    ];

    /**
     * @inheritDoc
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @inheritDoc
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    // Get unread messages from this user count
    public function getUnreadMessagesCountAttribute()
    {
        return count(Message::where([
            'from_user_id' => $this->id,
            'to_user_id' => auth()->user()->id
        ])
            ->whereNull('read_at')
            ->get());
    }

    // Friends list
    public function getFriendsList()
    {
        $friends_records = Friend::select('node_one_id', 'node_two_id')
            ->where(function ($q) {
                $q->where('node_one_id', $this->id)
                    ->orWhere('node_two_id', $this->id);
            })
            ->where('accepted', true)
            ->get();

        $friends_ids = [];

        foreach ($friends_records as $friend) {
            if ($friend['node_one_id'] !== $this->id) $friends_ids[] = $friend['node_one_id'];
            if ($friend['node_two_id'] !== $this->id) $friends_ids[] = $friend['node_two_id'];
        }

        $friends = User::select([
            'id',
            'plate_number AS plateNumber',
            'first_name AS firstName',
            'last_name AS lastName',
        ])
            ->whereIn('id', $friends_ids)
            ->get();

        foreach($friends as $friend) {
            $friend->append('unreadMessagesCount');
        }

        return $friends;
    }

    // Is friend
    public function isFriend($id)
    {
        if (auth()->user()->id === $this->id) return true;

        $friend = Friend::where(function ($q) use ($id) {
            $q->where('node_one_id', $this->id)
                ->where('node_two_id', $id);
        })->orWhere(function ($q) use ($id) {
            $q->where('node_one_id', $id)
                ->where('node_two_id', $this->id);
        })->first();

        if ($friend) {
            if ($friend->accepted) return true;
            return false;
        }
        return false;
    }

    // Is profile accessible
    public function isAccessible($id = 0)
    {
        if ($this->profile_visibility === 'PUBLIC'
            || $this->isFriend($id)
            || $this->id === auth()->user()->id
        ) return true;

        return false;
    }

    // Is friend with this user attribute
    public function getIsFriendWithMeAttribute()
    {
        $friend = Friend::where(function ($q) {
            $q->where('node_one_id', $this->id)
                ->where('node_two_id', auth()->user()->id);
        })->orWhere(function ($q) {
            $q->where('node_one_id', auth()->user()->id)
                ->where('node_two_id', $this->id);
        })->first();

        if (!$friend) return 'no';
        else {
            if ($friend->accepted) return 'yes';
            else return 'pending';
        }
    }

    // Feed
    public function getFeedAttribute()
    {
        if ($this->isAccessible(auth()->user()->id)) {
            $posts = Post::select('id')
                ->where('user_id', $this->id)
                ->orderBy('id', 'desc')
                ->get()
                ->pluck('id');
            return $posts;
        } else return 'profile_is_private';
    }

    // Is online
    public function getIsOnlineAttribute()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    // Notifications count
    public function getNotificationsCountAttribute()
    {
        return count($this->unreadNotifications);
    }

    public function getCustomNotifications(){
        $notifications = Notification::where([
                'notifiable_id' => auth()->user()->id
            ])
            ->get();
        
        return $notifications;

        $tempData = [];
        foreach($notifications as $notification){
            $tempItem->notification = $notification;
            $tempItem->timeAgo = $notification->getTimeAgoAttribute();
            $tempItem->formattedDate = $notification->getFormattedDateAttribute();
            $tempData[] = $tempItem;
        }

        return $tempData;
    }
}
