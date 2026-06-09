<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\Ticket;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Unified scan page — not limited to a single event.
     */
    public function scanUniversal()
    {
        return view('attendance.scan-universal');
    }

    /**
     * Unified ticket verification — auto-detects the event from QR data.
     */
    public function verifyUniversal(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string',
        ]);

        // Try to parse as QR data (JSON) first, then fall back to direct ticket number
        $qrData = json_decode($request->qr_data, true);
        $ticketNumber = $qrData && isset($qrData['ticket']) ? $qrData['ticket'] : trim($request->qr_data);

        $ticket = Ticket::where('ticket_number', $ticketNumber)
            ->with(['user', 'registration', 'event'])
            ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found.',
            ]);
        }

        // Authorize: only the event organizer can verify tickets
        if ($ticket->event->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to verify tickets for this event.',
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
            'ticket' => $ticket->load('user', 'event'),
        ]);
    }

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

        // Try to parse as QR data (JSON) first, then fall back to direct ticket number
        $qrData = json_decode($request->qr_data, true);
        $ticketNumber = $qrData && isset($qrData['ticket']) ? $qrData['ticket'] : trim($request->qr_data);

        $ticket = Ticket::where('ticket_number', $ticketNumber)
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

        $totalRegistered = Registration::where('event_id', $event->id)->count();
        $totalAttended = Registration::where('event_id', $event->id)->where('status', 'attended')->count();
        $totalPending = Registration::where('event_id', $event->id)->where('status', 'registered')->count();

        return view('attendance.attendees', compact(
            'event', 'attendees', 'totalRegistered', 'totalAttended', 'totalPending'
        ));
    }
}
