<?php

namespace App\Mail\Administration\DatabaseBackup;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class DatabaseBackupFailedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $errorMessage;
    public $failureDate;

    /**
     * Create a new message instance.
     */
    public function __construct($errorMessage, $failureDate)
    {
        $this->errorMessage = $errorMessage;
        $this->failureDate = $failureDate;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Database Backup Failed - ' . $this->failureDate,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.administration.backup.database_backup_failed',
            with: [
                'errorMessage' => $this->errorMessage,
                'failureDate' => $this->failureDate,
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
