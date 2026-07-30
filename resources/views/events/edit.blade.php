@extends('layouts.app')

@section('title', 'Edit Event')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header with icon -->
    <div class="flex items-center gap-4 mb-8">
        <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-200">
            <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Event</h1>
            <p class="text-gray-500 mt-0.5">Update your event details below.</p>
        </div>
    </div>

    <div class="card p-6 sm:p-8">
        <form method="POST" action="{{ route('events.update', $event) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Details Section -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Event Details
                </h2>
                <div class="space-y-5">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Event Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" required
                            class="input-field @error('title') input-error @enderror"
                            placeholder="Enter event title">
                        @error('title')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                        <textarea name="description" id="description" rows="5"
                            class="input-field @error('description') input-error @enderror"
                            placeholder="Describe your event...">{{ old('description', $event->description) }}</textarea>
                        @error('description')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

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
                        <input type="datetime-local" name="event_date" id="event_date"
                            value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}" required
                            class="input-field @error('event_date') input-error @enderror">
                        @error('event_date')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="registration_deadline" class="block text-sm font-medium text-gray-700 mb-1.5">Registration Deadline</label>
                        <input type="datetime-local" name="registration_deadline" id="registration_deadline"
                            value="{{ old('registration_deadline', $event->registration_deadline?->format('Y-m-d\TH:i')) }}"
                            class="input-field @error('registration_deadline') input-error @enderror">
                        @error('registration_deadline')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100"></div>

            <!-- Settings Section -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg>
                    Settings
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="venue" class="block text-sm font-medium text-gray-700 mb-1.5">Venue / Location</label>
                        <input type="text" name="venue" id="venue" value="{{ old('venue', $event->venue) }}"
                            class="input-field @error('venue') input-error @enderror"
                            placeholder="Online or physical location">
                        @error('venue')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1.5">Capacity <span class="text-red-500">*</span></label>
                        <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $event->capacity) }}" min="0" required
                            class="input-field @error('capacity') input-error @enderror">
                        @error('capacity')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">Set to 0 for unlimited capacity.</p>
                    </div>
                </div>

                <div class="mt-5">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                    <select name="status" id="status"
                        class="input-field @error('status') input-error @enderror">
                        <option value="draft" @selected(old('status', $event->status) === 'draft')>Draft</option>
                        <option value="published" @selected(old('status', $event->status) === 'published')>Published</option>
                        <option value="completed" @selected(old('status', $event->status) === 'completed')>Completed</option>
                        <option value="cancelled" @selected(old('status', $event->status) === 'cancelled')>Cancelled</option>
                    </select>
                    @error('status')
                    <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Banner Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Banner Image</label>
                @if($event->banner_image)
                <div class="mb-3 relative group">
                    <img src="{{ Storage::url($event->banner_image) }}" alt="Current banner"
                        class="w-full h-32 object-cover rounded-xl border border-gray-200">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 rounded-xl transition"></div>
                    <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Current banner
                    </p>
                </div>
                @endif
                <div class="relative border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-indigo-300 transition group">
                    <input type="file" name="banner_image" id="banner_image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <svg class="w-8 h-8 mx-auto text-gray-300 group-hover:text-indigo-400 transition mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/>
                    </svg>
                    <p class="text-sm text-gray-500 group-hover:text-gray-700 transition">
                        <span class="font-medium text-indigo-600">Click to change</span> or drag new image
                    </p>
                    <p class="text-xs text-gray-400 mt-1" id="file-name">Leave empty to keep current</p>
                </div>
                @error('banner_image')
                <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>{{ $message }}</p>
                @enderror
            </div>

            <!-- Visibility -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Event Visibility</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative flex items-center gap-3 p-4 border rounded-xl cursor-pointer transition has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50/50 has-[:checked]:ring-1 has-[:checked]:ring-indigo-500 border-gray-200 hover:border-gray-300">
                        <input type="radio" name="visibility" value="public" class="sr-only peer" {{ old('visibility', $event->visibility) === 'public' ? 'checked' : '' }}>
                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center peer-checked:border-indigo-500">
                            <div class="w-2.5 h-2.5 rounded-full bg-indigo-500 hidden peer-checked:block"></div>
                        </div>
                        <div>
                            <span class="block text-sm font-semibold text-gray-900">Public</span>
                            <span class="block text-xs text-gray-500">Visible to everyone. Appears in listings.</span>
                        </div>
                    </label>
                    <label class="relative flex items-center gap-3 p-4 border rounded-xl cursor-pointer transition has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50/50 has-[:checked]:ring-1 has-[:checked]:ring-indigo-500 border-gray-200 hover:border-gray-300">
                        <input type="radio" name="visibility" value="private" class="sr-only peer" {{ old('visibility', $event->visibility) === 'private' ? 'checked' : '' }}>
                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center peer-checked:border-indigo-500">
                            <div class="w-2.5 h-2.5 rounded-full bg-indigo-500 hidden peer-checked:block"></div>
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
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Update Event
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
    document.getElementById('file-name').textContent = fileName || 'Leave empty to keep current';
});
</script>
@endpush
