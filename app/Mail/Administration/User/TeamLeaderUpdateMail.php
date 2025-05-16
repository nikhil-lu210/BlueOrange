<?php

namespace App\Mail\Administration\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Address;

class TeamLeaderUpdateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $employee;
    public $oldTeamLeader;
    public $newTeamLeader;
    public $notifiableUser;
    public $authUser;

    /**
     * Create a new message instance.
     */
    public function __construct($employee, $oldTeamLeader, $newTeamLeader, $notifiableUser, $authUser)
    {
        $this->employee = $employee;
        $this->oldTeamLeader = $oldTeamLeader;
        $this->newTeamLeader = $newTeamLeader;
        $this->notifiableUser = $notifiableUser;
        $this->authUser = $authUser;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = '';
        
        if ($this->notifiableUser->id === $this->employee->id) {
            $subject = 'Your Team Leader Has Been Updated';
        } elseif ($this->oldTeamLeader && $this->notifiableUser->id === $this->oldTeamLeader->id) {
            $subject = 'Team Leader Role Removed for ' . $this->employee->employee->alias_name;
        } elseif ($this->notifiableUser->id === $this->newTeamLeader->id) {
            $subject = 'You Have Been Assigned as Team Leader for ' . $this->employee->employee->alias_name;
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
            markdown: 'emails.administration.user.team_leader_update',
            with: [
                'employee' => $this->employee,
                'oldTeamLeader' => $this->oldTeamLeader,
                'newTeamLeader' => $this->newTeamLeader,
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
