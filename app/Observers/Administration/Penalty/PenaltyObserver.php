<?php

namespace App\Observers\Administration\Penalty;

use App\Models\Penalty\Penalty;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use App\Mail\Administration\Penalty\PenaltyCreatedMail;
use App\Mail\Administration\Penalty\PenaltyRevokedMail;
use App\Notifications\Administration\Penalty\PenaltyCreatedNotification;
use App\Notifications\Administration\Penalty\PenaltyRevokedNotification;

class PenaltyObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Penalty "created" event.
     */
    public function created(Penalty $penalty): void
    {
        // Load necessary relationships for notifications
        $penalty->load(['user.employee', 'attendance', 'creator.employee']);

        // Send notifications automatically when a penalty is created
        $this->sendPenaltyNotifications($penalty);
    }

    /**
     * Handle the Penalty "updated" event.
     */
    public function updated(Penalty $penalty): void
    {
        //
    }

    /**
     * Handle the Penalty "deleted" event.
     */
    public function deleted(Penalty $penalty): void
    {
        // Load necessary relationships for notifications
        $penalty->load(['user.employee']);

        // Send penalty revoked notifications
        $this->sendPenaltyRevokedNotifications($penalty);
    }

    /**
     * Handle the Penalty "restored" event.
     */
    public function restored(Penalty $penalty): void
    {
        //
    }

    /**
     * Handle the Penalty "force deleted" event.
     */
    public function forceDeleted(Penalty $penalty): void
    {
        //
    }

    /**
     * Send penalty notifications to employee and team leader
     */
    private function sendPenaltyNotifications(Penalty $penalty): void
    {
        $employee = $penalty->user;
        $creator = auth()->user();

        // 1. Notify the employee who received the penalty
        if ($employee && $employee->employee && $employee->employee->official_email) {
            // Send in-app notification
            $employee->notify(new PenaltyCreatedNotification($penalty, $creator));

            // Send email notification
            Mail::to($employee->employee->official_email)
                ->queue(new PenaltyCreatedMail($penalty, $employee));
        }

        // 2. Notify the employee's active team leader
        $activeTeamLeader = $employee->active_team_leader;

        if ($activeTeamLeader && $activeTeamLeader->employee && $activeTeamLeader->employee->official_email) {
            // Send in-app notification (with team member context)
            $activeTeamLeader->notify(new PenaltyCreatedNotification($penalty, $creator, true));

            // Send email notification
            Mail::to($activeTeamLeader->employee->official_email)
                ->queue(new PenaltyCreatedMail($penalty, $activeTeamLeader));
        }
    }

    /**
     * Send penalty revoked notifications to employee
     */
    private function sendPenaltyRevokedNotifications(Penalty $penalty): void
    {
        $employee = $penalty->user;
        $deletedBy = auth()->user();

        // Only notify the employee who received the penalty
        if ($employee && $employee->employee && $employee->employee->official_email) {
            // Send in-app notification
            $employee->notify(new PenaltyRevokedNotification($penalty, $deletedBy));

            // Send email notification
            Mail::to($employee->employee->official_email)
                ->queue(new PenaltyRevokedMail($penalty, $employee, $deletedBy));
        }
    }
}
