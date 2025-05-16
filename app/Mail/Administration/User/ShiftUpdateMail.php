<?php

namespace App\Mail\Administration\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Address;

class ShiftUpdateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $oldShift;
    public $newShift;
    public $notifiableUser;
    public $authUser;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $oldShift, $newShift, $notifiableUser, $authUser)
    {
        $this->user = $user;
        $this->oldShift = $oldShift;
        $this->newShift = $newShift;
        $this->notifiableUser = $notifiableUser;
        $this->authUser = $authUser;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = '';

        // Get the active team leader ID if it exists
        $activeTeamLeader = $this->user->active_team_leader;
        $activeTeamLeaderId = $activeTeamLeader ? $activeTeamLeader->id : null;

        if ($this->notifiableUser->id === $this->user->id) {
            $subject = 'Your Shift Has Been Updated';
        } elseif ($activeTeamLeaderId && $this->notifiableUser->id === $activeTeamLeaderId) {
            $subject = 'Team Member ' . $this->user->employee->alias_name . '\'s Shift Has Been Updated';
        } else {
            $subject = 'Employee ' . $this->user->employee->alias_name . '\'s Shift Has Been Updated';
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
            markdown: 'emails.administration.user.shift_update',
            with: [
                'user' => $this->user,
                'oldShift' => $this->oldShift,
                'newShift' => $this->newShift,
                'notifiableUser' => $this->notifiableUser,
                'authUser' => $this->authUser
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
