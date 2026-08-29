@extends('layouts.app')

@section('title', 'Create Event')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header with icon -->
    <div class="flex items-center gap-4 mb-8">
        <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-200">
            <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Event</h1>
            <p class="text-gray-500 mt-0.5">Fill in the details below to create a new event.</p>
        </div>
    </div>

    <div class="card p-6 sm:p-8">
        <!-- Progress steps -->
        <div class="flex items-center gap-1 mb-8 text-sm">
            <span class="flex items-center gap-1.5 px-3 py-1.5 bg-indigo-100 text-indigo-700 rounded-full font-medium">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                Details
            </span>
            <div class="w-8 h-px bg-gray-200"></div>
            <span class="px-3 py-1.5 text-gray-400">Schedule</span>
            <div class="w-8 h-px bg-gray-200"></div>
            <span class="px-3 py-1.5 text-gray-400">Settings</span>
        </div>

        <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Details Section -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Event Details
                </h2>
                <div class="space-y-5">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Event Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            class="input-field @error('title') input-error @enderror"
                            placeholder="e.g. Annual Tech Summit 2026">
                        @error('title')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                        <textarea name="description" id="description" rows="5"
                            class="input-field @error('description') input-error @enderror"
                            placeholder="Describe what your event is about...">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">Provide a compelling description to attract attendees.</p>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-100"></div>

            <!-- Schedule Section -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Schedule
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1.5">Event Date & Time <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="event_date" id="event_date" value="{{ old('event_date') }}" required
                            class="input-field @error('event_date') input-error @enderror">
                        @error('event_date')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="registration_deadline" class="block text-sm font-medium text-gray-700 mb-1.5">Registration Deadline</label>
                        <input type="datetime-local" name="registration_deadline" id="registration_deadline" value="{{ old('registration_deadline') }}"
                            class="input-field @error('registration_deadline') input-error @enderror">
                        @error('registration_deadline')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-100"></div>

            <!-- Settings Section -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg>
                    Settings
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div x-data="{ venueType: @js(old('venue_type', 'offline')) }">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Venue Type <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-2 mb-2.5">
                            <label class="relative flex items-center justify-center gap-2 px-3 py-2.5 border rounded-xl cursor-pointer transition select-none text-sm font-medium" :class="venueType === 'online' ? 'border-indigo-500 bg-indigo-50/50 ring-1 ring-indigo-500 text-indigo-700' : 'border-gray-200 hover:border-gray-300 text-gray-600'">
                                <input type="radio" name="venue_type" value="online" class="sr-only" :checked="venueType === 'online'" @change="venueType = 'online'">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.38-3.72a4 4 0 010-5.657l4-4a4 4 0 015.656 5.656l-1.1 1.1"/></svg>
                                Online
                            </label>
                            <label class="relative flex items-center justify-center gap-2 px-3 py-2.5 border rounded-xl cursor-pointer transition select-none text-sm font-medium" :class="venueType === 'offline' ? 'border-indigo-500 bg-indigo-50/50 ring-1 ring-indigo-500 text-indigo-700' : 'border-gray-200 hover:border-gray-300 text-gray-600'">
                                <input type="radio" name="venue_type" value="offline" class="sr-only" :checked="venueType === 'offline'" @change="venueType = 'offline'">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Offline
                            </label>
                        </div>
                        <input type="text" name="venue" id="venue" value="{{ old('venue') }}" required
                            class="input-field @error('venue') input-error @enderror"
                            :placeholder="venueType === 'online' ? 'e.g. https://zoom.us/j/123 or meeting link' : 'e.g. Kathmandu Convention Center, Kathmandu'">
                        @error('venue_type')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>{{ $message }}</p>
                        @enderror
                        @error('venue')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400" x-text="venueType === 'online' ? 'Enter the meeting link or platform where the event takes place.' : 'Enter the physical address or venue name.'"></p>
                    </div>

                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1.5">Capacity <span class="text-red-500">*</span></label>
                        <input type="number" name="capacity" id="capacity" value="{{ old('capacity', 100) }}" min="0" required
                            class="input-field @error('capacity') input-error @enderror"
                            placeholder="0 for unlimited">
                        @error('capacity')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">Set to 0 for unlimited capacity.</p>
                    </div>
                </div>
            </div>

            <!-- Banner Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Banner Image</label>
                <div class="relative border-2 border-dashed border-gray-200 rounded-xl p-8 text-center hover:border-indigo-300 transition group">
                    <input type="file" name="banner_image" id="banner_image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <svg class="w-10 h-10 mx-auto text-gray-300 group-hover:text-indigo-400 transition mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/>
                    </svg>
                    <p class="text-sm text-gray-500 group-hover:text-gray-700 transition">
                        <span class="font-medium text-indigo-600">Click to upload</span> or drag and drop
                    </p>
                    <p class="text-xs text-gray-400 mt-1" id="file-name">PNG, JPG, WebP up to 2MB</p>
                </div>
                @error('banner_image')
                <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>{{ $message }}</p>
                @enderror
            </div>

            <!-- Visibility -->
            <div x-data="{ visibility: @js(old('visibility', 'public')) }">
                <label class="block text-sm font-medium text-gray-700 mb-3">Event Visibility</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative flex items-center gap-3 p-4 border rounded-xl cursor-pointer transition select-none" :class="visibility === 'public' ? 'border-indigo-500 bg-indigo-50/50 ring-1 ring-indigo-500' : 'border-gray-200 hover:border-gray-300'">
                        <input type="radio" name="visibility" value="public" class="sr-only" :checked="visibility === 'public'" @change="visibility = 'public'">
                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition" :class="visibility === 'public' ? 'border-indigo-500' : 'border-gray-300'">
                            <div class="w-2.5 h-2.5 rounded-full bg-indigo-500 transition" :class="visibility === 'public' ? 'block' : 'hidden'"></div>
                        </div>
                        <div>
                            <span class="block text-sm font-semibold text-gray-900">Public</span>
                            <span class="block text-xs text-gray-500">Visible to everyone. Appears in listings.</span>
                        </div>
                    </label>
                    <label class="relative flex items-center gap-3 p-4 border rounded-xl cursor-pointer transition select-none" :class="visibility === 'private' ? 'border-indigo-500 bg-indigo-50/50 ring-1 ring-indigo-500' : 'border-gray-200 hover:border-gray-300'">
                        <input type="radio" name="visibility" value="private" class="sr-only" :checked="visibility === 'private'" @change="visibility = 'private'">
                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition" :class="visibility === 'private' ? 'border-indigo-500' : 'border-gray-300'">
                            <div class="w-2.5 h-2.5 rounded-full bg-indigo-500 transition" :class="visibility === 'private' ? 'block' : 'hidden'"></div>
                        </div>
                        <div>
                            <span class="block text-sm font-semibold text-gray-900">Private</span>
                            <span class="block text-xs text-gray-500">Only via direct link. Hidden from listings.</span>
                        </div>
                    </label>
                </div>
                @error('visibility')
                <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4 pt-6 border-t border-gray-100">
                <button type="submit" class="btn-primary">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Create Event
                </button>
                <a href="{{ route('events.index') }}" class="btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('banner_image').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name || 'No file chosen';
    document.getElementById('file-name').textContent = fileName || 'PNG, JPG, WebP up to 2MB';
});
</script>
@endpush
