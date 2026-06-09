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
        <!-- Background gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-white to-purple-50 pointer-events-none"></div>
        <div class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-96 h-96 bg-indigo-200/30 rounded-full blur-3xl pointer-events-none max-sm:hidden"></div>
        <div class="absolute bottom-0 left-0 -translate-x-1/4 translate-y-1/4 w-96 h-96 bg-purple-200/30 rounded-full blur-3xl pointer-events-none max-sm:hidden"></div>
        <div class="absolute top-1/3 left-1/4 -translate-x-1/2 w-72 h-72 bg-pink-200/20 rounded-full blur-3xl pointer-events-none max-sm:hidden"></div>
        <div class="absolute bottom-1/4 right-1/3 w-64 h-64 bg-amber-200/25 rounded-full blur-3xl pointer-events-none max-sm:hidden"></div>
        <div class="absolute top-1/2 left-2/3 w-48 h-48 bg-emerald-200/20 rounded-full blur-3xl pointer-events-none max-sm:hidden"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 lg:py-24 w-full">
            <div class="max-w-3xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 rounded-full text-sm font-medium mb-6 shadow-sm border border-indigo-200/50">
                    <span class="w-2 h-2 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full animate-pulse"></span>
                    Smart event management platform
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight tracking-tight">
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
    <section class="py-20 sm:py-28 bg-white relative overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-indigo-300 to-transparent"></div>
        <div class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-purple-300 to-transparent"></div>
        <div class="absolute -left-32 top-1/2 w-64 h-64 bg-indigo-100/40 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -right-32 bottom-1/4 w-64 h-64 bg-purple-100/40 rounded-full blur-3xl pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-700 rounded-full text-sm font-medium mb-4 shadow-sm border border-indigo-200/50">
                    <span class="w-2 h-2 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full"></span>
                    All-in-One Platform
                </div>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Everything You Need</h2>
                <p class="mt-4 text-lg text-gray-500 max-w-2xl mx-auto">From registration to analytics — a complete event management ecosystem powered by smart technology.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 - Online Event Registration -->
                <div class="relative p-6 bg-gradient-to-br from-indigo-50 to-white rounded-2xl border border-indigo-100/50 hover:shadow-lg transition flex flex-col group">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Online Event Registration</h3>
                    <p class="text-gray-500 text-sm leading-relaxed flex-1">Seamless registration with instant confirmation, real-time capacity tracking, and automated waitlist management for sold-out events.</p>
                </div>

                <!-- Feature 2 - QR Code Ticket Generation -->
                <div class="relative p-6 bg-gradient-to-br from-purple-50 to-white rounded-2xl border border-purple-100/50 hover:shadow-lg transition flex flex-col group">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">QR Code Ticket Generation</h3>
                    <p class="text-gray-500 text-sm leading-relaxed flex-1">Each registration generates a unique QR-coded ticket with secure encryption, making check-ins fast, fraud-proof, and effortless.</p>
                </div>

                <!-- Feature 3 - QR Scanning for Attendance -->
                <div class="relative p-6 bg-gradient-to-br from-emerald-50 to-white rounded-2xl border border-emerald-100/50 hover:shadow-lg transition flex flex-col group">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">QR Scanning for Attendance</h3>
                    <p class="text-gray-500 text-sm leading-relaxed flex-1">Lightning-fast QR scanning at the door with real-time attendance sync. Verify tickets instantly and track entry in real time.</p>
                </div>

                <!-- Feature 4 - Automated Certificate Generation -->
                <div class="relative p-6 bg-gradient-to-br from-amber-50 to-white rounded-2xl border border-amber-100/50 hover:shadow-lg transition flex flex-col group">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Automated Certificate Generation</h3>
                    <p class="text-gray-500 text-sm leading-relaxed flex-1">Beautiful digital certificates generated and issued automatically after attendance. Custom designs with instant download and sharing.</p>
                </div>

                <!-- Feature 5 - Event Analytics Dashboard -->
                <div class="relative p-6 bg-gradient-to-br from-rose-50 to-white rounded-2xl border border-rose-100/50 hover:shadow-lg transition flex flex-col group">
                    <div class="w-12 h-12 bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Event Analytics Dashboard</h3>
                    <p class="text-gray-500 text-sm leading-relaxed flex-1">Track total registrations, attendance percentages, and event popularity trends. Data-driven insights to optimize your events.</p>
                </div>

                <!-- Feature 6 - Smart Notifications -->
                <div class="relative p-6 bg-gradient-to-br from-sky-50 to-white rounded-2xl border border-sky-100/50 hover:shadow-lg transition flex flex-col group">
                    <div class="w-12 h-12 bg-gradient-to-br from-sky-500 to-sky-600 rounded-xl flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Smart Notifications System</h3>
                    <p class="text-gray-500 text-sm leading-relaxed flex-1">Get booking confirmations with venue & time details, attendance milestone alerts like "You got 80/100 attendance", and event reminders.</p>
                </div>

                <!-- Feature 7 - Participants Dashboard -->
                <div class="relative p-6 bg-gradient-to-br from-teal-50 to-white rounded-2xl border border-teal-100/50 hover:shadow-lg transition flex flex-col group">
                    <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Participants Dashboard</h3>
                    <p class="text-gray-500 text-sm leading-relaxed flex-1">Your central hub to view all registered events, download tickets, and access certificates — everything in one place.</p>
                </div>

                <!-- Feature 8 - AI-Powered Recommendations -->
                <div class="relative p-6 bg-gradient-to-br from-orange-50 to-white rounded-2xl border border-orange-100/50 hover:shadow-lg transition flex flex-col group">
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">AI-Powered Recommendations</h3>
                    <p class="text-gray-500 text-sm leading-relaxed flex-1">Smart event suggestions based on your interests and search history. Discover similar upcoming events tailored just for you.</p>
                </div>

                <!-- Feature 9 - Event Creation & Management -->
                <div class="relative p-6 bg-gradient-to-br from-cyan-50 to-white rounded-2xl border border-cyan-100/50 hover:shadow-lg transition flex flex-col group">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Event Creation & Management</h3>
                    <p class="text-gray-500 text-sm leading-relaxed flex-1">Rich event builder with banners, venue details, scheduling, capacity controls, and one-click publishing to go live instantly.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Events Section -->
    @if($upcomingEvents->isNotEmpty())
    <section class="py-20 sm:py-28 relative overflow-hidden">
        <div class="absolute -right-32 top-1/3 w-72 h-72 bg-amber-100/40 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-32 bottom-1/4 w-64 h-64 bg-emerald-100/40 rounded-full blur-3xl pointer-events-none"></div>
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
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition group flex flex-col">
                    @if($event->banner_image)
                    <img src="{{ Storage::url($event->banner_image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                    @else
                    <div class="w-full h-48 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                        <svg class="w-16 h-16 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    @endif
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">Published</span>
                            <span class="text-xs text-gray-400">
                                <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $event->registrations_count }}/{{ $event->capacity > 0 ? $event->capacity : '∞' }} registered
                            </span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $event->title }}</h3>
                        <div class="space-y-1.5 text-sm text-gray-500 mb-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>{{ $event->event_date->format('F d, Y') }}</span>
                            </div>
                            @if($event->venue)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="truncate">{{ $event->venue }}</span>
                            </div>
                            @endif
                        </div>
                        <a href="{{ route('events.show', $event) }}" class="btn-secondary w-full text-sm !py-2.5 mt-auto">
                            View Details
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    <section class="py-28 sm:py-36 lg:py-52 bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-80 h-80 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-white/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
        <div class="absolute top-1/3 left-1/4 w-48 h-48 bg-pink-400/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/3 right-1/4 w-48 h-48 bg-amber-400/10 rounded-full blur-3xl"></div>
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
        <div class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
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
