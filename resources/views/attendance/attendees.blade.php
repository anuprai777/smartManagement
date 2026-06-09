@extends('layouts.app')

@section('title', 'Attendees - ' . $event->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <a href="{{ route('events.show', $event) }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Event
        </a>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Attendees</h1>
            <p class="text-gray-500 mt-1">{{ $event->title }} — Manage attendee registrations.</p>
        </div>
        <div class="flex items-center gap-3 mt-4 sm:mt-0">
            <a href="{{ route('attendance.scan') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                Scan Tickets
            </a>
            <a href="{{ route('certificates.event', $event) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-600 text-white font-medium rounded-lg hover:bg-amber-700 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
                Certificates
            </a>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Total Registered</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalRegistered }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Checked In</p>
            <p class="text-2xl font-bold text-green-600">{{ $totalAttended }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="text-2xl font-bold text-amber-600">{{ $totalPending }}</p>
        </div>
    </div>

    @if($attendees->isEmpty())
    <div class="text-center py-16 bg-white rounded-xl shadow-sm border border-gray-200">
        <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-500 mb-2">No attendees yet</h3>
        <p class="text-gray-400">Registrations will appear here once people sign up.</p>
    </div>
    @else
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-4 font-semibold text-gray-600">Attendee</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Ticket Number</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Registered At</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Checked In</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($attendees as $reg)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="w-9 h-9 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-semibold text-sm">
                                    {{ substr($reg->user->name, 0, 2) }}
                                </span>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $reg->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $reg->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $reg->ticket_number }}</td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $reg->created_at->format('M d, Y h:i A') }}</td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ $reg->checked_in_at ? $reg->checked_in_at->format('h:i A') : '—' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full
                                @if($reg->status === 'attended') bg-green-100 text-green-800
                                @elseif($reg->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-blue-100 text-blue-800 @endif">
                                @if($reg->status === 'attended') ✓ Checked In
                                @elseif($reg->status === 'cancelled') ✕ Cancelled
                                @else ● Registered @endif
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $attendees->links() }}
    </div>
    @endif
</div>
@endsection
