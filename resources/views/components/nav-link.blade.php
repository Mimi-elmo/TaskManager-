@props(['active' => false])
@php
$classes = ($active ?? false)
            ? 'inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium'
            : 'inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800 transition-colors';
@endphp
<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>