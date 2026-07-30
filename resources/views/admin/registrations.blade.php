@extends('layouts.app')

@section('title', $eventId ? $event->title . ' — Registrations' : 'Manage Registrations')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                @if(isset($event))
                    {{ $event->title }}
                @else
                    Manage Registrations
                @endif
            </h1>
            <p class="text-gray-500 mt-1">
                @if(isset($event))
                    Showing all registrations for this event
                @else
                    Select an event to view its registrations
                @endif
            </p>
        </div>
        @if(isset($event))
        <div class="flex items-center gap-2 text-sm text-gray-500 mt-4 sm:mt-0">
            @foreach($statusCounts as $s => $c)
            <span class="font-semibold text-gray-700">{{ $c }}</span> {{ $s }}
            @if(!$loop->last)<span class="text-gray-300 mx-1">·</span>@endif
            @endforeach
        </div>
        @else
        <div class="text-sm text-gray-500 mt-4 sm:mt-0">
            <span class="font-semibold text-gray-700">{{ $totalRegistrations }}</span> total registrations
        </div>
        @endif
    </div>

    <!-- Date Period Filter -->
    <div class="flex flex-wrap items-center gap-2 mb-6">
        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider mr-1">Period:</span>
        @php $periods = ['all' => 'All Time', 'today' => 'Today', 'last_7' => '7 Days', 'last_15' => '15 Days', 'last_30' => '30 Days', 'last_90' => '90 Days']; @endphp
        @foreach($periods as $val => $label)
        <a href="{{ route('admin.registrations', array_merge(request()->only(['event_id', 'search', 'status']), ['period' => $val])) }}"
           class="px-3 py-1.5 text-xs font-medium rounded-lg transition {{ $period === $val ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    @if(isset($event))
        {{-- ─── LEVEL 2: Registration details for a specific event ─── --}}

        <!-- Back link & filters -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
            <a href="{{ route('admin.registrations', ['period' => $period]) }}"
               class="btn-secondary text-sm !py-2 !px-3 shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Events
            </a>

            <form method="GET" action="{{ route('admin.registrations') }}" class="flex flex-1 flex-wrap gap-2 items-center">
                <input type="hidden" name="event_id" value="{{ $eventId }}">
                <input type="hidden" name="period" value="{{ $period }}">

                <select name="status" onchange="this.form.submit()"
                    class="px-3 py-2 rounded-xl border border-gray-200 focus:border-indigo-300 focus:ring focus:ring-indigo-200/50 text-xs bg-white">
                    <option value="">All Statuses</option>
                    @foreach(['registered', 'attended', 'cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>

                <div class="relative flex-1 min-w-[180px]">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search user or ticket..."
                        class="w-full pl-9 pr-3 py-2 rounded-xl border border-gray-200 focus:border-indigo-300 focus:ring focus:ring-indigo-200/50 text-xs">
                </div>
                <button type="submit" class="px-3 py-2 bg-indigo-600 text-white text-xs font-medium rounded-xl hover:bg-indigo-700 transition">Filter</button>
                @if(request('search') || request('status'))
                <a href="{{ route('admin.registrations', ['event_id' => $eventId, 'period' => $period]) }}" class="px-3 py-2 text-xs font-medium text-gray-500 hover:text-gray-700 transition">Clear</a>
                @endif
            </form>
        </div>

        @php
            $rUrl = fn($col) => route('admin.registrations', array_merge(
                request()->only(['event_id', 'search', 'status', 'period']),
                ['sort' => $col, 'direction' => $sort === $col && $dir === 'asc' ? 'desc' : 'asc']
            ));
            $rIcon = fn($col) => $sort === $col ? ($dir === 'asc' ? '↑' : '↓') : '⇅';
            $rCls  = fn($col) => $sort === $col ? 'text-indigo-700' : 'text-gray-600';
        @endphp

        <div class="rounded-2xl bg-white border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow">
            @if($registrations->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left bg-gray-50 border-b border-gray-100">
                            <th class="px-6 py-4 font-semibold text-gray-600">User</th>
                            <th onclick="window.location='{{ $rUrl('ticket_number') }}'"
                                class="px-6 py-4 font-semibold hidden sm:table-cell cursor-pointer select-none hover:text-indigo-700 transition {{ $rCls('ticket_number') }}">
                                <span class="flex items-center gap-1">Ticket # <span class="text-[11px] opacity-50">{{ $rIcon('ticket_number') }}</span></span>
                            </th>
                            <th onclick="window.location='{{ $rUrl('status') }}'"
                                class="px-6 py-4 font-semibold text-center cursor-pointer select-none hover:text-indigo-700 transition {{ $rCls('status') }}">
                                <span class="inline-flex items-center gap-1">Status <span class="text-[11px] opacity-50">{{ $rIcon('status') }}</span></span>
                            </th>
                            <th onclick="window.location='{{ $rUrl('created_at') }}'"
                                class="px-6 py-4 font-semibold text-center hidden md:table-cell cursor-pointer select-none hover:text-indigo-700 transition {{ $rCls('created_at') }}">
                                <span class="inline-flex items-center gap-1">Date <span class="text-[11px] opacity-50">{{ $rIcon('created_at') }}</span></span>
                            </th>
                            <th onclick="window.location='{{ $rUrl('checked_in_at') }}'"
                                class="px-6 py-4 font-semibold text-center hidden lg:table-cell cursor-pointer select-none hover:text-indigo-700 transition {{ $rCls('checked_in_at') }}">
                                <span class="inline-flex items-center gap-1">Checked In <span class="text-[11px] opacity-50">{{ $rIcon('checked_in_at') }}</span></span>
                            </th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($registrations as $reg)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-7 h-7 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-[10px] shrink-0">
                                        {{ substr($reg->user->name ?? '?', 0, 2) }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate max-w-[180px]">{{ $reg->user->name ?? 'Deleted User' }}</p>
                                        <p class="text-xs text-gray-400 truncate max-w-[180px]">{{ $reg->user->email ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 hidden sm:table-cell">
                                <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded font-mono text-gray-600">{{ $reg->ticket_number }}</code>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full
                                    @if($reg->status === 'registered') bg-blue-100 text-blue-800
                                    @elseif($reg->status === 'attended') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($reg->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center hidden md:table-cell whitespace-nowrap">
                                <span class="text-xs text-gray-500">{{ $reg->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="px-6 py-4 text-center hidden lg:table-cell">
                                @if($reg->checked_in_at)
                                <span class="text-xs text-gray-500">{{ $reg->checked_in_at->format('M d, H:i') }}</span>
                                @else
                                <span class="text-xs text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form method="POST" action="{{ route('admin.registrations.delete', $reg) }}"
                                    onsubmit="return confirm('Delete this registration?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
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
                <svg class="w-16 h-16 mb-4 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-lg font-medium text-gray-500">No registrations for this period</p>
            </div>
            @endif
        </div>

        @if($registrations->hasPages())
        <div class="mt-6">{{ $registrations->links() }}</div>
        @endif

        <!-- Other events sidebar hint -->
        @if($otherEvents->isNotEmpty())
        <div class="mt-8 rounded-2xl bg-white border border-gray-100 p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Other Events with Registrations</p>
            <div class="flex flex-wrap gap-2">
                @foreach($otherEvents as $oe)
                <a href="{{ route('admin.registrations', ['event_id' => $oe->id, 'period' => $period]) }}"
                   class="px-3 py-1.5 text-xs font-medium bg-gray-100 text-gray-600 rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition">
                    {{ $oe->title }}
                    <span class="ml-1 font-semibold">({{ $oe->total_count }})</span>
                </a>
                @endforeach
                <a href="{{ route('admin.registrations', ['period' => $period]) }}"
                   class="px-3 py-1.5 text-xs font-medium text-indigo-600 hover:text-indigo-800 transition">
                    View all &rarr;
                </a>
            </div>
        </div>
        @endif

    @else
        {{-- ─── LEVEL 1: Event list ─────────────────────────────────── --}}

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($events as $event)
            <a href="{{ route('admin.registrations', ['event_id' => $event->id, 'period' => $period]) }}"
               class="rounded-2xl bg-white border border-gray-100 p-5 hover:shadow-lg hover:border-indigo-200 transition-all group block">
                <div class="flex items-start gap-3">
                    @if($event->banner_image)
                    <img src="{{ Storage::url($event->banner_image) }}" alt="" class="w-14 h-14 rounded-xl object-cover shrink-0">
                    @else
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600 transition truncate">{{ $event->title }}</h3>
                        <p class="text-xs text-gray-400 mt-1 truncate">{{ $event->venue ?? 'No venue' }}</p>
                        <div class="flex items-center gap-3 mt-3 text-xs">
                            <span class="font-semibold text-gray-700">{{ $event->total_count }}</span>
                            <span class="text-gray-400">total</span>
                            <span class="font-semibold text-green-600">{{ $event->attended_count }}</span>
                            <span class="text-gray-400">attended</span>
                            <span class="font-semibold text-red-500">{{ $event->cancelled_count }}</span>
                            <span class="text-gray-400">cancelled</span>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-indigo-500 transition shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>
            @empty
            <div class="col-span-full flex flex-col items-center py-16 text-gray-400">
                <svg class="w-16 h-16 mb-4 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="text-lg font-medium text-gray-500">No events with registrations</p>
                <p class="text-sm mt-1">Try changing the period filter.</p>
            </div>
            @endforelse
        </div>

        @if($events->hasPages())
        <div class="mt-6">{{ $events->links() }}</div>
        @endif
    @endif
</div>
@endsection
