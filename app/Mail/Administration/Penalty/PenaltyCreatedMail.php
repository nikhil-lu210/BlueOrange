<?php

namespace App\Mail\Administration\Penalty;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class PenaltyCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $penalty;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($penalty, $user)
    {
        $this->penalty = $penalty;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Different subject for team leader vs employee
        if ($this->user->id === $this->penalty->user_id) {
            $subject = 'Penalty Assigned By ' . $this->penalty->creator->alias_name;
        } else {
            $subject = 'Team Member Penalty: ' . $this->penalty->user->alias_name . ' - By ' . $this->penalty->creator->alias_name;
        }

        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.administration.penalty.penalty_created',
            with: [
                'penalty' => $this->penalty,
                'user' => $this->user
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
