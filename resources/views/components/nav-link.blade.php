@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 rounded-full font-semibold text-xs tracking-wide transition-colors'
            : 'inline-flex items-center px-4 py-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-full font-medium text-xs tracking-wide transition-colors';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>