<?php

namespace App\Http\Controllers\Administration\Ticket;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Ticket\ItTicket;
use App\Http\Controllers\Controller;

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
        ])->get();

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
        dd($request->all());
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
