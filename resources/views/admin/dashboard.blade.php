@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Welcome Banner -->
    <div class="mb-8 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-700 p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 text-white/90 rounded-full text-xs font-medium mb-3">
                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                    System Overview
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Admin Dashboard</h1>
                <p class="text-indigo-200 mt-1 text-sm">Platform overview and system management at a glance.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1.5 bg-white/10 text-white text-xs rounded-lg flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ $totalEvents }} events
                </span>
                <span class="px-3 py-1.5 bg-white/10 text-white text-xs rounded-lg flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $totalUsers }} users
                </span>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1.5">{{ $totalUsers }}</p>
                    <p class="text-xs text-indigo-500 mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <span class="font-semibold">+{{ $newUsersThisMonth }}</span> this month
                    </p>
                </div>
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Total Events</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1.5">{{ $totalEvents }}</p>
                    <p class="text-xs text-emerald-500 mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <span class="font-semibold">{{ $upcomingWeekEvents }}</span> this week
                    </p>
                </div>
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Active Registrations</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1.5">{{ $activeRegistrations }}</p>
                    <p class="text-xs text-amber-500 mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Currently registered
                    </p>
                </div>
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-purple-600 uppercase tracking-wider">Attended</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1.5">{{ $eventsByStatus['completed'] ?? $activeRegistrations }}</p>
                    <p class="text-xs text-purple-500 mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Events completed
                    </p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Data -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Event Status Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 p-6">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Events by Status</h2>
            <canvas id="statusChart" height="200"></canvas>
        </div>

        <!-- Recent Users -->
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Recent Users</h2>
                <a href="{{ route('admin.users') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">View All →</a>
            </div>
            @if($recentUsers->isNotEmpty())
            <div class="space-y-3">
                @foreach($recentUsers as $u)
                <div class="flex items-center gap-3 p-2.5 bg-gray-50/80 rounded-xl hover:bg-gray-100 transition">
                    <span class="w-9 h-9 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-semibold text-xs shrink-0">
                        {{ substr($u->name, 0, 2) }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $u->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $u->email }}</p>
                    </div>
                    @if($u->is_admin)
                    <span class="text-[10px] font-medium text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">Admin</span>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-400 text-center py-6">No users yet.</p>
            @endif
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Event Status Breakdown -->
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Event Status Breakdown</h2>
            @if($eventsByStatus->isNotEmpty())
            <div class="space-y-3">
                @foreach(['published' => ['Published', 'emerald'], 'draft' => ['Draft', 'gray'], 'completed' => ['Completed', 'blue'], 'cancelled' => ['Cancelled', 'red']] as $status => [$label, $color])
                @php $count = $eventsByStatus[$status] ?? 0; @endphp
                <div class="flex items-center justify-between py-1">
                    <span class="text-sm text-gray-600">{{ $label }}</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $count }}</span>
                </div>
                <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full bg-{{ $color }}-500" style="width: {{ $totalEvents > 0 ? ($count / $totalEvents) * 100 : 0 }}%"></div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-400 text-center py-6">No events created yet.</p>
            @endif
        </div>

        <!-- Top Organizers -->
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Top Organizers</h2>
            @if($topOrganizers->isNotEmpty())
            <div class="space-y-3">
                @foreach($topOrganizers as $index => $org)
                <div class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-gray-50 transition">
                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0
                        @if($index === 0) bg-amber-400
                        @elseif($index === 1) bg-gray-300
                        @elseif($index === 2) bg-orange-400
                        @else bg-gray-200 text-gray-500 @endif">
                        {{ $index + 1 }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $org->name }}</p>
                        <p class="text-xs text-gray-400">{{ $org->events_count }} events</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-400 text-center py-6">No organizers yet.</p>
            @endif
        </div>

        <!-- Recent Registrations -->
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Recent Registrations</h2>
                <a href="{{ route('admin.registrations') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">View All →</a>
            </div>
            @if($recentRegistrations->isNotEmpty())
            <div class="space-y-2">
                @foreach($recentRegistrations as $reg)
                <div class="flex items-center justify-between p-2.5 rounded-xl hover:bg-gray-50 transition text-sm">
                    <div class="min-w-0 flex-1">
                        <p class="font-medium text-gray-900 truncate">{{ $reg->user->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $reg->event->title ?? 'N/A' }}</p>
                    </div>
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full shrink-0 ml-2
                        @if($reg->status === 'attended') bg-emerald-100 text-emerald-700
                        @elseif($reg->status === 'cancelled') bg-red-100 text-red-700
                        @else bg-blue-100 text-blue-700 @endif">
                        {{ ucfirst($reg->status) }}
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-400 text-center py-6">No registrations yet.</p>
            @endif

            <div class="mt-4 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.events') }}" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-50 text-indigo-700 rounded-xl hover:bg-indigo-100 transition text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Manage Events
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('statusChart')?.getContext('2d');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($eventsByStatus->keys()->map(fn($s) => ucfirst($s))) !!},
            datasets: [{
                data: {!! json_encode($eventsByStatus->values()) !!},
                backgroundColor: ['#10b981', '#9ca3af', '#6366f1', '#ef4444'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });
});
</script>
@endpush
