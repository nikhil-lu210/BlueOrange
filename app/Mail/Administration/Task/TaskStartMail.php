<?php

namespace App\Mail\Administration\Task;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskStartMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;
    public $history;
    public $user;
    public $starter;

    /**
     * Create a new message instance.
     */
    public function __construct($data, $history, $user, $starter)
    {
        $this->data = $data;
        $this->history = $history;
        $this->user = $user;
        $this->starter = $starter;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Task Started By '. $this->starter->alias_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.administration.task.task_start',
            with: [
                'data' => $this->data,
                'history' => $this->history,
                'user' => $this->user,
                'starter' => $this->starter
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
