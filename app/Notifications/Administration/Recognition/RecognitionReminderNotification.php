<?php

namespace App\Notifications\Administration\Recognition;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RecognitionReminderNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Recognition Reminder')
            ->greeting('Hello ' . $notifiable->alias_name . ',')
            ->line('You have not recognized any employee in the last ' . config('recognition.reminder_days', 15) . ' days.')
            ->action('Submit Recognition', url('/dashboard'))
            ->line('Please go to your dashboard and click on Submit Recognition to recognize any employee.');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'You have not recognized any employee in the last ' . config('recognition.reminder_days', 15) . ' days.'
        ];
    }
}
