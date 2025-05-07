<?php

namespace App\Mail\Administration\Ticket;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class ItTicketCreationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $itTicket;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($itTicket, $user)
    {
        $this->itTicket = $itTicket;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'New IT Ticket By ' . $this->itTicket->creator->alias_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.administration.ticket.it_ticket_creation',
            with: [
                'itTicket' => $this->itTicket,
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
