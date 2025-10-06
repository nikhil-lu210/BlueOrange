<?php

namespace App\Services\Administration\Event;

use App\Models\Event\Event;
use App\Repositories\Administration\Event\EventRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class EventService
{
    public function __construct(private readonly EventRepository $repo) {}

    /**
     * Create a new event with participants
     */
    public function create(array $data): Event
    {
        $event = null;
        DB::transaction(function () use (&$event, $data) {
            $event = Event::create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'start_time' => $data['start_time'] ?? null,
                'end_time' => $data['end_time'] ?? null,
                'location' => $data['location'] ?? null,
                'event_type' => $data['event_type'],
                'status' => $data['status'],
                'organizer_id' => auth()->id(),
                'is_all_day' => !empty($data['is_all_day']),
                'color' => $data['color'] ?? '#3788d8',
                'max_participants' => $data['max_participants'] ?? null,
                'is_public' => !empty($data['is_public']),
                'reminder_before' => $data['reminder_before'] ?? null,
                'reminder_unit' => $data['reminder_unit'] ?? null,
            ]);

            // Attach participants (pivot) and organizer
            $pivot = [];
            foreach ((array)($data['participants'] ?? []) as $uid) {
                if ($uid != auth()->id()) {
                    $pivot[$uid] = ['status' => 'Invited'];
                }
            }
            $pivot[auth()->id()] = ['status' => 'Accepted'];
            $event->participant_users()->syncWithoutDetaching($pivot);
        });
        if (!$event) throw new Exception('Failed to create event');
        return $event;
    }

    /**
     * Update event and re-sync participants
     */
    public function update(Event $event, array $data): Event
    {
        DB::transaction(function () use ($event, $data) {
            $event->update([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'start_time' => $data['start_time'] ?? null,
                'end_time' => $data['end_time'] ?? null,
                'location' => $data['location'] ?? null,
                'event_type' => $data['event_type'],
                'status' => $data['status'],
                'is_all_day' => !empty($data['is_all_day']),
                'color' => $data['color'] ?? '#3788d8',
                'max_participants' => $data['max_participants'] ?? null,
                'is_public' => !empty($data['is_public']),
                'reminder_before' => $data['reminder_before'] ?? null,
                'reminder_unit' => $data['reminder_unit'] ?? null,
            ]);

            if (array_key_exists('participants', $data)) {
                $pivot = [];
                foreach ((array)$data['participants'] as $uid) {
                    if ($uid != auth()->id()) {
                        $pivot[$uid] = ['status' => 'Invited'];
                    }
                }
                $pivot[auth()->id()] = ['status' => 'Accepted'];
                $event->participant_users()->sync($pivot);
            }
        });
        return $event;
    }
}

