<?php

namespace App\Mail\Administration\FunctionalityWalkthrough;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\FunctionalityWalkthrough\FunctionalityWalkthrough;
use App\Models\User;

class NewWalkthroughMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $walkthrough;
    protected $user;

    /**
     * Create a new message instance.
     */
    public function __construct(FunctionalityWalkthrough $walkthrough, User $user)
    {
        $this->walkthrough = $walkthrough;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Functionality Walkthrough: ' . $this->walkthrough->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.administration.functionality_walkthrough.new_walkthrough',
            with: [
                'walkthrough' => $this->walkthrough,
                'user' => $this->user,
                'creator' => $this->walkthrough->creator,
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
