@extends('layouts.app')

@section('title', 'Edit Event')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Edit Event</h1>
        <p class="text-gray-500 mt-1">Update your event details.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
        <form method="POST" action="{{ route('events.update', $event) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Event Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-500 @enderror"
                    placeholder="Enter event title">
                @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="5"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror"
                    placeholder="Describe your event...">{{ old('description', $event->description) }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1">Event Date & Time <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="event_date" id="event_date"
                        value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('event_date') border-red-500 @enderror">
                    @error('event_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="registration_deadline" class="block text-sm font-medium text-gray-700 mb-1">Registration Deadline</label>
                    <input type="datetime-local" name="registration_deadline" id="registration_deadline"
                        value="{{ old('registration_deadline', $event->registration_deadline?->format('Y-m-d\TH:i')) }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('registration_deadline') border-red-500 @enderror">
                    @error('registration_deadline')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="venue" class="block text-sm font-medium text-gray-700 mb-1">Venue / Location</label>
                    <input type="text" name="venue" id="venue" value="{{ old('venue', $event->venue) }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('venue') border-red-500 @enderror"
                        placeholder="Online or physical location">
                    @error('venue')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1">Capacity <span class="text-red-500">*</span></label>
                    <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $event->capacity) }}" min="0" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('capacity') border-red-500 @enderror">
                    @error('capacity')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-400">Set to 0 for unlimited capacity.</p>
                </div>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-500 @enderror">
                    <option value="draft" @selected(old('status', $event->status) === 'draft')>Draft</option>
                    <option value="published" @selected(old('status', $event->status) === 'published')>Published</option>
                    <option value="completed" @selected(old('status', $event->status) === 'completed')>Completed</option>
                    <option value="cancelled" @selected(old('status', $event->status) === 'cancelled')>Cancelled</option>
                </select>
                @error('status')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Banner Image</label>
                @if($event->banner_image)
                <div class="mb-3">
                    <img src="{{ Storage::url($event->banner_image) }}" alt="Current banner" class="w-48 h-32 object-cover rounded-lg border">
                    <p class="text-xs text-gray-400 mt-1">Current banner</p>
                </div>
                @endif
                <div class="flex items-center gap-4">
                    <label class="cursor-pointer px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm text-gray-600">
                        <svg class="w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Change Image
                        <input type="file" name="banner_image" id="banner_image" accept="image/*" class="hidden">
                    </label>
                    <span id="file-name" class="text-sm text-gray-400">No file chosen</span>
                </div>
                @error('banner_image')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Visibility -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Event Visibility</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative flex items-center gap-3 p-4 border rounded-xl cursor-pointer transition has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50/50 has-[:checked]:ring-1 has-[:checked]:ring-indigo-500 border-gray-200 hover:border-gray-300">
                        <input type="radio" name="visibility" value="public" class="sr-only" {{ old('visibility', $event->visibility) === 'public' ? 'checked' : '' }}>
                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center has-[:checked]:border-indigo-500">
                            <div class="w-2.5 h-2.5 rounded-full bg-indigo-500 hidden has-[:checked]:block"></div>
                        </div>
                        <div>
                            <span class="block text-sm font-semibold text-gray-900">Public</span>
                            <span class="block text-xs text-gray-500">Visible to everyone. Appears in browse and search.</span>
                        </div>
                    </label>
                    <label class="relative flex items-center gap-3 p-4 border rounded-xl cursor-pointer transition has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50/50 has-[:checked]:ring-1 has-[:checked]:ring-indigo-500 border-gray-200 hover:border-gray-300">
                        <input type="radio" name="visibility" value="private" class="sr-only" {{ old('visibility', $event->visibility) === 'private' ? 'checked' : '' }}>
                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center has-[:checked]:border-indigo-500">
                            <div class="w-2.5 h-2.5 rounded-full bg-indigo-500 hidden has-[:checked]:block"></div>
                        </div>
                        <div>
                            <span class="block text-sm font-semibold text-gray-900">Private</span>
                            <span class="block text-xs text-gray-500">Only accessible via direct link. Hidden from listings.</span>
                        </div>
                    </label>
                </div>
                @error('visibility')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    Update Event
                </button>
                <a href="{{ route('events.index') }}" class="px-6 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition">
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
    document.getElementById('file-name').textContent = fileName;
});
</script>
@endpush
