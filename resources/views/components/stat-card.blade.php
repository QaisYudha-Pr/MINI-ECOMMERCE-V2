@props(['title', 'value', 'growth', 'icon', 'color'])

@php
$colors = [
    'indigo' => 'bg-indigo-50 text-indigo-600 border-indigo-100 shadow-indigo-100/20',
    'emerald' => 'bg-emerald-50 text-emerald-600 border-emerald-100 shadow-emerald-100/20',
    'amber' => 'bg-amber-50 text-amber-600 border-amber-100 shadow-amber-100/20',
    'rose' => 'bg-rose-50 text-rose-600 border-rose-100 shadow-rose-100/20',
];

$icons = [
    'revenue' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    'users' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
    'box' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>',
    'shield' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
];
@endphp

<div class="bg-white p-7 rounded-[2.5rem] border border-slate-100 flex flex-col justify-between shadow-[0_20px_50px_rgba(0,0,0,0.02)] group hover:shadow-[0_20px_50px_rgba(0,0,0,0.05)] transition-all">
    <div>
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-6 border transition-transform group-hover:scale-110 {{ $colors[$color] ?? $colors['indigo'] }}">
            {!! $icons[$icon] ?? $icons['box'] !!}
        </div>
        <h4 class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">{{ $title }}</h4>
        <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $value }}</div>
    </div>
    <div class="mt-4 flex items-center gap-2">
        @if(str_contains($growth, '+'))
            <span class="text-[10px] font-black text-emerald-500 bg-emerald-50 px-2 py-1 rounded-lg">{{ $growth }}</span>
        @elseif(str_contains($growth, '-'))
            <span class="text-[10px] font-black text-rose-500 bg-rose-50 px-2 py-1 rounded-lg">{{ $growth }}</span>
        @else
            <span class="text-[10px] font-black text-slate-400 bg-slate-50 px-2 py-1 rounded-lg italic uppercase tracking-widest">{{ $growth }}</span>
        @endif
    </div>
</div>
