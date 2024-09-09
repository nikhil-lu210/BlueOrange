<?php

namespace App\Notifications\Administration\DailyWorkUpdate;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DailyWorkUpdateUpdateNotification extends Notification
{
    use Queueable;

    protected $workUpdate, $authUser;

    /**
     * Create a new notification instance.
     */
    public function __construct($workUpdate, $authUser)
    {
        $this->workUpdate = $workUpdate;
        $this->authUser = $authUser;

        // dd($this->workUpdate);
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
        $url = URL::route('administration.daily_work_update.show', ['daily_work_update' => $this->workUpdate]);
        return [
            'url'   => $url,
            'icon'   => 'speakerphone',
            'title'   => 'Work Update (' . get_date_only($this->workUpdate->date) . ')',
            'message'     => 'Your Daily Work Update Has Been Rated By '. $this->authUser->name,
        ];
    }
}
