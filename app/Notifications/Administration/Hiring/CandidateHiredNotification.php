<?php

namespace App\Notifications\Administration\Hiring;

use App\Models\Hiring\HiringCandidate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CandidateHiredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $candidate;

    /**
     * Create a new notification instance.
     */
    public function __construct(HiringCandidate $candidate)
    {
        $this->candidate = $candidate;
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
        return (new MailMessage)
            ->subject('Welcome to ' . config('app.name') . '!')
            ->greeting("Congratulations {$notifiable->name}!")
            ->line('We are pleased to inform you that you have been successfully hired for the position of ' . $this->candidate->expected_role . '.')
            ->line('Your user account has been created and you can now access the company portal.')
            ->line('Please contact HR for your login credentials and onboarding information.')
            ->line('We look forward to having you as part of our team!')
            ->action('Visit Company Portal', url('/'))
            ->line('Welcome aboard!');
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
            'message' => "Congratulations! You have been hired for the position of {$this->candidate->expected_role}",
            'hired_at' => $this->candidate->hired_at,
            'url' => url('/'),
        ];
    }
}
