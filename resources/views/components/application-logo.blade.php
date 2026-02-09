@php
    $logo = $siteSettings['site_logo'] ?? null;
    $name = $siteSettings['site_name'] ?? 'Q-Store';
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-2 group whitespace-nowrap']) }}>
    @if($logo)
        <img src="{{ asset($logo) }}" class="w-10 h-10 object-contain group-hover:rotate-6 transition-all duration-300 drop-shadow-sm" alt="{{ $name }}">
    @else
        <div class="relative">
            <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-100 group-hover:rotate-6 transition-all duration-300 border-2 border-white overflow-hidden">
                <div class="w-6 h-6 border-4 border-white rounded-full"></div>
                <div class="absolute bottom-2 right-2 w-3.5 h-1.5 bg-white rounded-full rotate-45 transform origin-left"></div>
            </div>
            <div class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-yellow-400 rounded-full border-2 border-white"></div>
        </div>
    @endif

    <div class="flex flex-col -space-y-1">
        <span class="font-bold text-xl tracking-tighter text-gray-900">
            @php
                $cleanName = str_replace('-', ' ', $name);
                $parts = explode(' ', $cleanName);
                $first = $parts[0] ?? $name;
                $rest = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';
            @endphp
            {{ $first }}<span class="text-emerald-600 ml-0.5">{{ $rest }}</span>
        </span>
        <span class="text-[11px] font-semibold uppercase tracking-[0.4em] text-gray-400">Mojokerto Marketplace</span>
    </div>
</div>

