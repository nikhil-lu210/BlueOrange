<?php

namespace App\Notifications\Administration\Tickets\ItTicket;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ItTicketCreateNotification extends Notification
{
    use Queueable;

    protected $ticket, $authUser;

    /**
     * Create a new notification instance.
     */
    public function __construct($ticket, $authUser)
    {
        $this->ticket = $ticket;
        $this->authUser = $authUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $url = URL::route('administration.ticket.it_ticket.show', ['it_ticket' => $this->ticket]);
        return [
            'url'   => $url,
            'icon'   => 'ticket',
            'title'   => 'New IT Ticket Arrived',
            'message'     => 'A New IT Ticket Has Been Arised By '. $this->authUser->name,
        ];
    }
}
