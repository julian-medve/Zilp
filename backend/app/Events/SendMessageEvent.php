<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMessageEvent implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $message, $receiver_id;

  public function __construct($message, $receiver_id)
  {
      $this->message = $message;
      $this->receiver_id = $receiver_id;
  }

  public function broadcastOn()
  {
      return ['App.User.' . $this->receiver_id];
  }

  public function broadcastAs()
  {
      return 'NewMessage';
  }
}