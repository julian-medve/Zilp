<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FriendRequestEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $senderId, $receiverId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($senderId, $receiverId)
    {
        $this->senderId = $senderId;
        $this->receiverId = $receiverId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['App.User.' . $this->receiverId];
        // return ['broadcast'];
    }

    public function broadcastAs()
    {
      return 'FriendRequestEvent';
    }
}
