<?php

namespace App\Mail\Administration\Task;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskOverdueMail extends Mailable implements ShouldQueue
{
	use Queueable, SerializesModels;

	public $data;
	public $user;

	public function __construct($data, $user)
	{
		$this->data = $data;
		$this->user = $user;
	}

	public function envelope(): Envelope
	{
		return new Envelope(
			from: new Address(config('mail.from.address'), config('mail.from.name')),
			subject: 'Task Overdue - Immediate Action Required',
		);
	}

	public function content(): Content
	{
		return new Content(
			markdown: 'emails.administration.task.overdue',
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


