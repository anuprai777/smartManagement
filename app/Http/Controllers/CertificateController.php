<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Event;
use App\Models\Registration;
use App\Notifications\CertificateIssuedNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::where('user_id', auth()->id())
            ->with('event')
            ->latest()
            ->paginate(10);
        return view('certificates.index', compact('certificates'));
    }

    public function generate(Event $event)
    {
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        $attendees = Registration::where('event_id', $event->id)
            ->where('status', 'attended')
            ->whereDoesntHave('certificate')
            ->with('user')
            ->get();

        if ($attendees->isEmpty()) {
            return back()->with('info', 'All attendees already have certificates or no attendees found.');
        }

        $count = 0;
        foreach ($attendees as $registration) {
            $certNumber = 'CERT-' . strtoupper(Str::random(10));

            $certificate = Certificate::create([
                'event_id' => $event->id,
                'user_id' => $registration->user_id,
                'registration_id' => $registration->id,
                'certificate_number' => $certNumber,
                'issued_at' => now(),
            ]);

            // Generate PDF
            $pdf = Pdf::loadView('certificates.template', [
                'certificate' => $certificate,
                'event' => $event,
                'user' => $registration->user,
            ]);

            $filename = 'certificates/' . $certNumber . '.pdf';
            \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $pdf->output());

            $certificate->update(['certificate_path' => $filename]);

            // Notify the attendee
            $registration->user->notify(new CertificateIssuedNotification($event, $certificate));
            $count++;
        }

        return back()->with('success', "{$count} certificate(s) generated successfully!");
    }

    public function download(Certificate $certificate)
    {
        if ($certificate->user_id !== auth()->id() && 
            $certificate->event->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$certificate->certificate_path || 
            !\Illuminate\Support\Facades\Storage::disk('public')->exists($certificate->certificate_path)) {
            abort(404, 'Certificate file not found.');
        }

        return \Illuminate\Support\Facades\Storage::disk('public')
            ->download($certificate->certificate_path, 
                'certificate-' . $certificate->certificate_number . '.pdf');
    }

    public function eventCertificates(Event $event)
    {
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        $certificates = Certificate::where('event_id', $event->id)
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('certificates.event', compact('event', 'certificates'));
    }
}
