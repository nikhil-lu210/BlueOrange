<?php

namespace App\Mail\Administration\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserStatusUpdateNotifyMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $notifiableUser;
    public $authUser;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $notifiableUser, $authUser)
    {
        $this->user = $user;
        $this->notifiableUser = $notifiableUser;
        $this->authUser = $authUser;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: $this->user->name. '\'s Status Marked As '. $this->user->status .' By '. $this->authUser->employee->alias_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.administration.user.status_update',
            with: [
                'user' => $this->user,
                'notifiableUser' => $this->notifiableUser
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
