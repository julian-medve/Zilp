<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostComment extends Notification
{
    use Queueable;
    private $message;
    private $post_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $post_id)
    {
        $this->post_id = $post_id;
        $this->message = $user->first_name . ' ' . $user->last_name . ' (' . $user->plate_number . ')' .
            ' has commented on your post';
    }

    public function broadcastType()
    {
        return 'post-comment';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'post_id' => $this->post_id
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => $this->message
        ]);
    }
}
