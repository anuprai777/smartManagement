@extends('layouts.app')

@section('title', 'My Interests')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">My Interests & Preferences</h1>
        <p class="text-gray-500 mt-1">Help us personalize your event recommendations. Your preferences are used by our AI to suggest the best events for you.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Manual Keywords Card -->
            <div class="card p-6">
                <h2 class="section-title">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/>
                    </svg>
                    Tell us what you're interested in
                </h2>
                <p class="text-sm text-gray-400 mb-4">
                    Add keywords describing your interests — e.g. <span class="font-medium text-gray-600">"technology, music, health, workshop, networking, outdoor"</span>.
                    Our AI will combine these with your registration history to find the best events for you.
                </p>

                <form action="{{ route('preferences.update') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label for="keywords" class="block text-sm font-medium text-gray-700 mb-1.5">Your Interest Keywords</label>
                        <textarea
                            name="keywords"
                            id="keywords"
                            rows="4"
                            class="w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200/50 transition @error('keywords') border-red-300 @enderror"
                            placeholder="e.g. technology, music, health, workshop, networking, outdoor, art, business, education, sports"
                        >{{ old('keywords', implode(', ', $manualKeywords)) }}</textarea>
                        @error('keywords')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-400 mt-1.5">Separate keywords with commas or new lines.</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Preferences
                        </button>
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-400 hover:text-gray-600 transition">Cancel</a>
                    </div>
                </form>
            </div>

            <!-- How It Works -->
            <div class="card p-6">
                <h2 class="section-title">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                    </svg>
                    How Recommendations Work
                </h2>
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center shrink-0">
                            <span class="text-sm font-bold text-indigo-600">1</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Your Registration History</p>
                            <p class="text-xs text-gray-400">Events you've registered for and attended teach our AI about your interests. Keywords are extracted automatically from titles and descriptions.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center shrink-0">
                            <span class="text-sm font-bold text-indigo-600">2</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Your Manual Keywords</p>
                            <p class="text-xs text-gray-400">Add keywords above to explicitly tell us what you like. These are combined with your automatic profile for better recommendations.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center shrink-0">
                            <span class="text-sm font-bold text-indigo-600">3</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Smart Matching</p>
                            <p class="text-xs text-gray-400">Our AI scores every event using content similarity, collaborative filtering (what similar users like), organizer affinity, and popularity — then shows you the best matches.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Auto-detected keywords -->
            <div class="card p-6">
                <h2 class="section-title mb-3">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Auto-Detected Interests
                </h2>
                <p class="text-xs text-gray-400 mb-3">
                    These keywords were automatically extracted from events you've registered for. They update as you explore more events.
                </p>
                @if(!empty($autoKeywords))
                <div class="flex flex-wrap gap-1.5">
                    @foreach($autoKeywords as $keyword)
                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                        {{ $keyword }}
                    </span>
                    @endforeach
                </div>
                @else
                <div class="text-center py-6 text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xs font-medium">No interests detected yet.</p>
                    <p class="text-xs mt-1">Register for events to build your profile!</p>
                </div>
                @endif
            </div>

            <!-- Influencing events -->
            @if($influencingEvents->isNotEmpty())
            <div class="card p-6">
                <h2 class="section-title mb-3">
                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Events Shaping Your Profile
                </h2>
                <div class="space-y-2">
                    @foreach($influencingEvents as $event)
                    <div class="flex items-center gap-2.5 p-2 bg-gray-50/80 rounded-lg">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-gray-700 truncate">{{ $event->title }}</p>
                            <p class="text-[10px] text-gray-400">{{ $event->event_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
