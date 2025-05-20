<?php

namespace App\Notifications\Administration\Announcement;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;

class AnnouncementCommentNotification extends Notification
{
    use Queueable;

    protected $announcement, $authUser;

    /**
     * Create a new notification instance.
     */
    public function __construct($announcement, $authUser)
    {
        $this->announcement = $announcement;
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
        $url = URL::route('administration.announcement.show', ['announcement' => $this->announcement]);
        return [
            'url'   => $url,
            'icon'   => 'message-circle',
            'title'   => 'New Comment on Announcement',
            'message'     => 'A new comment arrived on Announcement by '. $this->authUser->alias_name,
        ];
    }
}
