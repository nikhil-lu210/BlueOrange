<?php

namespace App\Http\Controllers\Administration\Event;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Repositories\Administration\Event\EventRepository;
use App\Services\Administration\Event\EventService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Event\Event;
use App\Http\Requests\Administration\Event\EventStoreRequest;
use App\Http\Requests\Administration\Event\EventUpdateRequest;

class EventController extends Controller
{
    protected EventService $eventService;
    protected EventRepository $eventRepository;

    public function __construct(EventService $eventService, EventRepository $eventRepository)
    {
        $this->eventService = $eventService;
        $this->eventRepository = $eventRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = $this->eventRepository->getRolesWithEventCreators();
        $events = $this->eventRepository->getEventsQuery($request)->get();
        return view('administration.event.index', compact(['roles', 'events']));
    }

    /**
     * Display a listing of the resource.
     */
    public function my()
    {
        $events = Event::with([
            'organizer.employee',
            'organizer.media',
            'participants.user.employee'
        ])->get()->filter(function ($event) {
            return $event->organizer_id === auth()->id() || 
                   $event->participants->where('user_id', auth()->id())->isNotEmpty();
        });

        return view('administration.event.my', compact(['events']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = $this->eventRepository->getRolesWithUsers();
        return view('administration.event.create', compact(['roles']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $event = $this->eventService->create($request->validated());
            DB::commit();

            return redirect()->route('administration.event.index')
                           ->with('success', 'Event created successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Failed to create event: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load([
            'organizer.employee',
            'organizer.media',
            'participants.user.employee',
            'participants.user.media'
        ]);

        return view('administration.event.show', compact(['event']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $roles = $this->eventRepository->getRolesWithUsers();
        $event->load(['participants.user']);
        return view('administration.event.edit', compact(['event', 'roles']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventUpdateRequest $request, Event $event)
    {
        try {
            DB::beginTransaction();

            $this->eventService->update($event, $request->validated());
            DB::commit();

            return redirect()->route('administration.event.index')
                           ->with('success', 'Event updated successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Failed to update event: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        try {
            $event->delete();
            return redirect()->route('administration.event.index')
                           ->with('success', 'Event deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to delete event: ' . $e->getMessage());
        }
    }

    /**
     * Get events for calendar view.
     */
    public function calendar(Request $request)
    {
        $start = $request->get('start', now()->startOfMonth());
        $end = $request->get('end', now()->endOfMonth());

        $events = Event::with(['organizer.employee'])
                      ->whereBetween('start_date', [$start, $end])
                      ->orWhereBetween('end_date', [$start, $end])
                      ->get()
                      ->map(function ($event) {
                          $end = $event->is_all_day
                              ? Carbon::parse($event->end_date)->copy()->addDay()->toDateString()
                              : $event->end_date . ($event->end_time ? 'T' . $event->end_time : '');

                          return [
                              'id' => $event->id,
                              'title' => $event->title,
                              'start' => $event->start_date . ($event->start_time ? 'T' . $event->start_time : ''),
                              'end' => $end,
                              'allDay' => $event->is_all_day,
                              'color' => $event->color,
                              'url' => route('administration.event.show', $event),
                              'extendedProps' => [
                                  'event_type' => $event->event_type_label,
                                  'location' => $event->location,
                                  'organizer' => $event->organizer->name ?? 'Unknown'
                              ]
                          ];
                      });

        return response()->json($events);
    }

    /**
     * Update event date/time (for drag and drop).
     */
    public function updateDateTime(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
        ]);

        try {
            $event->update([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
            ]);

            return response()->json(['success' => true, 'message' => 'Event updated successfully']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update event'], 500);
        }
    }
}
