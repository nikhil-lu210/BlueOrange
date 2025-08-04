<?php

namespace App\Notifications\Administration\Hiring;

use App\Models\User;
use App\Models\Hiring\HiringCandidate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CandidateStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $candidate;
    protected $action;
    protected $actionBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(HiringCandidate $candidate, string $action, User $actionBy)
    {
        $this->candidate = $candidate;
        $this->action = $action;
        $this->actionBy = $actionBy;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->getMailSubject();
        $greeting = "Hello {$notifiable->name},";
        $message = $this->getMailMessage();

        return (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line($message)
            ->line("Candidate: {$this->candidate->name}")
            ->line("Email: {$this->candidate->email}")
            ->line("Expected Role: {$this->candidate->expected_role}")
            ->action('View Candidate Details', route('administration.hiring.show', $this->candidate))
            ->line('Thank you for using our hiring management system!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'candidate_id' => $this->candidate->id,
            'candidate_name' => $this->candidate->name,
            'candidate_email' => $this->candidate->email,
            'action' => $this->action,
            'action_by' => $this->actionBy->name,
            'action_by_id' => $this->actionBy->id,
            'message' => $this->getDatabaseMessage(),
            'url' => route('administration.hiring.show', $this->candidate),
        ];
    }

    /**
     * Get mail subject based on action
     */
    private function getMailSubject(): string
    {
        return match ($this->action) {
            'created' => 'New Candidate Added to Hiring Process',
            'status_changed' => 'Candidate Status Updated',
            'stage_assigned' => 'New Stage Evaluation Assigned',
            'hired' => 'Candidate Successfully Hired',
            'deleted' => 'Candidate Removed from Hiring Process',
            default => 'Hiring Process Update',
        };
    }

    /**
     * Get mail message based on action
     */
    private function getMailMessage(): string
    {
        return match ($this->action) {
            'created' => "A new candidate has been added to the hiring process by {$this->actionBy->name}.",
            'status_changed' => "The candidate's status has been updated by {$this->actionBy->name}.",
            'stage_assigned' => "You have been assigned to evaluate this candidate at stage {$this->candidate->current_stage_name}.",
            'hired' => "The candidate has been successfully hired and a user account has been created.",
            'deleted' => "The candidate has been removed from the hiring process by {$this->actionBy->name}.",
            default => "There has been an update in the hiring process.",
        };
    }

    /**
     * Get database message based on action
     */
    private function getDatabaseMessage(): string
    {
        return match ($this->action) {
            'created' => "New candidate {$this->candidate->name} added by {$this->actionBy->name}",
            'status_changed' => "Candidate {$this->candidate->name} status updated to {$this->candidate->status_formatted}",
            'stage_assigned' => "You've been assigned to evaluate {$this->candidate->name} at {$this->candidate->current_stage_name}",
            'hired' => "Candidate {$this->candidate->name} has been hired successfully",
            'deleted' => "Candidate {$this->candidate->name} removed by {$this->actionBy->name}",
            default => "Update for candidate {$this->candidate->name}",
        };
    }
}
