<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SmartEvent') }} — Event Management Made Simple</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface text-gray-900 antialiased">
    <!-- Navigation -->
    <nav class="bg-white/80 backdrop-blur-lg border-b border-gray-200/80 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="/" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">SmartEvent</span>
                </a>
                <div class="flex items-center gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-primary text-sm !py-2 !px-4">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 px-3 py-2 hover:bg-gray-100 rounded-xl transition">Sign In</a>
                        <a href="{{ route('register') }}" class="btn-primary text-sm !py-2 !px-4">Get Started</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative overflow-hidden min-h-screen-minus-nav flex items-center">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-white to-purple-50 pointer-events-none"></div>
        <div class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-96 h-96 bg-indigo-200/30 rounded-full blur-3xl pointer-events-none max-sm:hidden"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 lg:py-24 w-full">
            <div class="max-w-3xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 rounded-full text-sm font-medium mb-6 shadow-sm border border-indigo-200/50 animate-fade-in">
                    <span class="w-2 h-2 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full animate-pulse"></span>
                    Smart event management platform
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight tracking-tight animate-fade-in-up">
                    Manage Events
                    <span class="block bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 bg-clip-text text-transparent">Smarter, Not Harder</span>
                </h1>
                <p class="mt-6 text-lg sm:text-xl text-gray-500 leading-relaxed max-w-2xl mx-auto">
                    Create, manage, and promote your events with ease. From registrations and ticketing 
                    to QR check-ins and digital certificates — everything you need in one place.
                </p>
                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    @auth
                        <a href="{{ route('events.create') }}" class="btn-primary text-base !py-3 !px-8">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Create an Event
                        </a>
                        <a href="{{ route('events.browse') }}" class="btn-secondary text-base !py-3 !px-8">
                            Browse Events
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn-primary text-base !py-3 !px-8">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Start Free
                        </a>
                        <a href="{{ route('events.browse') }}" class="btn-secondary text-base !py-3 !px-8">
                            Browse Events
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 sm:py-28 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 rounded-full text-sm font-medium mb-4 shadow-sm border border-indigo-200/50">
                    <span class="w-2 h-2 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full"></span>
                    All-in-One Platform
                </div>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Everything You Need</h2>
                <p class="mt-4 text-lg text-gray-500 max-w-2xl mx-auto">From registration to analytics — a complete event management ecosystem powered by smart technology.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 stagger-children">
                <!-- Feature 1 - Online Event Registration -->
                <div class="relative p-6 bg-white rounded-2xl border border-gray-100 hover:shadow-md transition flex flex-col group">
                    <div class="w-11 h-11 bg-indigo-100 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Online Registration</h3>
                    <p class="text-gray-500 text-sm leading-relaxed flex-1">Seamless sign-ups with instant confirmation and real-time capacity tracking.</p>
                </div>

                <!-- Feature 2 - QR Ticketing -->
                <div class="relative p-6 bg-white rounded-2xl border border-gray-100 hover:shadow-md transition flex flex-col group">
                    <div class="w-11 h-11 bg-purple-100 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">QR Ticketing</h3>
                    <p class="text-gray-500 text-sm leading-relaxed flex-1">Unique QR-coded tickets for every attendee with fast, fraud-proof scan verification.</p>
                </div>

                <!-- Feature 3 - Attendance Scanning -->
                <div class="relative p-6 bg-white rounded-2xl border border-gray-100 hover:shadow-md transition flex flex-col group">
                    <div class="w-11 h-11 bg-emerald-100 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Door Scanning</h3>
                    <p class="text-gray-500 text-sm leading-relaxed flex-1">Lightning-fast QR scanning at the door with real-time attendance tracking.</p>
                </div>

                <!-- Feature 4 - Digital Certificates -->
                <div class="relative p-6 bg-white rounded-2xl border border-gray-100 hover:shadow-md transition flex flex-col group">
                    <div class="w-11 h-11 bg-amber-100 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Digital Certificates</h3>
                    <p class="text-gray-500 text-sm leading-relaxed flex-1">Beautiful certificates issued automatically after attendance, ready to download and share.</p>
                </div>

                <!-- Feature 5 - AI Recommendations -->
                <div class="relative p-6 bg-white rounded-2xl border border-gray-100 hover:shadow-md transition flex flex-col group">
                    <div class="w-11 h-11 bg-orange-100 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                        <svg class="w-5 h-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">AI Recommendations</h3>
                    <p class="text-gray-500 text-sm leading-relaxed flex-1">Smart event suggestions based on your interests so you never miss what matters.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Events Section -->
    @if($upcomingEvents->isNotEmpty())
    <section class="py-20 sm:py-28 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-12">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Upcoming Events</h2>
                    <p class="mt-2 text-lg text-gray-500">Don't miss out on these exciting events.</p>
                </div>
                <a href="{{ route('events.browse') }}" class="mt-4 sm:mt-0 text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1">
                    View All Events
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($upcomingEvents as $event)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 group flex flex-col">
                    <!-- Banner -->
                    <div class="relative h-44 overflow-hidden">
                        @if($event->banner_image)
                        <img src="{{ Storage::url($event->banner_image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                        <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <svg class="w-14 h-14 text-white/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                        <!-- Date badge -->
                        <div class="absolute top-3 left-3 bg-white/95 backdrop-blur rounded-lg px-2 py-1.5 text-center shadow-sm">
                            <span class="block text-sm font-bold text-indigo-600 leading-none">{{ $event->event_date->format('d') }}</span>
                            <span class="block text-[9px] font-semibold uppercase tracking-wide text-gray-500 mt-0.5">{{ $event->event_date->format('M') }}</span>
                        </div>
                        <!-- Online/Offline badge -->
                        <div class="absolute top-3 right-3 inline-flex items-center gap-1 px-2 py-1 text-[10px] font-semibold rounded-lg backdrop-blur {{ ($event->venue_type ?? 'offline') === 'online' ? 'bg-violet-500/90 text-white' : 'bg-emerald-500/90 text-white' }}">
                            {{ ($event->venue_type ?? 'offline') === 'online' ? 'Online' : 'On-site' }}
                        </div>
                        <!-- Title on banner -->
                        <div class="absolute bottom-0 inset-x-0 p-4">
                            <h3 class="text-base font-semibold text-white leading-snug line-clamp-2">{{ $event->title }}</h3>
                        </div>
                    </div>
                    <!-- Body -->
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="flex items-center justify-between text-xs">
                            <span class="inline-flex items-center px-2 py-0.5 font-medium rounded-full bg-green-100 text-green-800">Published</span>
                            <span class="inline-flex items-center gap-1 text-gray-400">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $event->registrations_count }}/{{ $event->capacity > 0 ? $event->capacity : '∞' }}
                            </span>
                        </div>
                        <div class="mt-3 space-y-1.5 text-sm text-gray-500">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>{{ $event->event_date->format('D, M j, Y') }} · {{ $event->event_date->format('h:i A') }}</span>
                            </div>
                            @if($event->venue)
                            <div class="flex items-center gap-2">
                                @if(($event->venue_type ?? 'offline') === 'online')
                                <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.38-3.72a4 4 0 010-5.657l4-4a4 4 0 015.656 5.656l-1.1 1.1"/></svg>
                                @else
                                <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                @endif
                                <span class="truncate">{{ $event->venue }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="flex items-center justify-between gap-3 mt-4 pt-3 border-t border-gray-100">
                            <span class="text-sm font-bold text-gray-900">
                                @if(($event->price ?? 0) > 0)
                                Rs. {{ number_format($event->price) }}
                                @else
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Free</span>
                                @endif
                            </span>
                            <a href="{{ route('events.show', $event) }}" class="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">
                                View Details
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    <section class="py-24 bg-gradient-to-br from-indigo-600 to-purple-700 relative overflow-hidden">
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight">Ready to Simplify Your Event Management?</h2>
            <p class="mt-5 text-lg sm:text-xl text-indigo-200 max-w-2xl mx-auto">Join thousands of organizers who use SmartEvent to create, manage, and deliver amazing event experiences.</p>
            <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ route('events.create') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-white text-indigo-700 font-semibold rounded-xl hover:bg-indigo-50 transition shadow-lg">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Create Event
                    </a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-white text-indigo-700 font-semibold rounded-xl hover:bg-indigo-50 transition shadow-lg">
                        Get Started Free
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route('events.browse') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-indigo-500 text-white font-semibold rounded-xl hover:bg-indigo-400 transition border border-indigo-400">
                        Browse Events
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-2.5 text-gray-400">
                    <div class="w-7 h-7 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="font-semibold text-gray-500">SmartEvent</span>
                </div>
                <p class="text-sm text-gray-400">&copy; {{ date('Y') }} SmartEvent. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
