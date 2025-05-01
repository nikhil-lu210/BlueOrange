<?php

namespace App\Notifications\Administration\Attendance\Issue;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AttendanceIssueRequestUpdateNotification extends Notification
{
    use Queueable;

    protected $issue, $authUser;

    /**
     * Create a new notification instance.
     */
    public function __construct($issue, $authUser)
    {
        $this->issue = $issue;
        $this->authUser = $authUser;

        // dd($this->issue);
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
        $url = URL::route('administration.attendance.issue.show', ['issue' => $this->issue]);
        return [
            'url'   => $url,
            'icon'   => 'bell-question',
            'title'   => 'Attendance Issue '. $this->issue->status,
            'message'     => 'Attendance Issue Has Been '. $this->issue->status .' By '. $this->authUser->alias_name,
        ];
    }
}
