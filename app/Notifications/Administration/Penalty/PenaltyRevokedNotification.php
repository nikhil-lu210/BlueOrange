<?php

namespace App\Notifications\Administration\Penalty;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class PenaltyRevokedNotification extends Notification
{
    use Queueable;

    protected $penalty;
    protected $deletedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct($penalty, $deletedBy)
    {
        $this->penalty = $penalty;
        $this->deletedBy = $deletedBy;
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
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'url'   => URL::route('administration.penalty.my'),
            'icon'   => 'check-circle',
            'title'   => 'Penalty Revoked',
            'message' => 'Your penalty (' . $this->penalty->type . ') has been revoked by ' . $this->deletedBy->alias_name,
        ];
    }
}
