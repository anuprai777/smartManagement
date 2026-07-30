@extends('layouts.app')

@section('title', 'Manage Events')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manage Events</h1>
            <p class="text-gray-500 mt-1">View and manage all events across the platform.</p>
        </div>
    </div>

    <!-- Status filter tabs -->
    <div class="flex flex-wrap items-center gap-2 mb-6">
        <a href="{{ route('admin.events') }}"
           class="px-4 py-2 text-sm font-medium rounded-xl transition {{ !request('status') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            All
        </a>
        @foreach(['published', 'draft', 'completed', 'cancelled'] as $s)
        <a href="{{ route('admin.events', array_merge(request()->only(['search', 'visibility']), ['status' => $s])) }}"
           class="px-4 py-2 text-sm font-medium rounded-xl transition {{ request('status') === $s ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            {{ ucfirst($s) }}
        </a>
        @endforeach
    </div>

    <!-- Search & filter -->
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <form method="GET" action="{{ route('admin.events') }}" class="flex-1 flex gap-3">
            @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative flex-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search events by title or venue..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-300 focus:ring focus:ring-indigo-200/50 text-sm">
            </div>
            <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition">Search</button>
            @if(request('search') || request('status') || request('visibility'))
            <a href="{{ route('admin.events') }}" class="px-4 py-2.5 text-sm font-medium text-gray-500 hover:text-gray-700 transition flex items-center">Clear</a>
            @endif
        </form>
    </div>

    @php
        $eUrl = fn($col) => route('admin.events', array_merge(
            request()->only(['search', 'status']),
            ['sort' => $col, 'direction' => $sort === $col && $dir === 'asc' ? 'desc' : 'asc']
        ));
        $eIcon = fn($col) => $sort === $col ? ($dir === 'asc' ? '↑' : '↓') : '⇅';
        $eCls  = fn($col) => $sort === $col ? 'text-indigo-700' : 'text-gray-600';
    @endphp

    <!-- Events Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($events->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left bg-gray-50 border-b border-gray-200">
                        <th onclick="window.location='{{ $eUrl('title') }}'"
                            class="px-6 py-4 font-semibold cursor-pointer select-none hover:text-indigo-700 transition {{ $eCls('title') }}">
                            <span class="flex items-center gap-1">Event <span class="text-[11px] opacity-50">{{ $eIcon('title') }}</span></span>
                        </th>
                        <th class="px-6 py-4 font-semibold text-gray-600 hidden md:table-cell">Organizer</th>
                        <th onclick="window.location='{{ $eUrl('event_date') }}'"
                            class="px-6 py-4 font-semibold hidden sm:table-cell cursor-pointer select-none hover:text-indigo-700 transition {{ $eCls('event_date') }}">
                            <span class="flex items-center gap-1">Date <span class="text-[11px] opacity-50">{{ $eIcon('event_date') }}</span></span>
                        </th>
                        <th onclick="window.location='{{ $eUrl('registrations_count') }}'"
                            class="px-6 py-4 font-semibold text-center cursor-pointer select-none hover:text-indigo-700 transition {{ $eCls('registrations_count') }}">
                            <span class="inline-flex items-center gap-1">Regs <span class="text-[11px] opacity-50">{{ $eIcon('registrations_count') }}</span></span>
                        </th>
                        <th onclick="window.location='{{ $eUrl('status') }}'"
                            class="px-6 py-4 font-semibold text-center cursor-pointer select-none hover:text-indigo-700 transition {{ $eCls('status') }}">
                            <span class="inline-flex items-center gap-1">Status <span class="text-[11px] opacity-50">{{ $eIcon('status') }}</span></span>
                        </th>
                        <th onclick="window.location='{{ $eUrl('visibility') }}'"
                            class="px-6 py-4 font-semibold text-center cursor-pointer select-none hover:text-indigo-700 transition {{ $eCls('visibility') }}">
                            <span class="inline-flex items-center gap-1">Visibility <span class="text-[11px] opacity-50">{{ $eIcon('visibility') }}</span></span>
                        </th>
                        <th class="px-6 py-4 font-semibold text-gray-600 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($events as $event)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($event->banner_image)
                                <img src="{{ Storage::url($event->banner_image) }}" alt="" class="w-10 h-10 rounded-lg object-cover shrink-0">
                                @else
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                @endif
                                <div class="min-w-0">
                                    <a href="{{ route('events.show', $event) }}" class="text-sm font-semibold text-gray-900 hover:text-indigo-600 transition block truncate max-w-[200px]">
                                        {{ $event->title }}
                                    </a>
                                    <p class="text-xs text-gray-400 truncate">{{ $event->venue ?? 'No venue' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 hidden md:table-cell">
                            <span class="text-sm text-gray-600">{{ $event->organizer->name ?? 'Unknown' }}</span>
                        </td>
                        <td class="px-6 py-4 hidden sm:table-cell whitespace-nowrap">
                            <span class="text-sm text-gray-500">{{ $event->event_date->format('M d, Y') }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm font-semibold text-gray-700">{{ $event->registrations_count }}</span>
                            <span class="text-xs text-gray-400">/{{ $event->capacity > 0 ? $event->capacity : '∞' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full
                                @if($event->status === 'published') bg-green-100 text-green-800
                                @elseif($event->status === 'draft') bg-gray-100 text-gray-600
                                @elseif($event->status === 'completed') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($event->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full
                                @if($event->visibility === 'public') bg-sky-100 text-sky-700
                                @else bg-gray-100 text-gray-500 @endif">
                                {{ ucfirst($event->visibility) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('events.show', $event) }}"
                                    class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                    title="View event">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.events.delete', $event) }}"
                                    onsubmit="return confirm('Delete event &quot;{{ $event->title }}&quot;? This will also remove all associated registrations, tickets, and certificates.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                        title="Delete event">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="flex flex-col items-center py-16 text-gray-400">
            <svg class="w-16 h-16 mb-4 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-lg font-medium text-gray-500">No events found</p>
            <p class="text-sm mt-1">Try adjusting your search or filter criteria.</p>
        </div>
        @endif
    </div>

    @if($events->hasPages())
    <div class="mt-6">
        {{ $events->links() }}
    </div>
    @endif
</div>
@endsection
