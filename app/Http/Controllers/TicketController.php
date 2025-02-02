<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::query();

        // Apply filters
        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        if ($request->has('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }
        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }
        if ($request->has('onlyOwn') && $request->onlyOwn) {
            $query->where('user_id', auth()->id());
        }

        $tickets = $query->paginate(1);

        return response()->json($tickets);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate(Ticket::rules());

        $ticket = Ticket::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'status' => 'open',
            'user_id' => auth()->id(),
        ]);

        return response()->json($ticket, 201);
    }

    public function show($id)
    {
        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }
        return response()->json($ticket, 200);
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }

        $validatedData = $request->validate(Ticket::rules());

        $ticket->update($validatedData);
        return response()->json($ticket, 200);
    }

    public function destroy($id)
    {
        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }

        $ticket->delete();
        return response()->json(['message' => 'Ticket deleted'], 200);
    }
}
