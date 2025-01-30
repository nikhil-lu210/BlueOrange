<?php

namespace App\Notifications\Administration;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewUserRegistrationNotification extends Notification
{
    use Queueable;

    protected $user, $authUser;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $authUser)
    {
        $this->user = $user;
        $this->authUser = $authUser;

        // dd($this->user);
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
        $url = URL::route('administration.settings.user.show.profile', ['user' => $this->user]);
        return [
            'url'   => $url,
            'icon'   => 'user',
            'title'   => 'New User Assigned',
            'message'     => 'A New '. $this->user->role->name . ' Has Been Assigned By '. $this->authUser->name,
        ];
    }
}
