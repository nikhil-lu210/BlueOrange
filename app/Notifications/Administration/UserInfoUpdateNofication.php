<?php

namespace App\Notifications\Administration;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserInfoUpdateNofication extends Notification
{
    use Queueable;

    protected $user;
    protected $authUser;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $authUser)
    {
        $this->user = $user;
        $this->authUser = $authUser;
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
        $url = URL::route('administration.my.profile');
        return [
            'url'   => $url,
            'icon'   => 'user',
            'title'   => 'User Info Updated',
            'message'     => 'Your User Info Has Been Updated by, '. $this->authUser->name,
        ];
    }
}
