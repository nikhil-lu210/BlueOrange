<?php

namespace App\Notifications\Administration\Leave;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LeaveStoreNotification extends Notification
{
    use Queueable;

    protected $leave, $authUser;

    /**
     * Create a new notification instance.
     */
    public function __construct($leave, $authUser)
    {
        $this->leave = $leave;
        $this->authUser = $authUser;

        // dd($this->leave);
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
        $url = URL::route('administration.leave.history.show', ['leaveHistory' => $this->leave]);
        return [
            'url'   => $url,
            'icon'   => 'calendar-pause',
            'title'   => 'New Leave Request',
            'message'     => 'A New Leave Request Has Been Created By '. $this->authUser->name,
        ];
    }
}
