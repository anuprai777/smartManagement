@extends('layouts.app')

@section('title', 'Certificates - ' . $event->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <a href="{{ route('events.show', $event) }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Event
        </a>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Certificates</h1>
            <p class="text-gray-500 mt-1">{{ $event->title }} — Generate and manage certificates for attendees.</p>
        </div>
        <form method="POST" action="{{ route('certificates.generate', $event) }}" class="mt-4 sm:mt-0">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-600 text-white font-medium rounded-lg hover:bg-amber-700 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Generate Certificates
            </button>
        </form>
    </div>

    @if($certificates->isEmpty())
    <div class="text-center py-16 bg-white rounded-xl shadow-sm border border-gray-200">
        <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-500 mb-2">No certificates generated yet</h3>
        <p class="text-gray-400">Click "Generate Certificates" to create certificates for all attendees who have checked in.</p>
    </div>
    @else
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-4 font-semibold text-gray-600">Attendee</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Certificate #</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Issued At</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($certificates as $cert)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="w-9 h-9 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-semibold text-sm">
                                    {{ substr($cert->user->name, 0, 2) }}
                                </span>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $cert->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $cert->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $cert->certificate_number }}</td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ $cert->issued_at?->format('M d, Y h:i A') ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('certificates.download', $cert) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Download
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $certificates->links() }}
    </div>
    @endif
</div>
@endsection
