<?php

namespace App\Observers\Administration\Hiring;

use App\Models\Hiring\HiringCandidate;
use App\Notifications\Administration\Hiring\CandidateStatusChangedNotification;
use App\Notifications\Administration\Hiring\CandidateHiredNotification;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class HiringCandidateObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the HiringCandidate "created" event.
     */
    public function created(HiringCandidate $candidate): void
    {
        // Load necessary relationships for notifications
        $candidate->load(['creator.employee']);

        // Notify HR team about new candidate
        $this->notifyHRTeam($candidate, 'created');
    }

    /**
     * Handle the HiringCandidate "updated" event.
     */
    public function updated(HiringCandidate $candidate): void
    {
        // Check if status changed
        if ($candidate->isDirty('status')) {
            $candidate->load(['creator.employee', 'user.employee']);
            
            $oldStatus = $candidate->getOriginal('status');
            $newStatus = $candidate->status;

            // Notify relevant parties about status change
            $this->notifyStatusChange($candidate, $oldStatus, $newStatus);

            // Special handling for hired status
            if ($newStatus === 'hired') {
                $this->notifyHiring($candidate);
            }
        }

        // Check if stage changed
        if ($candidate->isDirty('current_stage')) {
            $this->notifyStageProgression($candidate);
        }
    }

    /**
     * Handle the HiringCandidate "deleted" event.
     */
    public function deleted(HiringCandidate $candidate): void
    {
        // Notify HR team about candidate removal
        $this->notifyHRTeam($candidate, 'deleted');
    }

    /**
     * Notify HR team about candidate events
     */
    private function notifyHRTeam(HiringCandidate $candidate, string $action): void
    {
        // Get users with HR permissions
        $hrUsers = \App\Models\User::whereStatus('Active')
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['Super Admin', 'Developer'])
                      ->orWhereHas('permissions', function ($permQuery) {
                          $permQuery->where('name', 'like', 'Employee Hiring%');
                      });
            })
            ->get();

        foreach ($hrUsers as $user) {
            $user->notify(new CandidateStatusChangedNotification($candidate, $action, auth()->user()));
        }
    }

    /**
     * Notify about status changes
     */
    private function notifyStatusChange(HiringCandidate $candidate, string $oldStatus, string $newStatus): void
    {
        // Notify creator
        if ($candidate->creator) {
            $candidate->creator->notify(new CandidateStatusChangedNotification($candidate, 'status_changed', auth()->user()));
        }

        // Notify assigned evaluators
        $evaluators = $candidate->evaluations()
            ->with('assignedUser')
            ->get()
            ->pluck('assignedUser')
            ->unique('id');

        foreach ($evaluators as $evaluator) {
            if ($evaluator && $evaluator->id !== auth()->id()) {
                $evaluator->notify(new CandidateStatusChangedNotification($candidate, 'status_changed', auth()->user()));
            }
        }
    }

    /**
     * Notify about hiring completion
     */
    private function notifyHiring(HiringCandidate $candidate): void
    {
        // Notify HR team
        $this->notifyHRTeam($candidate, 'hired');

        // Notify the newly hired user if account was created
        if ($candidate->user) {
            $candidate->user->notify(new CandidateHiredNotification($candidate));
        }
    }

    /**
     * Notify about stage progression
     */
    private function notifyStageProgression(HiringCandidate $candidate): void
    {
        // Get the new stage evaluation if it exists
        $newStageEvaluation = $candidate->evaluations()
            ->whereHas('stage', function ($query) use ($candidate) {
                $query->where('stage_order', $candidate->current_stage);
            })
            ->with('assignedUser')
            ->first();

        if ($newStageEvaluation && $newStageEvaluation->assignedUser) {
            $newStageEvaluation->assignedUser->notify(
                new CandidateStatusChangedNotification($candidate, 'stage_assigned', auth()->user())
            );
        }
    }
}
