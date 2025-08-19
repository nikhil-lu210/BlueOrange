<?php

namespace App\Mail\Administration\Recognition;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Recognition\Recognition;
use App\Models\User;

class RecognitionReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $teamLeader;

    public function __construct(User $teamLeader)
    {
        $this->teamLeader = $teamLeader;
    }

    public function build()
    {
        return $this->subject('Recognition Reminder!')
            ->view('emails.administration.recognition.recognition_reminder')
            ->with([
                'teamLeader' => $this->teamLeader,
            ]);
    }
}
