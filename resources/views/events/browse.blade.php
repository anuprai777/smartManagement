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
            <div class="bg-white rounded-xl shadow-sm border border-amber-200/60 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 flex flex-col group">
                <!-- Banner -->
                <div class="relative h-36 overflow-hidden">
                    @if($event->banner_image)
                    <img src="{{ Storage::url($event->banner_image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                    <div class="w-full h-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center">
                        <svg class="w-12 h-12 text-white/70" fill="currentColor" viewBox="0 0 20 20"><path d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"/></svg>
                    </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    <!-- AI badge -->
                    <div class="absolute top-3 left-3 inline-flex items-center gap-1 px-2 py-1 text-[10px] font-bold rounded-lg bg-amber-400 text-amber-950 shadow-sm">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                        AI Pick
                    </div>
                    <!-- Online/Offline -->
                    <div class="absolute top-3 right-3 inline-flex items-center gap-1 px-2 py-1 text-[10px] font-semibold rounded-lg backdrop-blur {{ ($event->venue_type ?? 'offline') === 'online' ? 'bg-violet-500/90 text-white' : 'bg-emerald-500/90 text-white' }}">
                        {{ ($event->venue_type ?? 'offline') === 'online' ? 'Online' : 'On-site' }}
                    </div>
                    <!-- Date -->
                    <div class="absolute bottom-0 inset-x-0 p-3 flex items-center justify-between">
                        <span class="text-[11px] font-medium text-white/90">{{ $event->event_date->format('D, M j') }}</span>
                        @if(($event->price ?? 0) > 0)
                        <span class="text-xs font-bold text-white">Rs. {{ number_format($event->price) }}</span>
                        @else
                        <span class="inline-flex items-center px-1.5 py-0.5 text-[9px] font-semibold rounded bg-white/90 text-emerald-700">Free</span>
                        @endif
                    </div>
                </div>
                <!-- Body -->
                <div class="p-4 flex flex-col flex-1">
                    <h3 class="text-sm font-semibold text-gray-900 mb-2 leading-snug">
                        <a href="{{ route('events.show', $event) }}" class="hover:text-indigo-600 transition">{{ $event->title }}</a>
                    </h3>
                    @if($event->venue)
                    <p class="text-xs text-gray-400 truncate flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 shrink-0 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            @if(($event->venue_type ?? 'offline') === 'online')
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.38-3.72a4 4 0 010-5.657l4-4a4 4 0 015.656 5.656l-1.1 1.1"/>
                            @else
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            @endif
                        </svg>
                        {{ $event->venue }}
                    </p>
                    @endif
                    <div class="flex items-center justify-between mt-auto pt-3">
                        <span class="text-xs text-gray-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $event->registrations_count }}/{{ $event->capacity > 0 ? $event->capacity : '∞' }}
                        </span>
                        <a href="{{ route('events.show', $event) }}" class="text-xs font-medium text-amber-600 hover:text-amber-800">
                            View →
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
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 flex flex-col group">
            <!-- Picture Banner -->
            <div class="relative h-44 overflow-hidden">
                @if($event->banner_image)
                <img src="{{ Storage::url($event->banner_image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                @else
                <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                    <svg class="w-14 h-14 text-white/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                @endif
                <!-- Bottom gradient overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>

                <!-- Date badge -->
                <div class="absolute top-3 left-3 bg-white/95 backdrop-blur rounded-lg px-2 py-1.5 text-center shadow-sm">
                    <span class="block text-sm font-bold text-indigo-600 leading-none">{{ $event->event_date->format('d') }}</span>
                    <span class="block text-[9px] font-semibold uppercase tracking-wide text-gray-500 mt-0.5">{{ $event->event_date->format('M') }}</span>
                </div>

                <!-- Online / Offline badge -->
                <div class="absolute top-3 right-3 inline-flex items-center gap-1 px-2 py-1 text-[10px] font-semibold rounded-lg backdrop-blur {{ ($event->venue_type ?? 'offline') === 'online' ? 'bg-violet-500/90 text-white' : 'bg-emerald-500/90 text-white' }}">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        @if(($event->venue_type ?? 'offline') === 'online')
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.38-3.72a4 4 0 010-5.657l4-4a4 4 0 015.656 5.656l-1.1 1.1"/>
                        @else
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        @endif
                    </svg>
                    {{ ($event->venue_type ?? 'offline') === 'online' ? 'Online' : 'On-site' }}
                </div>

                <!-- Title on banner -->
                <div class="absolute bottom-0 inset-x-0 p-4">
                    <a href="{{ route('events.show', $event) }}" class="text-base font-semibold text-white leading-snug line-clamp-2 group-hover:text-indigo-200 transition">{{ $event->title }}</a>
                </div>
            </div>

            <!-- Body -->
            <div class="p-4 flex flex-col flex-1">
                <div class="flex items-center justify-between text-xs">
                    <span class="inline-flex items-center px-2 py-0.5 font-medium rounded-full
                        @if($event->status === 'published') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($event->status) }}
                    </span>
                    <span class="inline-flex items-center gap-1 text-gray-400">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $event->registrations_count }}/{{ $event->capacity > 0 ? $event->capacity : '∞' }} registered
                    </span>
                </div>

                <div class="mt-3 space-y-1.5 text-sm text-gray-500">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ $event->event_date->format('D, M j, Y') }}</span>
                        <span class="text-gray-300">·</span>
                        <span>{{ $event->event_date->format('h:i A') }}</span>
                    </div>
                    @if($event->venue)
                    <div class="flex items-center gap-2">
                        @if(($event->venue_type ?? 'offline') === 'online')
                        <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.38-3.72a4 4 0 010-5.657l4-4a4 4 0 015.656 5.656l-1.1 1.1"/></svg>
                        @else
                        <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        @endif
                        <span class="truncate">{{ $event->venue }}</span>
                    </div>
                    @endif
                </div>

                <div class="flex items-center justify-between gap-3 mt-4 pt-3 border-t border-gray-100">
                    <span class="text-sm font-bold text-gray-900">
                        @if(($event->price ?? 0) > 0)
                        Rs. {{ number_format($event->price, 2) }}
                        @else
                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Free</span>
                        @endif
                    </span>
                    <a href="{{ route('events.show', $event) }}" class="inline-flex items-center gap-1 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                        View Details
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
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
