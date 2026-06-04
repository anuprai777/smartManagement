@extends('layouts.app')

@section('title', 'Your Ticket')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <a href="{{ route('registrations.my') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to My Tickets
        </a>
    </div>

    <!-- Ticket Card -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <!-- Ticket Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span class="font-bold text-lg">SmartEvent</span>
                </div>
                <span class="text-sm opacity-80">Ticket</span>
            </div>
            <h2 class="text-2xl font-bold">{{ $registration->event->title }}</h2>
        </div>

        <!-- Ticket Body -->
        <div class="p-6">
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Attendee</p>
                    <p class="font-semibold text-gray-900 mt-1">{{ $registration->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $registration->user->email }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Ticket Number</p>
                    <p class="font-semibold text-gray-900 mt-1 font-mono text-sm">{{ $registration->ticket_number }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Date & Time</p>
                    <p class="font-semibold text-gray-900 mt-1">{{ $registration->event->event_date->format('M d, Y') }}</p>
                    <p class="text-sm text-gray-500">{{ $registration->event->event_date->format('h:i A') }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Venue</p>
                    <p class="font-semibold text-gray-900 mt-1">{{ $registration->event->venue ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Status -->
            <div class="mb-6">
                <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full
                    @if($registration->status === 'attended') bg-green-100 text-green-800
                    @elseif($registration->status === 'cancelled') bg-red-100 text-red-800
                    @else bg-blue-100 text-blue-800 @endif">
                    @if($registration->status === 'attended') ✓ Attended
                    @elseif($registration->status === 'cancelled') ✕ Cancelled
                    @else ● Active @endif
                </span>
            </div>

            <!-- Divider with dots -->
            <div class="border-t-2 border-dashed border-gray-200 mb-6"></div>

            <!-- QR Code -->
            <div class="text-center">
                <p class="text-sm font-medium text-gray-700 mb-4">Scan this QR code at the event</p>
                @if($registration->ticket && $registration->ticket->qr_code_path)
                <div class="inline-block p-4 bg-white rounded-xl border border-gray-200 shadow-sm">
                    <img src="{{ Storage::url($registration->ticket->qr_code_path) }}"
                         alt="QR Code"
                         class="w-48 h-48 mx-auto">
                </div>
                @else
                <div class="inline-block p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="w-48 h-48 flex items-center justify-center text-gray-400">
                        <svg class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-2">QR code will be generated upon registration.</p>
                @endif
            </div>
        </div>

        <!-- Ticket Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-xs text-gray-400">
                    <p>Registered on {{ $registration->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <button onclick="window.print()" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print Ticket
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style media="print">
    nav, footer, .sticky { display: none !important; }
    body { background: white !important; }
    .max-w-2xl { max-width: 100% !important; }
</style>
@endpush
