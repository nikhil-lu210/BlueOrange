<?php

namespace App\Notifications\Administration\Recognition;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use URL;

class RecognitionCreatedNotification extends Notification
{
    use Queueable;

    public $recognition;

    /**
     * Create a new notification instance.
     */
    public function __construct($recognition)
    {
        $this->recognition = $recognition;
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
    public function toMail(object $notifiable)
    {
        //
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $url = URL::route('administration.dashboard.index');
        // Recognition for specific employee
        $title = 'User Recognition';
        $message = $this->recognition->user->alias_name .' got recognition from '. $this->recognition->recognizer->alias_name;
        
        return [
            'url' => $url,
            'icon'   => 'badge',
            'title' => $title,
            'message' => $message,

        ];
    }
}
