<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\Ticket;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function scanPage(Event $event)
    {
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }
        return view('attendance.scan', compact('event'));
    }

    public function verifyTicket(Request $request, Event $event)
    {
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'qr_data' => 'required|string',
        ]);

        $qrData = json_decode($request->qr_data, true);

        if (!$qrData || !isset($qrData['ticket'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code format.',
            ]);
        }

        $ticket = Ticket::where('ticket_number', $qrData['ticket'])
            ->with(['user', 'registration'])
            ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found.',
            ]);
        }

        if ($ticket->event_id !== $event->id) {
            return response()->json([
                'success' => false,
                'message' => 'This ticket is for a different event.',
            ]);
        }

        if ($ticket->status === 'used') {
            return response()->json([
                'success' => false,
                'message' => 'This ticket has already been used. Scanned at: ' . 
                    ($ticket->scanned_at ? $ticket->scanned_at->format('M d, Y h:i A') : 'Unknown'),
                'ticket' => $ticket,
            ]);
        }

        if ($ticket->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'This registration has been cancelled.',
            ]);
        }

        // Mark as attended
        $ticket->update([
            'status' => 'used',
            'scanned_at' => now(),
        ]);

        $ticket->registration->update([
            'status' => 'attended',
            'checked_in_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in successful! Welcome ' . $ticket->user->name . '!',
            'ticket' => $ticket->load('user'),
        ]);
    }

    public function attendees(Event $event)
    {
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        $attendees = Registration::where('event_id', $event->id)
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('attendance.attendees', compact('event', 'attendees'));
    }
}
