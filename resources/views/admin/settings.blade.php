@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-200">
            <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/>
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Platform Settings</h1>
            <p class="text-gray-500 mt-0.5">Configure global platform settings and commission rates.</p>
        </div>
    </div>

    <div class="card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Commission Rate -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Commission Rate
                </h2>
                <div class="bg-emerald-50/50 rounded-xl p-5 border border-emerald-100/60 mb-5">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-emerald-800">How commissions work</p>
                            <p class="text-xs text-emerald-600 mt-1">A percentage of each registration fee is deducted as the platform commission. For example, if an event costs Rs. 1000 and the rate is 10%, the organizer receives Rs. 900 and the platform earns Rs. 100.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="commission_rate" class="block text-sm font-medium text-gray-700 mb-1.5">Commission Rate (%)</label>
                        <div class="relative">
                            <input type="number" name="commission_rate" id="commission_rate"
                                value="{{ old('commission_rate', $commissionRate) }}"
                                min="0" max="100" step="0.5"
                                class="input-field @error('commission_rate') input-error @enderror pr-12"
                                placeholder="10">
                            <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">%</span>
                        </div>
                        @error('commission_rate')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">Set between 0% (free) and 100%.</p>
                    </div>
                    <div class="flex flex-col justify-end pb-2">
                        <div class="p-4 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl border border-indigo-100/50">
                            <p class="text-xs font-medium text-indigo-600 uppercase tracking-wider">Preview</p>
                            <div class="mt-2 space-y-1.5 text-sm">
                                @php $previewRate = old('commission_rate', $commissionRate); @endphp
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Ticket price:</span>
                                    <span class="font-medium text-gray-900">Rs. 1,000.00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Commission ({{ $previewRate }}%):</span>
                                    <span class="font-medium text-emerald-700">Rs. {{ number_format(1000 * $previewRate / 100, 2) }}</span>
                                </div>
                                <div class="flex justify-between pt-1.5 border-t border-indigo-100">
                                    <span class="text-gray-700 font-medium">Organizer gets:</span>
                                    <span class="font-medium text-amber-700">Rs. {{ number_format(1000 - (1000 * $previewRate / 100), 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100"></div>

            <!-- Actions -->
            <div class="flex items-center gap-4">
                <button type="submit" class="btn-primary">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
