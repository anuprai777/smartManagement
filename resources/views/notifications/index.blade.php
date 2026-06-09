@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
            <p class="text-gray-500 mt-1">Stay updated with your event activities.</p>
        </div>
        @if(auth()->user()->unreadNotifications->count() > 0)
        <form method="POST" action="{{ route('notifications.markAllRead') }}" class="mt-4 sm:mt-0">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Mark All as Read
            </button>
        </form>
        @endif
    </div>

    @if($notifications->isEmpty())
    <div class="text-center py-16 bg-white rounded-xl shadow-sm border border-gray-200">
        <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-500 mb-2">No notifications</h3>
        <p class="text-gray-400">You'll see notifications here when you register for events or receive certificates.</p>
    </div>
    @else
    <div class="space-y-3">
        @foreach($notifications as $notification)
        <div class="rounded-xl shadow-sm border p-5 transition
            @if($notification->unread())
                bg-white border-gray-200 hover:shadow-md border-l-4 border-l-indigo-500
            @else
                bg-gray-50/70 border-gray-100 opacity-70 hover:opacity-90
            @endif">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0
                    @if($notification->read()) opacity-50 @endif
                    @if($notification->type === 'App\Notifications\EventRegistrationNotification') bg-green-100
                    @elseif($notification->type === 'App\Notifications\CertificateIssuedNotification') bg-amber-100
                    @else bg-indigo-100 @endif">
                    @if($notification->type === 'App\Notifications\EventRegistrationNotification')
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    @elseif($notification->type === 'App\Notifications\CertificateIssuedNotification')
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    @else
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <p class="text-sm font-medium @if($notification->read()) text-gray-500 @else text-gray-900 @endif">
                                {{ $notification->data['title'] ?? 'Notification' }}
                            </p>
                            <p class="text-sm @if($notification->read()) text-gray-400 @else text-gray-500 @endif mt-0.5">
                                {{ $notification->data['message'] ?? '' }}
                            </p>
                        </div>
                        @if($notification->unread())
                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                            @csrf
                            <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-800 whitespace-nowrap">Mark read</button>
                        </form>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="text-xs @if($notification->read()) text-gray-300 @else text-gray-400 @endif">{{ $notification->created_at->diffForHumans() }}</span>
                        @if(isset($notification->data['action_url']))
                        <a href="{{ $notification->data['action_url'] }}" class="text-xs @if($notification->read()) text-gray-400 @else text-indigo-600 hover:text-indigo-800 @endif font-medium">
                            View Details →
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
    @endif
</div>
@endsection
