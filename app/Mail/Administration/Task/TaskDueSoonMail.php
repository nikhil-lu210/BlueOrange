<?php

namespace App\Mail\Administration\Task;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskDueSoonMail extends Mailable implements ShouldQueue
{
	use Queueable, SerializesModels;

	public $data;
	public $user;
	public $daysLeft;

	public function __construct($data, $user, int $daysLeft)
	{
		$this->data = $data;
		$this->user = $user;
		$this->daysLeft = $daysLeft;
	}

	public function envelope(): Envelope
	{
		return new Envelope(
			from: new Address(config('mail.from.address'), config('mail.from.name')),
			subject: 'Task Due in '. $this->daysLeft .' day(s) - Please Update',
		);
	}

	public function content(): Content
	{
		return new Content(
			markdown: 'emails.administration.task.due_soon',
			with: [
				'data' => $this->data,
				'user' => $this->user,
				'daysLeft' => $this->daysLeft,
			]
		);
	}

	public function attachments(): array
	{
		return [];
	}
}


