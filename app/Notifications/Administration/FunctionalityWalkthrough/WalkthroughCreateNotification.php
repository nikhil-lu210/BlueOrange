<?php

namespace App\Notifications\Administration\FunctionalityWalkthrough;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\FunctionalityWalkthrough\FunctionalityWalkthrough;
use App\Models\User;

class WalkthroughCreateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $walkthrough;
    protected $creator;

    /**
     * Create a new notification instance.
     */
    public function __construct(FunctionalityWalkthrough $walkthrough, User $creator)
    {
        $this->walkthrough = $walkthrough;
        $this->creator = $creator;
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
                    ->subject('New Functionality Walkthrough: ' . $this->walkthrough->title)
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('A new functionality walkthrough has been created by ' . $this->creator->name . '.')
                    ->line('Walkthrough: ' . $this->walkthrough->title)
                    ->action('View Walkthrough', route('administration.functionality_walkthrough.show', $this->walkthrough))
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
            'title' => 'New Functionality Walkthrough',
            'message' => 'A new functionality walkthrough "' . $this->walkthrough->title . '" has been created by ' . $this->creator->name . '.',
            'type' => 'walkthrough_created',
            'walkthrough_id' => $this->walkthrough->id,
            'creator_id' => $this->creator->id,
            'creator_name' => $this->creator->name,
            'walkthrough_title' => $this->walkthrough->title,
            'url' => route('administration.functionality_walkthrough.show', $this->walkthrough),
        ];
    }
}
