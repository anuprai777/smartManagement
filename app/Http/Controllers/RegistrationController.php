<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\Ticket;
use App\Notifications\EventRegistrationNotification;
use Illuminate\Http\Request;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function myRegistrations()
    {
        $registrations = Registration::where('user_id', auth()->id())
            ->with(['event', 'ticket'])
            ->latest()
            ->paginate(10);
        return view('registrations.my', compact('registrations'));
    }

    public function register(Event $event)
    {
        if (!$event->isRegistrationOpen()) {
            return back()->with('error', 'Registration is not available for this event.');
        }

        $existing = Registration::where('event_id', $event->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            return back()->with('error', 'You are already registered for this event.');
        }

        $ticketNumber = $this->generateTicketNumber($event);

        $registration = Registration::create([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'ticket_number' => $ticketNumber,
            'status' => 'registered',
        ]);

        // Generate QR code data
        $qrData = json_encode([
            'ticket' => $ticketNumber,
            'event' => $event->id,
            'user' => auth()->id(),
            'email' => auth()->user()->email,
        ]);

        // Create ticket
        $ticket = Ticket::create([
            'registration_id' => $registration->id,
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'ticket_number' => $ticketNumber,
            'qr_code_data' => $qrData,
            'status' => 'active',
        ]);

        // Generate QR code image
        $this->generateQrCode($ticket);

        // Send notifications
        $attendee = auth()->user();
        // Notify the event organizer
        $event->organizer->notify(new EventRegistrationNotification($event, $attendee, 'new_registration'));
        // Notify the attendee
        $attendee->notify(new EventRegistrationNotification($event, $attendee, 'registration_confirmed'));

        return redirect()->route('registrations.my')
            ->with('success', 'Registration successful! Your ticket is ready.');
    }

    public function showTicket(Registration $registration)
    {
        if ($registration->user_id !== auth()->id() && 
            $registration->event->user_id !== auth()->id()) {
            abort(403);
        }

        $registration->load(['event', 'ticket', 'user']);
        return view('registrations.ticket', compact('registration'));
    }

    public function cancel(Registration $registration)
    {
        if ($registration->user_id !== auth()->id()) {
            abort(403);
        }

        $registration->update(['status' => 'cancelled']);
        $registration->ticket()->update(['status' => 'cancelled']);

        return back()->with('success', 'Registration cancelled successfully.');
    }

    private function generateTicketNumber(Event $event): string
    {
        $prefix = strtoupper(Str::substr(preg_replace('/[^A-Za-z0-9]/', '', $event->title), 0, 3));
        return $prefix . '-' . strtoupper(Str::random(8)) . '-' . $event->id;
    }

    private function generateQrCode(Ticket $ticket): void
    {
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($ticket->qr_code_data)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevel\ErrorCorrectionLevelHigh())
            ->size(300)
            ->margin(10)
            ->build();

        $filename = 'qr-codes/' . $ticket->ticket_number . '.png';
        \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $result->getString());

        $ticket->update(['qr_code_path' => $filename]);
    }
}
