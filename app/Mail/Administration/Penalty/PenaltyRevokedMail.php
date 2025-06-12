<?php

namespace App\Mail\Administration\Penalty;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class PenaltyRevokedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $penalty;
    public $user;
    public $deleted_by;

    /**
     * Create a new message instance.
     */
    public function __construct($penalty, $user, $deleted_by)
    {
        $this->penalty = $penalty;
        $this->user = $user;
        $this->deleted_by = $deleted_by;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'Penalty Revoked by ' . $this->deleted_by->alias_name;

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
            view: 'emails.administration.penalty.penalty_revoked',
            with: [
                'penalty' => $this->penalty,
                'user' => $this->user,
                'deleted_by' => $this->deleted_by
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
