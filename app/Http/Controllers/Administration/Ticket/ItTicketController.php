<?php

namespace App\Http\Controllers\Administration\Ticket;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Ticket\ItTicket;
use App\Http\Controllers\Controller;
use Exception;

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
        return view('administration.ticket.it_ticket.my');
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
        dd($itTicket->toArray());
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
        dd($itTicket->toArray());
    }
}
