<?php

namespace App\Http\Controllers\Administration\Ticket;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Ticket\ItTicket;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\Administration\Tickets\ItTicket\ItTicketCreateNotification;
use App\Notifications\Administration\Tickets\ItTicket\ItTicketRunningNotification;
use App\Notifications\Administration\Tickets\ItTicket\ItTicketStatusUpdateNotification;

class ItTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $itTickets = ItTicket::with(['creator', 'solver'])->whereBetween('created_at', [
            Carbon::now()->startOfMonth()->format('Y-m-d'),
            Carbon::now()->endOfMonth()->format('Y-m-d')
        ])
        ->orderByDesc('created_at')
        ->get();

        return view('administration.ticket.it_ticket.index', compact(['itTickets']));
    }
    
    /**
     * Display a listing of the resource.
     */
    public function my()
    {
        $itTickets = ItTicket::with(['creator', 'solver'])
                            ->where(function ($query) {
                                $query->where('creator_id', auth()->user()->id)
                                    ->orWhere('solved_by', auth()->user()->id);
                            })
                            ->orderByDesc('created_at')
                            ->get();

        return view('administration.ticket.it_ticket.my', compact(['itTickets']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administration.ticket.it_ticket.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:200'],
            'description' => ['required', 'string', 'min:10'],
        ]);
        
        try {
            $itTicket = null;

            DB::transaction(function() use ($request, &$itTicket) {
                $itTicket = ItTicket::create([
                    'creator_id' => auth()->user()->id,
                    'title' => $request->input('title'),
                    'description' => $request->input('description'),
                    'status' => 'Pending',
                ]);

                $notifiableUsers = User::whereStatus('Active')->get();
                    
                foreach ($notifiableUsers as $key => $notifiableUser) {
                    if ($notifiableUser->hasAnyPermission(['IT Ticket Everything', 'IT Ticket Update'])) {
                        $notifiableUser->notify(new ItTicketCreateNotification($itTicket, auth()->user()));
                    }
                }
            }, 3);

            toast('Ticket Created Successfully.', 'success');
            return redirect()->route('administration.ticket.it_ticket.show', ['it_ticket' => $itTicket]);
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ItTicket $itTicket)
    {
        // Update the 'seen_by' data for the current user
        $this->updateSeenBy($itTicket);
        
        return view('administration.ticket.it_ticket.show', compact(['itTicket']));
    }


    /**
     * Update status to Running
     */
    public function markAsRunning(ItTicket $itTicket)
    {
        try {
            DB::transaction(function() use ($itTicket) {
                $itTicket->update([
                    'status' => 'Running',
                ]);

                $itTicket->creator->notify(new ItTicketRunningNotification($itTicket, auth()->user()));
            }, 3);

            toast('Ticket Mark As Running.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    /**
     * Update status to Solved or Canceled
     */
    public function updateStatus(Request $request, ItTicket $itTicket)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:Solved,Canceled'],
            'solver_note' => ['required', 'string', 'min:2'],
        ]);

        try {
            DB::transaction(function() use ($request, $itTicket) {
                $itTicket->update([
                    'solved_by' => auth()->user()->id,
                    'solved_at' => now(),
                    'status' => $request->status,
                    'solver_note' => $request->solver_note,
                ]);

                $itTicket->creator->notify(new ItTicketStatusUpdateNotification($itTicket, auth()->user()));
            }, 3);

            toast('Ticket Mark As '. $request->status, 'success');
            return redirect()->back();
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ItTicket $itTicket)
    {
        abort_if($itTicket->status !== 'Pending', 403, 'You Cannot Edit This IT Ticket.');

        return view('administration.ticket.it_ticket.edit', compact(['itTicket']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItTicket $itTicket)
    {
        abort_if($itTicket->status !== 'Pending', 403, 'You Cannot Update This IT Ticket.');

        $request->validate([
            'title' => ['sometimes', 'string', 'min:5', 'max:200'],
            'description' => ['sometimes', 'string', 'min:10'],
        ]);
        
        try {
            $itTicket->update([
                'title' => $request->input('title'),
                'description' => $request->input('description')
            ]);

            toast('Ticket Updated Successfully.', 'success');
            return redirect()->route('administration.ticket.it_ticket.show', ['it_ticket' => $itTicket]);
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItTicket $itTicket)
    {
        try {
            $itTicket->delete();

            toast('IT Ticket deleted successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }


    /**
     * Updates the 'seen_by' field of the given ItTicket for the current user.
     *
     * @param ItTicket $itTicket The ticket being viewed.
     * @return void
     */
    private function updateSeenBy(ItTicket $itTicket): void
    {
        $userId = Auth::id(); // Get the authenticated user's ID
        $currentSeenBy = $itTicket->seen_by; // Retrieve the current 'seen_by' data

        // Check if the user has already seen the ticket
        if (!collect($currentSeenBy)->contains('user_id', $userId)) {
            // Add the current user and timestamp to the 'seen_by' data
            $currentSeenBy[] = [
                'user_id' => $userId,
                'seen_at' => now()->toDateTimeString(),
            ];

            // Update the 'seen_by' field in the database
            $itTicket->update(['seen_by' => $currentSeenBy]);
        }
    }
}
