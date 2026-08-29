<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\Ticket;
use App\Models\User;
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

    /**
     * Join an event by scanning the private registration QR code.
     * Attendees scan → if logged in, they're registered instantly (ticket + QR created).
     * If not logged in, they're sent to login and returned here afterwards.
     */
    public function join(Event $event)
    {
        if (!auth()->check()) {
            session()->flash('info', 'Sign in to register for this event.');
            return redirect()->guest(route('login'));
        }

        if ($event->status !== 'published') {
            return redirect()->route('events.show', $event)
                ->with('error', 'This event is not open for registration.');
        }

        $existing = Registration::where('event_id', $event->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            return redirect()->route('registrations.ticket', $existing)
                ->with('info', 'You are already registered for this event. Here is your ticket.');
        }

        if ($event->isFull()) {
            return redirect()->route('events.show', $event)
                ->with('error', 'This event has reached full capacity.');
        }

        $this->createTicketForUser($event, auth()->user());

        return redirect()->route('registrations.my')
            ->with('success', 'Welcome aboard! Your ticket is ready.');
    }

    /**
     * Return the private registration QR code as a PNG image.
     * Scanning it opens the join URL so attendees can register themselves.
     */
    public function joinQr(Event $event)
    {
        $url = route('events.join', $event);

        $result = (new Builder(
            writer: new PngWriter(),
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 400,
            margin: 20,
        ))->build();

        return response($result->getString(), 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'inline; filename="register-' . $event->id . '.png"',
        ]);
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

    /**
     * Generate tickets in bulk for invited guests (private events).
     * Accepts a list of emails (newline / comma / semicolon separated) and/or a CSV file.
     * Only the event owner (or an admin) can do this.
     */
    public function generateTicket(Request $request, Event $event)
    {
        if ($event->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'emails' => 'nullable|string',
            'csv' => 'nullable|file|mimes:csv,txt|max:4096',
        ]);

        $emails = $this->extractEmails($request);

        if (empty($emails)) {
            return back()->with('error', 'Please provide at least one valid email address, or upload a CSV file.');
        }

        $created = 0;
        $skippedNoAccount = [];
        $skippedDuplicate = [];
        $stoppedFull = false;

        foreach ($emails as $email) {
            $user = User::where('email', $email)->first();

            if (!$user) {
                $skippedNoAccount[] = $email;
                continue;
            }

            $existing = Registration::where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->first();

            if ($existing) {
                $skippedDuplicate[] = $email;
                continue;
            }

            if ($event->isFull()) {
                $stoppedFull = true;
                break;
            }

            $this->createTicketForUser($event, $user);
            $created++;
        }

        // Build a human-readable summary
        $summary = [];
        if ($created > 0) {
            $summary[] = "{$created} ticket" . ($created === 1 ? '' : 's') . " generated";
        }
        if (count($skippedDuplicate) > 0) {
            $summary[] = count($skippedDuplicate) . " skipped (already registered)";
        }
        if (count($skippedNoAccount) > 0) {
            $summary[] = count($skippedNoAccount) . " skipped (no account)";
        }
        if ($stoppedFull) {
            $summary[] = "stopped at full capacity";
        }

        $message = implode(', ', $summary) ?: 'No tickets were generated.';

        // Include a couple of skipped emails as examples
        $examples = array_merge(array_slice($skippedNoAccount, 0, 2), array_slice($skippedDuplicate, 0, 2));
        if (!empty($examples)) {
            $message .= ' (e.g. ' . implode(', ', $examples) . ')';
        }

        return $created > 0
            ? back()->with('success', $message)
            : back()->with('error', $message);
    }

    /**
     * Pull emails from the text field and/or uploaded CSV file.
     */
    private function extractEmails(Request $request): array
    {
        $emails = [];

        // From textarea / single email field — split on whitespace, commas, semicolons
        $raw = $request->input('emails') ?: ($request->input('email') ?: '');
        foreach (preg_split('/[\s,;]+/', trim($raw)) ?: [] as $part) {
            $part = trim($part);
            if (filter_var($part, FILTER_VALIDATE_EMAIL)) {
                $emails[] = strtolower($part);
            }
        }

        // From uploaded CSV file (emails in first column, optional header row)
        if ($request->hasFile('csv')) {
            $lines = file($request->file('csv')->getRealPath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if ($lines !== false) {
                $start = 0;
                if (isset($lines[0]) && stripos($lines[0], 'email') !== false) {
                    $start = 1; // skip header row
                }
                for ($i = $start, $n = count($lines); $i < $n; $i++) {
                    $col = trim(explode(',', $lines[$i])[0], " \t\"'");
                    if (filter_var($col, FILTER_VALIDATE_EMAIL)) {
                        $emails[] = strtolower($col);
                    }
                }
            }
        }

        return array_values(array_unique($emails));
    }

    /**
     * Create a registration + ticket + QR code for a given user on an event.
     */
    private function createTicketForUser(Event $event, User $user): Ticket
    {
        $ticketNumber = $this->generateTicketNumber($event);

        $registration = Registration::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'ticket_number' => $ticketNumber,
            'status' => 'registered',
        ]);

        $qrData = json_encode([
            'ticket' => $ticketNumber,
            'event' => $event->id,
            'user' => $user->id,
            'email' => $user->email,
        ]);

        $ticket = Ticket::create([
            'registration_id' => $registration->id,
            'event_id' => $event->id,
            'user_id' => $user->id,
            'ticket_number' => $ticketNumber,
            'qr_code_data' => $qrData,
            'status' => 'active',
        ]);

        $this->generateQrCode($ticket);

        try {
            $user->notify(new EventRegistrationNotification($event, $user, 'registration_confirmed'));
        } catch (\Throwable $e) {
            // Ignore notification failures - ticket is still generated
        }

        return $ticket;
    }

    private function generateTicketNumber(Event $event): string
    {
        $prefix = strtoupper(Str::substr(preg_replace('/[^A-Za-z0-9]/', '', $event->title), 0, 3));
        return $prefix . '-' . strtoupper(Str::random(8)) . '-' . $event->id;
    }

    private function generateQrCode(Ticket $ticket): void
    {
        $result = (new Builder(
            writer: new PngWriter(),
            data: $ticket->qr_code_data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
        ))->build();

        $filename = 'qr-codes/' . $ticket->ticket_number . '.png';
        \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $result->getString());

        $ticket->update(['qr_code_path' => $filename]);
    }
}
