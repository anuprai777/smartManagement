@props(['href', 'active' => false])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-indigo-700 bg-indigo-50/80 rounded-xl transition-all duration-200'
    : 'inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-900 hover:bg-gray-100/80 rounded-xl transition-all duration-200';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
