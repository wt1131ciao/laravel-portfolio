<?php

namespace App\Http\Controllers;

use App\Models\Ticket;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('is_active', true)->latest()->get();
        return view('tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        abort_if(!$ticket->is_active, 404);
        return view('tickets.show', compact('ticket'));
    }
}
