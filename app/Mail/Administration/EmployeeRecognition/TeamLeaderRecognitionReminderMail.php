<?php

namespace App\Mail\Administration\EmployeeRecognition;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class TeamLeaderRecognitionReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public array $data, public $user)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: __('Reminder: Monthly Recognitions Due (:month)', ['month' => $this->data['month_label'] ?? now()->format('F Y')]),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.administration.employee_recognition.team_leader_recognition_reminder',
            with: [
                'data' => $this->data,
                'user' => $this->user,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
