@extends('layouts.app')

@section('title', 'Earnings')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                @if($selectedOrganizer)
                    {{ $selectedOrganizer->name }}
                @else
                    Earnings
                @endif
            </h1>
            <p class="text-gray-500 mt-1">
                @if($selectedOrganizer)
                    Detailed earnings breakdown for this organizer
                @else
                    Organizer earnings overview with {{ $commissionRate * 100 }}% platform commission
                @endif
            </p>
        </div>
        @if($selectedOrganizer)
        <a href="{{ route('admin.earnings', ['period' => $period]) }}"
           class="btn-secondary text-sm mt-4 sm:mt-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to All Organizers
        </a>
        @endif
    </div>

    <!-- Period Filter -->
    <div class="flex flex-wrap items-center gap-2 mb-8">
        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider mr-1">Period:</span>
        @foreach(['this_month' => 'This Month', 'last_month' => 'Last Month', 'this_year' => 'This Year', 'last_year' => 'Last Year', 'all' => 'All Time'] as $val => $label)
        <a href="{{ route('admin.earnings', array_merge(['period' => $val], $organizerId ? ['organizer' => $organizerId] : [])) }}"
           class="px-4 py-2 text-sm font-medium rounded-xl transition {{ $period === $val ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    @if($selectedOrganizer)
        {{-- ─── ORGANIZER DETAIL VIEW ──────────────────────────────── --}}
        <!-- Organizer summary cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="bg-white rounded-xl border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Revenue</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1.5">Rs. {{ number_format($byEvent->sum('revenue'), 2) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Commission</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1.5">Rs. {{ number_format($byEvent->sum('commission'), 2) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Payout</p>
                        <p class="text-2xl font-bold text-amber-700 mt-1.5">Rs. {{ number_format($byEvent->sum('payout'), 2) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-purple-600 uppercase tracking-wider">Registrations</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1.5">{{ $byEvent->sum('reg_count') }}</p>
                        <p class="text-xs text-purple-500 mt-1">{{ $byEvent->count() }} events</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events table -->
        <div class="rounded-2xl bg-white border border-gray-100 p-6 mb-8 hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Events</h3>
                    <p class="text-xs text-gray-400">Breakdown by event</p>
                </div>
            </div>
            @if($byEvent->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-100">
                            <th class="pb-3 font-semibold text-gray-600">Event</th>
                            <th class="pb-3 font-semibold text-gray-600 text-center">Price</th>
                            <th class="pb-3 font-semibold text-gray-600 text-center">Registrations</th>
                            <th class="pb-3 font-semibold text-gray-600 text-right">Revenue</th>
                            <th class="pb-3 font-semibold text-emerald-600 text-right">Commission</th>
                            <th class="pb-3 font-semibold text-amber-600 text-right">Payout</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($byEvent as $event)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 pr-4 font-medium text-gray-900">{{ $event->title }}</td>
                            <td class="py-3 text-center text-gray-500">Rs. {{ number_format($event->price, 2) }}</td>
                            <td class="py-3 text-center font-medium text-gray-700">{{ $event->reg_count }}</td>
                            <td class="py-3 text-right font-semibold text-gray-900">Rs. {{ number_format($event->revenue, 2) }}</td>
                            <td class="py-3 text-right font-semibold text-emerald-700">Rs. {{ number_format($event->commission, 2) }}</td>
                            <td class="py-3 text-right font-semibold text-amber-700">Rs. {{ number_format($event->payout, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="flex flex-col items-center py-10 text-gray-400">
                <svg class="w-14 h-14 mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="text-sm font-medium">No events found for this period.</p>
            </div>
            @endif
        </div>

        <!-- Recent Transactions -->
        <div class="rounded-2xl bg-white border border-gray-100 p-6 mb-8 hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Recent Transactions</h3>
                    <p class="text-xs text-gray-400">Latest registrations for this organizer</p>
                </div>
            </div>
            @if($recentEarnings->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-100">
                            <th class="pb-3 font-semibold text-gray-600">Attendee</th>
                            <th class="pb-3 font-semibold text-gray-600">Event</th>
                            <th class="pb-3 font-semibold text-gray-600 hidden md:table-cell">Date</th>
                            <th class="pb-3 font-semibold text-gray-600 text-right">Amount</th>
                            <th class="pb-3 font-semibold text-emerald-600 text-right">Fee</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($recentEarnings as $reg)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 pr-4 font-medium text-gray-900">{{ $reg->user?->name ?? 'Unknown' }}</td>
                            <td class="py-3 pr-4 text-gray-500 truncate max-w-[200px]">{{ $reg->event_title }}</td>
                            <td class="py-3 pr-4 hidden md:table-cell text-gray-400 whitespace-nowrap">{{ $reg->created_at->format('M d, Y') }}</td>
                            <td class="py-3 text-right font-semibold text-gray-900">Rs. {{ number_format($reg->amount, 2) }}</td>
                            <td class="py-3 text-right font-semibold text-emerald-700">Rs. {{ number_format($reg->commission, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="flex flex-col items-center py-10 text-gray-400">
                <svg class="w-14 h-14 mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm font-medium">No transactions for this period.</p>
            </div>
            @endif
        </div>

        <!-- Monthly Trend Chart -->
        @if($monthlyTrend->isNotEmpty())
        <div class="rounded-2xl bg-white border border-gray-100 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Monthly Trend</h3>
                    <p class="text-xs text-gray-400">Revenue, commission & payout over last 12 months</p>
                </div>
            </div>
            <canvas id="earningsChart" height="100"></canvas>
        </div>
        @endif

    @else
        {{-- ─── ORGANIZER LIST VIEW ────────────────────────────────── --}}
        <!-- Platform-wide Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 stagger-children">
            <div class="relative overflow-hidden rounded-2xl bg-white border border-indigo-100/60 p-5 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Total Revenue</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1.5">Rs. {{ number_format($totalRevenue, 2) }}</p>
                        <p class="text-xs text-indigo-500 mt-1"><span class="font-semibold">{{ $totalRegistrations }}</span> registrations</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200/50">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
            <div class="relative overflow-hidden rounded-2xl bg-white border border-emerald-100/60 p-5 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Commission</p>
                        <p class="text-3xl font-bold text-emerald-700 mt-1.5">Rs. {{ number_format($totalCommission, 2) }}</p>
                        <p class="text-xs text-emerald-500 mt-1">{{ $commissionRate * 100 }}% platform fee</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200/50">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                </div>
            </div>
            <div class="relative overflow-hidden rounded-2xl bg-white border border-amber-100/60 p-5 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Total Payout</p>
                        <p class="text-3xl font-bold text-amber-700 mt-1.5">Rs. {{ number_format($totalPayout, 2) }}</p>
                        <p class="text-xs text-amber-500 mt-1"><span class="font-semibold">{{ $totalOrganizers }}</span> organizers</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-200/50">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
            </div>
            <div class="relative overflow-hidden rounded-2xl bg-white border border-purple-100/60 p-5 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-purple-600 uppercase tracking-wider">Net Earnings</p>
                        <p class="text-3xl font-bold text-purple-700 mt-1.5">Rs. {{ number_format($totalRevenue - $totalPayout, 2) }}</p>
                        <p class="text-xs text-purple-500 mt-1">After payouts</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-200/50">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Organizer List -->
        <div class="rounded-2xl bg-white border border-gray-100 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">Organizers</h3>
                    <p class="text-xs text-gray-400">Click an organizer to view detailed earnings</p>
                </div>
            </div>
            @if($byOrganizer->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-100">
                            <th class="pb-3 font-semibold text-gray-600">Organizer</th>
                            <th class="pb-3 font-semibold text-gray-600 text-center">Events</th>
                            <th class="pb-3 font-semibold text-gray-600 text-center">Registrations</th>
                            <th class="pb-3 font-semibold text-gray-600 text-right">Revenue</th>
                            <th class="pb-3 font-semibold text-emerald-600 text-right">Commission</th>
                            <th class="pb-3 font-semibold text-amber-600 text-right">Payout</th>
                            <th class="pb-3 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($byOrganizer as $org)
                        <tr class="hover:bg-indigo-50/40 transition cursor-pointer" onclick="window.location='{{ route('admin.earnings', array_merge(['period' => $period], ['organizer' => $org->user_id])) }}'">
                            <td class="py-3 pr-4">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-xs shrink-0">
                                        {{ substr($org->organizer_name, 0, 2) }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 truncate max-w-[180px]">{{ $org->organizer_name }}</p>
                                        <p class="text-xs text-gray-400 truncate max-w-[180px]">{{ $org->organizer_email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 text-center font-medium text-gray-700">{{ $org->event_count }}</td>
                            <td class="py-3 text-center font-medium text-gray-700">{{ $org->reg_count }}</td>
                            <td class="py-3 text-right font-semibold text-gray-900">Rs. {{ number_format($org->revenue, 2) }}</td>
                            <td class="py-3 text-right font-semibold text-emerald-700">Rs. {{ number_format($org->commission, 2) }}</td>
                            <td class="py-3 text-right font-semibold text-amber-700">Rs. {{ number_format($org->payout, 2) }}</td>
                            <td class="py-3 text-right">
                                <svg class="w-4 h-4 text-gray-300 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-gray-200">
                            <td class="pt-3 font-bold text-gray-900">{{ $byOrganizer->count() }} organizers</td>
                            <td class="pt-3 text-center font-bold text-gray-900">{{ $byOrganizer->sum('event_count') }}</td>
                            <td class="pt-3 text-center font-bold text-gray-900">{{ $byOrganizer->sum('reg_count') }}</td>
                            <td class="pt-3 text-right font-bold text-gray-900">Rs. {{ number_format($byOrganizer->sum('revenue'), 2) }}</td>
                            <td class="pt-3 text-right font-bold text-emerald-700">Rs. {{ number_format($byOrganizer->sum('commission'), 2) }}</td>
                            <td class="pt-3 text-right font-bold text-amber-700">Rs. {{ number_format($byOrganizer->sum('payout'), 2) }}</td>
                            <td class="pt-3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="flex flex-col items-center py-10 text-gray-400">
                <svg class="w-14 h-14 mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <p class="text-sm font-medium">No organizers with earnings this period.</p>
            </div>
            @endif
        </div>
    @endif
</div>
@endsection

@push('scripts')
@if($selectedOrganizer && $monthlyTrend->isNotEmpty())
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('earningsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyTrend->pluck('month')) !!},
            datasets: [
                {
                    label: 'Revenue',
                    data: {!! json_encode($monthlyTrend->pluck('revenue')) !!},
                    backgroundColor: 'rgba(79, 70, 229, 0.2)',
                    borderColor: '#4f46e5',
                    borderWidth: 2,
                    borderRadius: 4,
                    borderSkipped: false,
                },
                {
                    label: 'Commission',
                    data: {!! json_encode($monthlyTrend->pluck('commission')) !!},
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    borderColor: '#10b981',
                    borderWidth: 2,
                    borderRadius: 4,
                    borderSkipped: false,
                },
                {
                    label: 'Payout',
                    data: {!! json_encode($monthlyTrend->pluck('payout')) !!},
                    backgroundColor: 'rgba(245, 158, 11, 0.2)',
                    borderColor: '#f59e0b',
                    borderWidth: 2,
                    borderRadius: 4,
                    borderSkipped: false,
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top', labels: { usePointStyle: true, padding: 16, font: { size: 11 } } },
            },
            scales: {
                y: { beginAtZero: true, ticks: { callback: function(v) { return 'Rs. ' + v.toLocaleString(); }, font: { size: 10 } }, grid: { color: 'rgba(0,0,0,0.04)' } },
                x: { grid: { display: false }, ticks: { font: { size: 10 } } }
            }
        }
    });
});
</script>
@endif
@endpush