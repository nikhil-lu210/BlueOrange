<?php

namespace App\Repositories\Administration\Event;

use App\Models\Event\Event;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;

class EventRepository
{
    /**
     * Roles with users who can create events (scoped to user interactions and active)
     */
    public function getRolesWithEventCreators(): Collection
    {
        return Role::select(['id', 'name'])
            ->with([
                'users' => function ($user) {
                    $user->with(['employee', 'media'])
                        ->permission('Event Create')
                        ->select(['id', 'name', 'email'])
                        ->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                        ->whereStatus('Active')
                        ->orderBy('name');
                }
            ])
            ->whereHas('users', function ($user) {
                $user->permission('Event Create');
            })
            ->distinct()
            ->get();
    }

    /**
     * Roles with active users (for participants pickers)
     */
    public function getRolesWithUsers(): Collection
    {
        return Role::with([
            'users' => function ($query) {
                $query->with(['employee', 'media'])
                    ->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                    ->whereStatus('Active')
                    ->orderBy('name', 'asc');
            }
        ])->get();
    }

    /**
     * Base query for events list with filters applied.
     */
    public function getEventsQuery(?Request $request = null): Builder
    {
        $request = $request ?? request();

        $query = Event::with([
            'organizer.employee',
            'organizer.media',
            'participants.user.employee',
            'participants.user.media',
        ])->orderByDesc('created_at');

        if ($request->filled('organizer_id')) {
            $query->where('organizer_id', $request->organizer_id);
        }
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }
        if ($request->filled('created_month_year')) {
            $monthYear = \Carbon\Carbon::parse($request->created_month_year);
            $query->whereYear('created_at', $monthYear->year)
                  ->whereMonth('created_at', $monthYear->month);
        }

        return $query;
    }

    /**
     * Events of current user (organizer or participant)
     */
    public function getMyEvents(): Collection
    {
        return Event::with([
            'organizer.employee',
            'organizer.media',
            'participants.user.employee'
        ])->get()->filter(function ($event) {
            return $event->organizer_id === auth()->id() ||
                   $event->participants->where('user_id', auth()->id())->isNotEmpty();
        });
    }

    /**
     * Get events between two dates for calendar
     */
    public function getEventsForCalendar($start, $end): Collection
    {
        return Event::with(['organizer.employee'])
            ->whereBetween('start_date', [$start, $end])
            ->orWhereBetween('end_date', [$start, $end])
            ->get();
    }
}

