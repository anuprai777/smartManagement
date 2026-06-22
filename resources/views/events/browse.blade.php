@extends('layouts.app')

@section('title', 'Browse Events')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Browse Events</h1>
            <p class="text-gray-500 mt-1">Discover and register for upcoming events.</p>
        </div>
    </div>

    <!-- AI-Powered Recommendations -->
    @auth
    @if($recommendedEvents->isNotEmpty())
    <div class="mb-10">
        <div class="flex items-center gap-2.5 mb-5">
            <div class="w-8 h-8 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center shadow-sm">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-lg font-bold text-gray-900">Recommended for You</h2>
                    <a href="{{ route('preferences.edit') }}" class="text-xs font-medium text-amber-600 hover:text-amber-800 transition flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/>
                        </svg>
                        Customize Interests
                    </a>
                </div>
                <p class="text-xs text-gray-400">AI-powered picks based on your interests and registration history</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($recommendedEvents as $event)
            <div class="bg-white rounded-xl shadow-sm border border-amber-200/60 overflow-hidden hover:shadow-md transition group">
                @if($event->banner_image)
                <img src="{{ Storage::url($event->banner_image) }}" alt="{{ $event->title }}" class="w-full h-36 object-cover">
                @else
                <div class="w-full h-36 bg-gradient-to-br from-amber-50 to-orange-100 flex items-center justify-center">
                    <svg class="w-12 h-12 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"/>
                    </svg>
                </div>
                @endif
                <div class="p-4">
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-medium rounded-full bg-amber-100 text-amber-700">AI Pick</span>
                        <span class="text-xs text-gray-400">{{ $event->event_date->format('M d, Y') }}</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-1 group-hover:text-indigo-600 transition">
                        <a href="{{ route('events.show', $event) }}">{{ $event->title }}</a>
                    </h3>
                    @if($event->venue)
                    <p class="text-xs text-gray-400 truncate">{{ $event->venue }}</p>
                    @endif
                    <div class="flex items-center justify-between mt-3">
                        <span class="text-xs text-gray-400">
                            {{ $event->registrations_count }}/{{ $event->capacity > 0 ? $event->capacity : '∞' }} registered
                        </span>
                        <a href="{{ route('events.show', $event) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">
                            View Details →
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endauth

    <!-- All Events -->
    @auth
    @if($recommendedEvents->isNotEmpty())
    <div class="flex items-center gap-3 mb-6">
        <div class="flex-1 h-px bg-gray-200"></div>
        <span class="text-sm font-medium text-gray-400">All Events</span>
        <div class="flex-1 h-px bg-gray-200"></div>
    </div>
    @endif
    @endauth

    @if($events->isEmpty())
    <div class="text-center py-16">
        <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-500 mb-2">No events available</h3>
        <p class="text-gray-400">Check back later for upcoming events.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($events as $event)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
            @if($event->banner_image)
            <img src="{{ Storage::url($event->banner_image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
            @else
            <div class="w-full h-48 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                <svg class="w-16 h-16 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </div>
            @endif
            <div class="p-5">
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full
                        @if($event->status === 'published') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($event->status) }}
                    </span>
                    @if($event->isPublic())
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-medium rounded-full bg-sky-100 text-sky-700">Public</span>
                    @endif
                    <span class="text-xs text-gray-400">
                        <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $event->registrations_count }}/{{ $event->capacity > 0 ? $event->capacity : '∞' }} registered
                    </span>
                </div>

                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    <a href="{{ route('events.show', $event) }}" class="hover:text-indigo-600 transition">{{ $event->title }}</a>
                </h3>

                <div class="space-y-2 text-sm text-gray-500 mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ $event->event_date->format('l, F j, Y') }}</span>
                    </div>
                    @if($event->venue)
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $event->venue }}</span>
                    </div>
                    @endif
                </div>

                <div class="flex items-center gap-3 mt-4">
                    <a href="{{ route('events.show', $event) }}" class="flex-1 text-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $events->links() }}
    </div>
    @endif
</div>
@endsection
