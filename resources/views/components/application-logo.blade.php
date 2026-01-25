@php
    $logo = $siteSettings['site_logo'] ?? null;
    $name = $siteSettings['site_name'] ?? 'MiniQ-Store';
@endphp

@if($logo)
    <img src="{{ asset($logo) }}" {{ $attributes->merge(['class' => 'h-9 w-auto']) }} alt="{{ $name }}">
@else
    <div {{ $attributes->merge(['class' => 'flex items-center gap-2 group whitespace-nowrap']) }}>
        <div class="w-10 h-10 bg-[#00AA5B] rounded-xl flex items-center justify-center shadow-sm group-hover:rotate-3 transition-all duration-300">
            <span class="text-white font-black text-xl italic mt-0.5">M</span>
        </div>
        <div class="flex flex-col -space-y-1">
            <span class="font-black text-xl tracking-tighter text-gray-900">
                @php
                    $cleanName = str_replace('-', ' ', $name);
                    $parts = explode(' ', $cleanName);
                    $first = $parts[0] ?? $name;
                    $rest = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';
                @endphp
                {{ $first }}<span class="text-[#00AA5B] ml-0.5">{{ $rest }}</span>
            </span>
            <span class="text-[8px] font-black uppercase tracking-[0.4em] text-gray-400">Mojokerto Marketplace</span>
        </div>
    </div>
@endif
