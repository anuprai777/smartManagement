@extends('layouts.app')

@section('title', 'My Registrations')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">My Registrations & Tickets</h1>
        <p class="text-gray-500 mt-1">View all events you've registered for.</p>
    </div>

    @if($registrations->isEmpty())
    <div class="text-center py-16 bg-white rounded-xl shadow-sm border border-gray-200">
        <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-500 mb-2">No registrations yet</h3>
        <p class="text-gray-400 mb-6">Browse events and register to get started.</p>
        <a href="{{ route('events.browse') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Browse Events
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($registrations as $registration)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
            @if($registration->event->banner_image)
            <img src="{{ Storage::url($registration->event->banner_image) }}" alt="" class="w-full h-36 object-cover">
            @else
            <div class="w-full h-36 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                <svg class="w-12 h-12 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </div>
            @endif
            <div class="p-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full
                        @if($registration->status === 'attended') bg-green-100 text-green-800
                        @elseif($registration->status === 'cancelled') bg-red-100 text-red-800
                        @else bg-blue-100 text-blue-800 @endif">
                        @if($registration->status === 'attended') Attended
                        @elseif($registration->status === 'cancelled') Cancelled
                        @else Registered @endif
                    </span>
                    <span class="text-xs text-gray-400">#{{ $registration->ticket_number }}</span>
                </div>

                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    <a href="{{ route('events.show', $registration->event) }}" class="hover:text-indigo-600 transition">{{ $registration->event->title }}</a>
                </h3>

                <div class="space-y-1.5 text-sm text-gray-500 mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ $registration->event->event_date->format('M d, Y h:i A') }}</span>
                    </div>
                    @if($registration->event->venue)
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $registration->event->venue }}</span>
                    </div>
                    @endif
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Checked in: {{ $registration->checked_in_at ? $registration->checked_in_at->format('h:i A') : 'Not yet' }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                    <a href="{{ route('registrations.ticket', $registration) }}" class="flex-1 text-center px-3 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                        View Ticket
                    </a>
                    @if($registration->status === 'registered')
                    <form method="POST" action="{{ route('registrations.cancel', $registration) }}" onsubmit="return confirm('Are you sure you want to cancel this registration?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
                            Cancel
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $registrations->links() }}
    </div>
    @endif
</div>
@endsection
