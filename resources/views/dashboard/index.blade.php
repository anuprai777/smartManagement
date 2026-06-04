@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Welcome, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-500 mt-1">Here's what's happening with your events.</p>
        </div>
        <a href="{{ route('events.create') }}" class="mt-4 sm:mt-0 inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Create Event
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">My Events</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $myEventsCount }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Upcoming Events</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $upcomingEventsCount }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Registrations</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalRegistrations }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Attendees</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalAttendees }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Registration Trend</h2>
            @if($monthlyTrend->isNotEmpty())
            <canvas id="registrationChart" height="200"></canvas>
            @else
            <div class="text-center py-12 text-gray-400">
                <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <p>No registration data yet.</p>
            </div>
            @endif
        </div>

        <!-- My Registrations -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">My Registrations</h2>
            @if($myRegistrations->isNotEmpty())
            <div class="space-y-4">
                @foreach($myRegistrations as $reg)
                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $reg->event->title }}</p>
                        <p class="text-xs text-gray-500">{{ $reg->event->event_date->format('M d, Y') }}</p>
                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full mt-1
                            @if($reg->status === 'attended') bg-green-100 text-green-800
                            @elseif($reg->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ ucfirst($reg->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-400">
                <p>No registrations yet.</p>
                <a href="{{ route('events.browse') }}" class="text-indigo-600 text-sm font-medium hover:text-indigo-800 mt-2 inline-block">Browse Events</a>
            </div>
            @endif
        </div>
    </div>

    <!-- Recent Events -->
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Recent Events</h2>
            <a href="{{ route('events.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">View All</a>
        </div>
        @if($recentEvents->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200">
                        <th class="pb-3 font-medium">Title</th>
                        <th class="pb-3 font-medium">Date</th>
                        <th class="pb-3 font-medium">Registrations</th>
                        <th class="pb-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentEvents as $event)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3">
                            <a href="{{ route('events.show', $event) }}" class="font-medium text-gray-900 hover:text-indigo-600">{{ $event->title }}</a>
                        </td>
                        <td class="py-3 text-gray-500">{{ $event->event_date->format('M d, Y') }}</td>
                        <td class="py-3 text-gray-500">{{ $event->registrations_count }}</td>
                        <td class="py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full
                                @if($event->status === 'published') bg-green-100 text-green-800
                                @elseif($event->status === 'draft') bg-gray-100 text-gray-800
                                @elseif($event->status === 'completed') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($event->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8 text-gray-400">
            <p>No events yet. Create your first event!</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
@if($monthlyTrend->isNotEmpty())
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('registrationChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyTrend->keys()) !!},
            datasets: [{
                label: 'Registrations',
                data: {!! json_encode($monthlyTrend->values()) !!},
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 2,
                pointBackgroundColor: '#4f46e5',
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
});
</script>
@endif
@endpush
