<?php

namespace App\Mail\Administration\Task;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewCommentOnTaskMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;
    public $user;
    public $commenter;

    /**
     * Create a new message instance.
     */
    public function __construct($data, $user, $commenter)
    {
        $this->data = $data;
        $this->user = $user;
        $this->commenter = $commenter;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'New Comment For Task ('. $this->data->taskid .') by '. $this->data->creator->alias_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.administration.task.new_comment_task',
            with: [
                'data' => $this->data,
                'user' => $this->user,
                'commenter' => $this->commenter
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
