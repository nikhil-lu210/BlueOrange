<?php

namespace App\Http\Controllers\Administration\Ticket;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Ticket\ItTicket;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
                            ->whereCreatorId(auth()->user()->id)
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
            $itTicket = ItTicket::create([
                'creator_id' => auth()->user()->id,
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'status' => 'Pending',
            ]);

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
            $itTicket->update([
                'status' => 'Running',
            ]);

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
            $itTicket->update([
                'solved_by' => auth()->user()->id,
                'solved_at' => now(),
                'status' => $request->status,
                'solver_note' => $request->solver_note,
            ]);

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
        dd($itTicket->toArray());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItTicket $itTicket)
    {
        dd($request->all(), $itTicket->toArray());
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
