@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Welcome, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-500 mt-1.5">Here's what's happening with your events.</p>
        </div>
        <a href="{{ route('events.create') }}" class="btn-primary mt-4 sm:mt-0">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Create Event
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="stat-card bg-gradient-to-br from-indigo-50 to-indigo-100/50 border-indigo-200/50">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wide">My Events</p>
                    <p class="text-3xl font-bold text-indigo-900 mt-1">{{ $myEventsCount }}</p>
                </div>
                <div class="w-12 h-12 bg-white/80 backdrop-blur rounded-xl flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-card bg-gradient-to-br from-emerald-50 to-emerald-100/50 border-emerald-200/50">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-emerald-600 uppercase tracking-wide">Upcoming</p>
                    <p class="text-3xl font-bold text-emerald-900 mt-1">{{ $upcomingEventsCount }}</p>
                </div>
                <div class="w-12 h-12 bg-white/80 backdrop-blur rounded-xl flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-card bg-gradient-to-br from-amber-50 to-amber-100/50 border-amber-200/50">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-amber-600 uppercase tracking-wide">Registrations</p>
                    <p class="text-3xl font-bold text-amber-900 mt-1">{{ $totalRegistrations }}</p>
                </div>
                <div class="w-12 h-12 bg-white/80 backdrop-blur rounded-xl flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="stat-card bg-gradient-to-br from-purple-50 to-purple-100/50 border-purple-200/50">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-purple-600 uppercase tracking-wide">Attendees</p>
                    <p class="text-3xl font-bold text-purple-900 mt-1">{{ $totalAttendees }}</p>
                </div>
                <div class="w-12 h-12 bg-white/80 backdrop-blur rounded-xl flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- AI-Powered Recommendations -->
    @if($recommendedEvents->isNotEmpty())
    <div class="mb-8">
        <div class="flex items-center justify-between mb-5">
            <h2 class="section-title mb-0">
                <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"/>
                </svg>
                AI Recommended for You
            </h2>
            <a href="{{ route('preferences.edit') }}" class="text-xs font-medium text-amber-600 hover:text-amber-800 transition flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/>
                </svg>
                Customize Interests
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($recommendedEvents as $event)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition group">
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
                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-medium rounded-full bg-amber-100 text-amber-700">
                            <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                            AI Pick
                        </span>
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
                            {{ $event->registrations_count ?? $event->registrations()->where('status', 'registered')->count() }} registered
                        </span>
                        <a href="{{ route('events.show', $event) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">
                            View →
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart -->
        <div class="lg:col-span-2 card p-6">
            <h2 class="section-title">
                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Registration Trend
            </h2>
            @if($monthlyTrend->isNotEmpty())
            <canvas id="registrationChart" height="200"></canvas>
            @else
            <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                <svg class="w-20 h-20 mb-4 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <p class="font-medium">No registration data yet.</p>
                <p class="text-sm mt-1">Create and publish events to see trends here.</p>
            </div>
            @endif
        </div>

        <!-- My Registrations -->
        <div class="card p-6">
            <h2 class="section-title">
                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
                My Registrations
            </h2>
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
                            @if($reg->status === 'attended') badge-green
                            @elseif($reg->status === 'cancelled') badge-red
                            @else badge-blue @endif">
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
    <div class="mt-6 card p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="section-title mb-0">
                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
                        <th class="table-header">Title</th>
                        <th class="table-header hidden sm:table-cell">Date</th>
                        <th class="table-header">Registrations</th>
                        <th class="table-header">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentEvents as $event)
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="table-cell font-medium text-gray-900">
                            <a href="{{ route('events.show', $event) }}" class="hover:text-indigo-600 transition">{{ $event->title }}</a>
                        </td>
                        <td class="table-cell hidden sm:table-cell text-gray-400">{{ $event->event_date->format('M d, Y') }}</td>
                        <td class="table-cell">
                            <span class="font-semibold text-gray-700">{{ $event->registrations_count }}</span>
                            <span class="text-gray-400">/ {{ $event->capacity > 0 ? $event->capacity : '∞' }}</span>
                        </td>
                        <td class="table-cell">
                            @if($event->status === 'published')
                            <span class="badge-green">Published</span>
                            @elseif($event->status === 'draft')
                            <span class="badge-gray">Draft</span>
                            @elseif($event->status === 'completed')
                            <span class="badge-blue">Completed</span>
                            @else
                            <span class="badge-red">Cancelled</span>
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
                backgroundColor: 'rgba(79, 70, 229, 0.08)',
                fill: true,
                tension: 0.4,
                borderWidth: 2.5,
                pointBackgroundColor: '#4f46e5',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
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
