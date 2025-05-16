<?php

namespace App\Notifications\Administration\User;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;

class ShiftUpdateNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $oldShift;
    protected $newShift;
    protected $authUser;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $oldShift, $newShift, $authUser)
    {
        $this->user = $user;
        $this->oldShift = $oldShift;
        $this->newShift = $newShift;
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
        $url = URL::route('administration.settings.user.show.profile', ['user' => $this->user]);

        // Format times for display
        $oldStartTime = date('h:i A', strtotime($this->oldShift->start_time));
        $oldEndTime = date('h:i A', strtotime($this->oldShift->end_time));
        $newStartTime = date('h:i A', strtotime($this->newShift->start_time));
        $newEndTime = date('h:i A', strtotime($this->newShift->end_time));

        // Customize message based on who is receiving the notification
        $message = '';

        // Get the active team leader ID if it exists
        $activeTeamLeader = $this->user->active_team_leader;
        $activeTeamLeaderId = $activeTeamLeader ? $activeTeamLeader->id : null;

        if ($notifiable->id === $this->user->id) {
            // Message for the employee
            $message = 'Your shift has been updated from ' . $oldStartTime . ' - ' . $oldEndTime .
                       ' to ' . $newStartTime . ' - ' . $newEndTime . ' by ' . $this->authUser->employee->alias_name;
        } elseif ($activeTeamLeaderId && $notifiable->id === $activeTeamLeaderId) {
            // Message for the team leader
            $message = 'Your team member ' . $this->user->employee->alias_name . '\'s shift has been updated from ' .
                       $oldStartTime . ' - ' . $oldEndTime . ' to ' . $newStartTime . ' - ' . $newEndTime .
                       ' by ' . $this->authUser->employee->alias_name;
        } else {
            // Message for users with permissions
            $message = $this->user->employee->alias_name . '\'s shift has been updated from ' .
                       $oldStartTime . ' - ' . $oldEndTime . ' to ' . $newStartTime . ' - ' . $newEndTime .
                       ' by ' . $this->authUser->employee->alias_name;
        }

        return [
            'url'     => $url,
            'icon'    => 'clock',
            'title'   => 'Shift Update',
            'message' => $message,
        ];
    }
}
