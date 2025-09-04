<?php

namespace App\Notifications\Administration\FunctionalityWalkthrough;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;

class WalkthroughCreateNotification extends Notification
{
    use Queueable;

    protected $walkthrough, $authUser;

    /**
     * Create a new notification instance.
     */
    public function __construct($walkthrough, $authUser)
    {
        $this->walkthrough = $walkthrough;
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
        $url = URL::route('administration.functionality_walkthrough.show', ['functionalityWalkthrough' => $this->walkthrough]);
        return [
            'url'   => $url,
            'icon'   => 'list-numbers',
            'title'   => 'New Functionality Walkthrough Available',
            'message'     => 'A New Functionality Walkthrough Has Been Created By '. ($this->authUser->employee->alias_name ?? $this->authUser->name),
        ];
    }
}
