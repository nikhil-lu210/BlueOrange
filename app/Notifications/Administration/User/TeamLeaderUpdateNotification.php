<?php

namespace App\Notifications\Administration\User;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;

class TeamLeaderUpdateNotification extends Notification
{
    use Queueable;

    protected $employee;
    protected $oldTeamLeader;
    protected $newTeamLeader;
    protected $authUser;

    /**
     * Create a new notification instance.
     */
    public function __construct($employee, $oldTeamLeader, $newTeamLeader, $authUser)
    {
        $this->employee = $employee;
        $this->oldTeamLeader = $oldTeamLeader;
        $this->newTeamLeader = $newTeamLeader;
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
        $url = URL::route('administration.settings.user.show.profile', ['user' => $this->employee]);

        // Customize message based on who is receiving the notification
        $message = '';

        if ($notifiable->id === $this->employee->id) {
            // Message for the employee
            $message = 'Your Team Leader has been updated from ' .
                       ($this->oldTeamLeader ? $this->oldTeamLeader->employee->alias_name : 'None') .
                       ' to ' . $this->newTeamLeader->employee->alias_name . ' by ' . $this->authUser->employee->alias_name;
        } elseif ($this->oldTeamLeader && $notifiable->id === $this->oldTeamLeader->id) {
            // Message for the old team leader
            $message = 'You have been removed as Team Leader for ' . $this->employee->employee->alias_name .
                       ' by ' . $this->authUser->employee->alias_name;
        } elseif ($notifiable->id === $this->newTeamLeader->id) {
            // Message for the new team leader
            $message = 'You have been assigned as Team Leader for ' . $this->employee->employee->alias_name .
                       ' by ' . $this->authUser->employee->alias_name;
        }

        return [
            'url'     => $url,
            'icon'    => 'user-shield',
            'title'   => 'Team Leader Update',
            'message' => $message,
        ];
    }
}
