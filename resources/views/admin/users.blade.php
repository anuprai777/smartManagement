@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manage Users</h1>
            <p class="text-gray-500 mt-1">View and manage all registered users on the platform.</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-gray-500 mt-4 sm:mt-0">
            <span class="font-semibold text-gray-700">{{ $users->total() }}</span> total users
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <form method="GET" action="{{ route('admin.users') }}" class="flex-1 flex gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by name or email..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-300 focus:ring focus:ring-indigo-200/50 text-sm">
            </div>
            <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition">Search</button>
            @if(request('search') || request('role'))
            <a href="{{ route('admin.users') }}" class="px-4 py-2.5 text-sm font-medium text-gray-500 hover:text-gray-700 transition flex items-center">Clear</a>
            @endif
        </form>
    </div>



    @php
        $buildUrl = fn($col) => route('admin.users', array_merge(
            request()->only(['search', 'role']),
            ['sort' => $col, 'direction' => $sort === $col && $dir === 'asc' ? 'desc' : 'asc']
        ));
        $sortIcon = fn($col) => $sort === $col ? ($dir === 'asc' ? '↑' : '↓') : '⇅';
        $sortCls  = fn($col) => $sort === $col ? 'text-indigo-700' : 'text-gray-600';
    @endphp

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($users->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-4 font-semibold text-gray-600">User</th>
                        <th onclick="window.location='{{ $buildUrl('created_at') }}'"
                            class="px-6 py-4 font-semibold hidden sm:table-cell cursor-pointer select-none hover:text-indigo-700 transition {{ $sortCls('created_at') }}">
                            <span class="flex items-center gap-1">
                                Joined
                                <span class="text-[11px] opacity-50">{{ $sortIcon('created_at') }}</span>
                            </span>
                        </th>
                        <th onclick="window.location='{{ $buildUrl('events_count') }}'"
                            class="px-6 py-4 font-semibold text-center cursor-pointer select-none hover:text-indigo-700 transition {{ $sortCls('events_count') }}">
                            <span class="inline-flex items-center gap-1">
                                Events
                                <span class="text-[11px] opacity-50">{{ $sortIcon('events_count') }}</span>
                            </span>
                        </th>
                        <th onclick="window.location='{{ $buildUrl('registrations_count') }}'"
                            class="px-6 py-4 font-semibold text-center cursor-pointer select-none hover:text-indigo-700 transition {{ $sortCls('registrations_count') }}">
                            <span class="inline-flex items-center gap-1">
                                Regs
                                <span class="text-[11px] opacity-50">{{ $sortIcon('registrations_count') }}</span>
                            </span>
                        </th>
                        <th class="px-6 py-4 font-semibold text-gray-600 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="w-9 h-9 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0">
                                    {{ substr($user->name, 0, 2) }}
                                </span>
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-900 truncate max-w-[200px]">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400 truncate max-w-[200px]">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 hidden sm:table-cell whitespace-nowrap">
                            <span class="text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm font-semibold text-gray-700">{{ $user->events_count }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm font-semibold text-gray-700">{{ $user->registrations_count }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if(auth()->id() !== $user->id)
                            <form method="POST" action="{{ route('admin.users.delete', $user) }}"
                                onsubmit="return confirm('Delete user &quot;{{ $user->name }}&quot;? This will permanently remove their account, events, and registrations.');">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete user">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="flex flex-col items-center py-16 text-gray-400">
            <svg class="w-16 h-16 mb-4 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="text-lg font-medium text-gray-500">No users found</p>
            <p class="text-sm mt-1">Try adjusting your search criteria.</p>
        </div>
        @endif
    </div>

    @if($users->hasPages())
    <div class="mt-6">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
