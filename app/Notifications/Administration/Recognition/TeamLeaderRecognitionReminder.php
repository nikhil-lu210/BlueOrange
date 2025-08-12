<?php

namespace App\Notifications\Administration\Recognition;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class TeamLeaderRecognitionReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public array $missingEmployees, public string $monthLabel)
    {
    }

    public function via(object $notifiable): array
    {
        // Follow existing pattern: database notification only. Email is sent via Mailable separately.
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $url = URL::route('administration.employee_recognition.index', ['month' => now()->startOfMonth()->format('Y-m-d')]);
        return [
            'url' => $url,
            'icon' => 'speakerphone',
            'title' => __('Monthly Recognitions Due'),
            'message' => __('You have pending recognitions for :month.', ['month' => $this->monthLabel]),
            'missing' => $this->missingEmployees,
        ];
    }
}
