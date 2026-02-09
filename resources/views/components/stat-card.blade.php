@props(['title', 'value', 'growth', 'icon', 'color' => 'emerald'])

@php
$icons = [
    'revenue' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    'users' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
    'box' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>',
    'shield' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
];
@endphp

<div class="group relative bg-white p-6 rounded-2xl border border-slate-100 flex flex-col justify-between shadow-[0_8px_30px_rgba(0,0,0,0.04)] transition-all duration-500 hover:shadow-emerald-500/10 hover:-translate-y-1 overflow-hidden">
    <div class="absolute top-0 left-6 right-6 h-1 bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-b-full"></div>
    <div class="absolute -right-3 -top-3 w-20 h-20 bg-emerald-50 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
    
    <div class="relative z-10">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-sm">
                {!! $icons[$icon] ?? $icons['box'] !!}
            </div>
            <svg class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
        </div>
        <h4 class="text-slate-400 text-[11px] font-semibold mb-0.5">{{ $title }}</h4>
        <div class="text-2xl font-bold text-slate-900 tracking-tight">{{ $value }}</div>
    </div>
    
    <div class="relative z-10 mt-6 flex items-center justify-between">
        @if(str_contains($growth, '+'))
            <div class="flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-xl">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                <span class="text-[11px] font-bold">{{ $growth }}</span>
            </div>
        @elseif(str_contains($growth, '-'))
            <div class="flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-600 rounded-xl">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                <span class="text-[11px] font-bold">{{ $growth }}</span>
            </div>
        @else
            <span class="text-[11px] font-bold text-slate-400 bg-slate-50 px-3 py-1.5 rounded-xl">{{ $growth }}</span>
        @endif
        
        <div class="w-8 h-1 bg-slate-100 rounded-full group-hover:w-12 transition-all duration-500"></div>
    </div>
</div>

