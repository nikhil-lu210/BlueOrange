<?php

namespace App\Notifications\Administration\Sugggestion;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuggestionCreateNotification extends Notification
{
    use Queueable;

    protected $suggestion, $authUser;


    /**
     * Create a new notification instance.
     */
    public function __construct($suggestion, $authUser)
    {
        $this->suggestion = $suggestion;
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
        $url = URL::route('administration.suggestion.show', ['suggestion' => $this->suggestion]);
        return [
            'url'   => $url,
            'icon'   => 'exclamation-circle',
            'title'   => 'New Suggestion Arrived',
            'message'     => 'A New Suggestion Has Been Arised By '. $this->authUser->alias_name,
        ];
    }
}
