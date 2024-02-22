<?php

namespace App\Notifications\Administration;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProfileUpdateNofication extends Notification
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $url = URL::route('administration.settings.user.show.profile', ['user' => $this->user->id]);
        return [
            'url'   => $url,
            'icon'   => 'user',
            'title'   => 'Employee Profile Updated',
            'message'     => $this->user->name.' Has Been Updated His/Her Profile.',
        ];
    }
}
