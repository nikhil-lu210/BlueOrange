<?php

namespace App\Http\Controllers\Administration\Ticket;

use App\Http\Controllers\Controller;
use App\Models\Ticket\ItTicket;
use Illuminate\Http\Request;

class ItTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('administration.ticket.it_ticket.index');
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
