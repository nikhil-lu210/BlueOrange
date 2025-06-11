<?php

namespace App\Notifications\Administration\Penalty;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class PenaltyCreatedNotification extends Notification
{
    use Queueable;

    public $penalty;
    public $authUser;
    public $isForTeamLeader;

    /**
     * Create a new notification instance.
     */
    public function __construct($penalty, $authUser, $isForTeamLeader = false)
    {
        $this->penalty = $penalty;
        $this->authUser = $authUser;
        $this->isForTeamLeader = $isForTeamLeader;
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
        $url = URL::route('administration.penalty.show', ['penalty' => $this->penalty]);

        if ($this->isForTeamLeader) {
            // Message for team leader
            $title = 'Team Member Penalty';
            $message = 'Your team member ' . $this->penalty->user->alias_name . ' has received a penalty (' . $this->penalty->type . ') by ' . $this->authUser->alias_name;
        } else {
            // Message for employee
            $title = 'Penalty Assigned';
            $message = 'A penalty (' . $this->penalty->type . ') has been assigned to you by ' . $this->authUser->alias_name;
        }

        return [
            'url'   => $url,
            'icon'   => 'gavel',
            'title'   => $title,
            'message' => $message,
        ];
    }
}
