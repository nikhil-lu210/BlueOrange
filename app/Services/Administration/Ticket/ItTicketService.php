<?php

namespace App\Services\Administration\Ticket;

use App\Models\Ticket\ItTicket;
use App\Mail\Administration\Ticket\ItTicketCreationMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Notifications\Administration\Tickets\ItTicket\ItTicketCreateNotification;
use App\Notifications\Administration\Tickets\ItTicket\ItTicketRunningNotification;
use App\Notifications\Administration\Tickets\ItTicket\ItTicketStatusUpdateNotification;

class ItTicketService
{
    public function createTicket(array $data, User $creator): ItTicket
    {
        $itTicket = null;

        DB::transaction(function() use ($data, $creator, &$itTicket) {
            $itTicket = ItTicket::create([
                'creator_id' => $creator->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'status' => 'Pending',
            ]);

            $this->notifyTicketCreation($itTicket, $creator);
        }, 3);

        // Ensure we always return an ItTicket instance
        if (!$itTicket) {
            throw new \RuntimeException('Failed to create IT ticket');
        }

        return $itTicket;
    }

    public function updateTicket(ItTicket $itTicket, array $data): ItTicket
    {
        $itTicket->update([
            'title' => $data['title'],
            'description' => $data['description']
        ]);

        return $itTicket;
    }

    public function markAsRunning(ItTicket $itTicket, User $user): ItTicket
    {
        DB::transaction(function() use ($itTicket, $user) {
            $itTicket->update([
                'status' => 'Running',
            ]);

            $itTicket->creator->notify(new ItTicketRunningNotification($itTicket, $user));
        }, 3);

        return $itTicket;
    }

    public function updateStatus(ItTicket $itTicket, array $data, User $user): ItTicket
    {
        DB::transaction(function() use ($itTicket, $data, $user) {
            $itTicket->update([
                'solved_by' => $user->id,
                'solved_at' => now(),
                'status' => $data['status'],
                'solver_note' => $data['solver_note'],
            ]);

            $itTicket->creator->notify(new ItTicketStatusUpdateNotification($itTicket, $user));
        }, 3);

        return $itTicket;
    }

    public function updateSeenBy(ItTicket $itTicket, User $user): void
    {
        $currentSeenBy = $itTicket->seen_by;

        if (!collect($currentSeenBy)->contains('user_id', $user->id)) {
            $currentSeenBy[] = [
                'user_id' => $user->id,
                'seen_at' => now()->toDateTimeString(),
            ];

            $itTicket->update(['seen_by' => $currentSeenBy]);
        }
    }

    private function notifyTicketCreation(ItTicket $itTicket, User $creator): void
    {
        $notifiableUsers = User::whereStatus('Active')->get()->filter(function ($user) {
            return $user->hasAnyPermission(['IT Ticket Everything', 'IT Ticket Update']);
        });

        foreach ($notifiableUsers as $user) {
            $user->notify(new ItTicketCreateNotification($itTicket, $creator));

            Mail::to($user->employee->official_email)->queue(new ItTicketCreationMail($itTicket, $user));
        }
    }
}

