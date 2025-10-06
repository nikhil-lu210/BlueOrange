<?php

namespace App\Notifications\Administration\LearningHub;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;

class LearningHubCommentNotification extends Notification
{
    use Queueable;

    protected $learningTopic, $authUser;

    /**
     * Create a new notification instance.
     */
    public function __construct($learningTopic, $authUser)
    {
        $this->learningTopic = $learningTopic;
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
        $url = URL::route('administration.learning_hub.show', ['learning_topic' => $this->learningTopic]);
        return [
            'url'   => $url,
            'icon'   => 'message-circle',
            'title'   => 'New Comment on Learning Topic',
            'message'     => 'A new comment arrived on Learning Topic by '. ($this->authUser->employee->alias_name ?? $this->authUser->name),
        ];
    }
}
