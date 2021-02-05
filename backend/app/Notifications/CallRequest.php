<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CallRequest extends Notification
{
    use Queueable;
    private $message;
    private $user_id;

    /**
     * Create a new notification instance.
     *
     * @param $user
     */
    public function __construct($user)
    {
        $this->user_id = $user->id;
        $this->message = $user->first_name . ' ' . $user->last_name . ' (' . $user->plate_number . ')' .
            ' is calling you...';
    }

    public function broadcastType()
    {
        return 'call-request';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => $this->message
        ]);
    }
}
