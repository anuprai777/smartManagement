@extends('layouts.app')

@section('title', 'Manage Tickets')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manage Tickets</h1>
            <p class="text-gray-500 mt-1">View all issued tickets across the platform.</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-gray-500 mt-4 sm:mt-0">
            @foreach($statusCounts as $s => $c)
            <span class="font-semibold text-gray-700">{{ $c }}</span> {{ $s }}
            @if(!$loop->last)<span class="text-gray-300 mx-1">·</span>@endif
            @endforeach
        </div>
    </div>

    <!-- Status filter -->
    <div class="flex flex-wrap items-center gap-2 mb-6">
        <a href="{{ route('admin.tickets') }}"
           class="px-3 py-1.5 text-xs font-medium rounded-lg transition {{ !request('status') ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">All</a>
        @foreach(['active', 'used', 'cancelled'] as $s)
        <a href="{{ route('admin.tickets', array_merge(request()->only(['search']), ['status' => $s])) }}"
           class="px-3 py-1.5 text-xs font-medium rounded-lg transition {{ request('status') === $s ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">{{ ucfirst($s) }}</a>
        @endforeach
    </div>

    <!-- Search -->
    <form method="GET" action="{{ route('admin.tickets') }}" class="flex gap-3 mb-6">
        <div class="relative flex-1">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search by ticket number, user, or event..."
                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-300 focus:ring focus:ring-indigo-200/50 text-sm">
        </div>
        <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition">Search</button>
        @if(request('search') || request('status'))
        <a href="{{ route('admin.tickets') }}" class="px-4 py-2.5 text-sm font-medium text-gray-500 hover:text-gray-700 transition flex items-center">Clear</a>
        @endif
    </form>

    @php
        $tUrl = fn($col) => route('admin.tickets', array_merge(
            request()->only(['search', 'status']),
            ['sort' => $col, 'direction' => $sort === $col && $dir === 'asc' ? 'desc' : 'asc']
        ));
        $tIcon = fn($col) => $sort === $col ? ($dir === 'asc' ? '↑' : '↓') : '⇅';
        $tCls  = fn($col) => $sort === $col ? 'text-indigo-700' : 'text-gray-600';
    @endphp

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($tickets->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left bg-gray-50 border-b border-gray-200">
                        <th onclick="window.location='{{ $tUrl('ticket_number') }}'"
                            class="px-6 py-4 font-semibold cursor-pointer select-none hover:text-indigo-700 transition {{ $tCls('ticket_number') }}">
                            <span class="flex items-center gap-1">Ticket # <span class="text-[11px] opacity-50">{{ $tIcon('ticket_number') }}</span></span>
                        </th>
                        <th class="px-6 py-4 font-semibold text-gray-600">User</th>
                        <th class="px-6 py-4 font-semibold text-gray-600 hidden sm:table-cell">Event</th>
                        <th onclick="window.location='{{ $tUrl('status') }}'"
                            class="px-6 py-4 font-semibold text-center cursor-pointer select-none hover:text-indigo-700 transition {{ $tCls('status') }}">
                            <span class="inline-flex items-center gap-1">Status <span class="text-[11px] opacity-50">{{ $tIcon('status') }}</span></span>
                        </th>
                        <th onclick="window.location='{{ $tUrl('scanned_at') }}'"
                            class="px-6 py-4 font-semibold text-center hidden md:table-cell cursor-pointer select-none hover:text-indigo-700 transition {{ $tCls('scanned_at') }}">
                            <span class="inline-flex items-center gap-1">Scanned At <span class="text-[11px] opacity-50">{{ $tIcon('scanned_at') }}</span></span>
                        </th>
                        <th class="px-6 py-4 font-semibold text-gray-600 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($tickets as $ticket)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <code class="text-xs bg-gray-100 px-2 py-1 rounded font-mono text-gray-700">{{ $ticket->ticket_number }}</code>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2.5">
                                <span class="w-7 h-7 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-[10px] shrink-0">
                                    {{ substr($ticket->user->name ?? '?', 0, 2) }}
                                </span>
                                <span class="text-sm text-gray-900 truncate max-w-[140px]">{{ $ticket->user->name ?? 'Deleted' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 hidden sm:table-cell">
                            <span class="text-sm text-gray-500 truncate max-w-[160px] block">{{ $ticket->event->title ?? 'Deleted Event' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full
                                @if($ticket->status === 'active') bg-emerald-100 text-emerald-800
                                @elseif($ticket->status === 'used') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center hidden md:table-cell">
                            @if($ticket->scanned_at)
                            <span class="text-xs text-gray-500">{{ $ticket->scanned_at->format('M d, H:i') }}</span>
                            @else
                            <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form method="POST" action="{{ route('admin.tickets.delete', $ticket) }}"
                                onsubmit="return confirm('Delete ticket {{ $ticket->ticket_number }}?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="flex flex-col items-center py-16 text-gray-400">
            <svg class="w-16 h-16 mb-4 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
            <p class="text-lg font-medium text-gray-500">No tickets found</p>
        </div>
        @endif
    </div>

    @if($tickets->hasPages())
    <div class="mt-6">{{ $tickets->links() }}</div>
    @endif
</div>
@endsection
