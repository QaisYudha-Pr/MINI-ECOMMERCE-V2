@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-[1rem] font-black text-[10px] uppercase tracking-widest shadow-lg shadow-indigo-100 transition-all duration-300'
            : 'inline-flex items-center px-4 py-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-[1rem] font-bold text-[10px] uppercase tracking-widest transition-all duration-300';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>