<?php

namespace App\Notifications\Administration\Announcement;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AnnouncementCreateNotification extends Notification
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

        // dd($this->announcement);
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
            'icon'   => 'speakerphone',
            'title'   => 'New Announcement Arrived',
            'message'     => 'A New Announcement Has Been Created By '. $this->authUser->alias_name,
        ];
    }
}
