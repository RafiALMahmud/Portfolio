<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserMessage extends Notification
{
    use Queueable;

    protected $message;
    protected $user;

    public function __construct(string $message, $user)
    {
        $this->message = $message;
        $this->user = $user;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message,
            'user_name' => $this->user->name,
            'user_id' => $this->user->id,
        ];
    }
}



