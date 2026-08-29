@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Welcome, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-500 text-sm mt-1">Here's what's happening with your events.</p>
        </div>
        <a href="{{ route('events.create') }}" class="btn-primary mt-4 sm:mt-0 !py-2 !px-4 text-sm">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Create Event
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">My Events</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $myEventsCount }}</p>
                </div>
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Upcoming</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $upcomingEventsCount }}</p>
                </div>
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Registrations</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalRegistrations }}</p>
                </div>
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Attendees</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalAttendees }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Recommendations + My Registrations -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- AI Recommendations Sidebar -->
        @if($recommendedEvents->isNotEmpty())
        <div class="bg-gradient-to-br from-amber-50 to-orange-50/50 rounded-xl border border-amber-200/60 p-4 flex flex-col"
            x-data="{
                active: 0,
                total: {{ ceil($recommendedEvents->count() / 2) }},
                playing: true,
                next() { this.active = (this.active + 1) % this.total; },
                prev() { this.active = (this.active - 1 + this.total) % this.total; },
                go(i) { this.active = i; }
            }"
            x-init="setInterval(() => { if ($data.playing) $data.next(); }, 5000)"
            @mouseenter="$data.playing = false" @mouseleave="$data.playing = true">
            <!-- Header -->
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xs font-bold text-amber-900">AI Recommendations</h2>
                        <p class="text-[9px] text-amber-600">Based on your interests</p>
                    </div>
                </div>
                <a href="{{ route('preferences.edit') }}" class="text-[9px] font-medium text-amber-700 hover:text-amber-900 bg-amber-100/80 px-1.5 py-0.5 rounded-lg transition">
                    Edit
                </a>
            </div>

            <!-- Carousel viewport -->
            <div class="relative flex-1 overflow-hidden rounded-xl">
                <!-- Sliding track: each slide shows 2 cards stacked vertically -->
                <div class="flex transition-transform duration-500 ease-in-out"
                    :style="`transform: translateX(-${active * 100}%)`">
                    @foreach($recommendedEvents->chunk(2) as $pair)
                    <div class="w-full shrink-0">
                        <div class="flex flex-col gap-3">
                            @foreach($pair as $event)
                            <a href="{{ route('events.show', $event) }}" class="block group">
                                <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-amber-100/60">
                                    <!-- Banner -->
                                    <div class="relative h-28 overflow-hidden">
                                        @if($event->banner_image)
                                        <img src="{{ Storage::url($event->banner_image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        @else
                                        <div class="w-full h-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white/70" fill="currentColor" viewBox="0 0 20 20"><path d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                                        </div>
                                        @endif
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                                        <span class="absolute top-2 left-2 inline-flex items-center gap-1 px-1.5 py-0.5 text-[9px] font-bold rounded bg-amber-400 text-amber-950 shadow-sm">
                                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                                            AI
                                        </span>
                                        <div class="absolute top-2 right-2 inline-flex items-center px-1.5 py-0.5 text-[9px] font-semibold rounded bg-white/90 text-gray-700 shadow-sm">
                                            {{ ($event->venue_type ?? 'offline') === 'online' ? 'Online' : 'On-site' }}
                                        </div>
                                        <div class="absolute bottom-0 inset-x-0 p-2.5">
                                            <p class="text-[13px] font-semibold text-white leading-snug line-clamp-2 group-hover:text-amber-200 transition">{{ $event->title }}</p>
                                        </div>
                                    </div>
                                    <!-- Details -->
                                    <div class="px-3 py-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-[11px] text-gray-500 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                {{ $event->event_date->format('M d, Y') }}
                                            </span>
                                            @if(($event->price ?? 0) > 0)
                                            <span class="text-[11px] font-bold text-amber-700">Rs. {{ number_format($event->price) }}</span>
                                            @else
                                            <span class="inline-flex items-center px-1.5 py-0.5 text-[9px] font-semibold rounded bg-emerald-100 text-emerald-700">Free</span>
                                            @endif
                                        </div>
                                        @if($event->venue)
                                        <p class="text-[10px] text-gray-400 truncate mt-1 flex items-center gap-1">
                                            <svg class="w-3 h-3 shrink-0 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                @if(($event->venue_type ?? 'offline') === 'online')
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.38-3.72a4 4 0 010-5.657l4-4a4 4 0 015.656 5.656l-1.1 1.1"/>
                                                @else
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                @endif
                                            </svg>
                                            {{ $event->venue }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Prev/Next arrows -->
                <button @click="prev()" class="absolute left-1.5 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-white/90 shadow-md flex items-center justify-center text-gray-700 hover:bg-white hover:text-amber-700 transition z-10">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button @click="next()" class="absolute right-1.5 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-white/90 shadow-md flex items-center justify-center text-gray-700 hover:bg-white hover:text-amber-700 transition z-10">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            <!-- Dots -->
            <div class="flex items-center justify-center gap-1.5 mt-3">
                @foreach($recommendedEvents->chunk(2) as $pair)
                <button @click="go({{ $loop->index }})" class="h-1.5 rounded-full transition-all duration-300" :class="active === {{ $loop->index }} ? 'w-4 bg-amber-500' : 'w-1.5 bg-amber-300 hover:bg-amber-400'"></button>
                @endforeach
            </div>

            <a href="{{ route('events.browse') }}" class="mt-3 block text-center text-[10px] font-medium text-amber-700 hover:text-amber-900 bg-amber-100/60 py-1.5 rounded-lg hover:bg-amber-200/60 transition">
                View all {{ $recommendedEvents->count() }} recommendations →
            </a>
        </div>
        @else
        <div class="bg-white rounded-xl border border-dashed border-gray-200 p-6 flex flex-col items-center justify-center text-center">
            <svg class="w-10 h-10 text-amber-300 mb-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
            <p class="text-xs text-gray-400 font-medium mb-1">AI Recommendations</p>
            <a href="{{ route('preferences.edit') }}" class="text-[10px] text-amber-600 hover:text-amber-800 font-medium">Set your interests →</a>
        </div>
        @endif

        <!-- My Registrations -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                    My Registrations
                </h2>
                <a href="{{ route('registrations.my') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800 transition">View All →</a>
            </div>
            @if($myRegistrations->isNotEmpty())
            <div class="space-y-3">
                @foreach($myRegistrations as $reg)
                <div class="flex items-start gap-3 p-3 bg-gray-50/80 rounded-xl hover:bg-gray-100 transition">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $reg->event->title }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $reg->event->event_date->format('M d, Y') }}</p>
                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full mt-1.5
                            @if($reg->status === 'attended') bg-emerald-100 text-emerald-700
                            @elseif($reg->status === 'cancelled') bg-red-100 text-red-700
                            @else bg-blue-100 text-blue-700 @endif">
                            {{ ucfirst($reg->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="flex flex-col items-center py-8 text-gray-400">
                <svg class="w-12 h-12 mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
                <p class="font-medium">No registrations yet</p>
                <a href="{{ route('events.browse') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium mt-2">Browse Events →</a>
            </div>
            @endif
        </div>
    </div>

    <!-- Recent Events -->
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide flex items-center gap-1.5">
                <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Recent Events
            </h2>
            <a href="{{ route('events.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">View All →</a>
        </div>
        @if($recentEvents->isNotEmpty())
        <div class="overflow-x-auto -mx-6">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left">
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Date</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Registrations</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentEvents as $event)
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="px-6 py-3.5 font-medium text-gray-900">
                            <a href="{{ route('events.show', $event) }}" class="hover:text-indigo-600 transition">{{ $event->title }}</a>
                        </td>
                        <td class="px-6 py-3.5 hidden sm:table-cell text-gray-400">{{ $event->event_date->format('M d, Y') }}</td>
                        <td class="px-6 py-3.5">
                            <span class="font-semibold text-gray-700">{{ $event->registrations_count }}</span>
                            <span class="text-gray-400">/ {{ $event->capacity > 0 ? $event->capacity : '∞' }}</span>
                        </td>
                        <td class="px-6 py-3.5">
                            @if($event->status === 'published')
                            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">Published</span>
                            @elseif($event->status === 'draft')
                            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-600">Draft</span>
                            @elseif($event->status === 'completed')
                            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-700">Completed</span>
                            @else
                            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-700">Cancelled</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="flex flex-col items-center py-8 text-gray-400">
            <svg class="w-12 h-12 mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="font-medium">No events yet</p>
            <a href="{{ route('events.create') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium mt-2">Create your first event →</a>
        </div>
        @endif
    </div>
</div>
@endsection
