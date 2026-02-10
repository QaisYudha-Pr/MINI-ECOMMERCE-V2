@props(['item', 'isFavorite' => null])

@php
    $isFavorite = $isFavorite ?? (auth()->check() ? auth()->user()->favoriteItems()->where('item_shop_id', $item->id)->exists() : false);
    
    $cartData = [
        'id' => $item->id,
        'nama_barang' => $item->nama_barang,
        'harga' => $item->harga,
        'gambar' => asset($item->gambar),
        'berat' => $item->berat ?? 1000,
        'user_id' => $item->user_id,
        'seller_lat' => $item->user?->latitude,
        'seller_lng' => $item->user?->longitude,
        'seller_name' => $item->user?->nama_toko ?? 'Official Store'
    ];
@endphp

<div class="group relative bg-white rounded-2xl p-0 transition-all duration-300 hover:shadow-lg hover:shadow-gray-100 border border-gray-100 flex flex-col h-full overflow-hidden">
    {{-- IMAGE CONTAINER --}}
    <div class="relative aspect-square overflow-hidden bg-gray-50">
        <a href="{{ route('shop.show', $item->id) }}" class="block w-full h-full">
            <img src="{{ asset($item->gambar) }}"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                alt="{{ $item->nama_barang }}" loading="lazy">
        </a>

        {{-- Top Badges --}}
        <div class="absolute top-2.5 left-2.5 right-2.5 flex items-center justify-between">
            {{-- Category --}}
            <span class="px-2 py-1 bg-white/90 backdrop-blur-md text-[11px] font-semibold text-emerald-600 rounded-md">
                {{ $item->kategori ?? 'Produk' }}
            </span>
            
            {{-- Rating --}}
            <div class="flex items-center gap-1 bg-white/90 backdrop-blur-md px-2 py-1 rounded-md">
                <i class="fa-solid fa-star text-[9px] text-amber-400"></i>
                <span class="text-[11px] font-semibold text-gray-600">{{ number_format($item->ratings_avg ?? 0.0, 1) }}</span>
            </div>
        </div>

        {{-- Floating Favorite --}}
        <div class="absolute bottom-2.5 left-2.5 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
            <button onclick="event.preventDefault(); event.stopPropagation(); toggleFavorite({{ $item->id }}, this)" 
                class="w-8 h-8 bg-white/90 backdrop-blur-md {{ $isFavorite ? 'text-rose-500' : 'text-gray-400' }} rounded-lg flex items-center justify-center shadow-sm transition-all hover:bg-rose-500 hover:text-white hover:scale-105 active:scale-95 favorit-btn"
                data-id="{{ $item->id }}">
                <i class="fa-{{ $isFavorite ? 'solid' : 'regular' }} fa-heart text-xs"></i>
            </button>
        </div>
    </div>

    {{-- INFO AREA --}}
    <div class="p-4 flex flex-col flex-grow relative">
        {{-- Title --}}
        <h3 class="font-semibold text-sm text-gray-900 mb-2 line-clamp-2 leading-snug min-h-[2.5rem]">
            <a href="{{ route('shop.show', $item->id) }}" class="hover:text-emerald-600 transition-colors">{{ $item->nama_barang }}</a>
        </h3>

        {{-- Store Info --}}
        <div class="flex items-center gap-2 mb-3">
            @if($item->user->avatar)
                <img src="{{ asset($item->user->avatar) }}" 
                     class="w-5 h-5 rounded-md object-cover border border-gray-100" 
                     alt="Store Avatar">
            @else
                <div class="w-5 h-5 bg-emerald-600 rounded-md flex items-center justify-center text-[10px] font-bold text-white uppercase">
                    {{ substr($item->user->nama_toko ?? $item->user->name ?? 'OS', 0, 1) }}
                </div>
            @endif
            <span class="text-xs text-gray-500 truncate">{{ $item->user->nama_toko ?? $item->user->name ?? 'Official Store' }}</span>
        </div>

        {{-- Price --}}
        <div class="mt-auto flex flex-col">
            <div class="flex items-baseline gap-0.5">
                <span class="text-xs font-medium text-gray-500">Rp</span>
                <span class="text-lg font-bold text-gray-900 tracking-tight">{{ number_format($item->harga, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Footer Info --}}
        <div class="mt-4 flex items-center justify-between border-t border-gray-50 pt-3">
            <div class="flex flex-col gap-0.5">
                <div class="flex items-center gap-1.5 text-gray-400">
                    <i class="fa-solid fa-location-dot text-[8px]"></i>
                    <span class="text-[11px] text-gray-500">{{ $item->lokasi ?? ($item->user?->alamat ?? 'Mojokerto') }}</span>
                </div>
                <div class="text-[11px] text-gray-400">
                    {{ number_format($item->total_terjual ?? 0, 0, ',', '.') }} terjual
                </div>
            </div>

            {{-- Rounded Plus Button --}}
            <button onclick='Alpine.store("cart").add(@json($cartData))'
                class="w-9 h-9 bg-gray-900 text-white rounded-full flex items-center justify-center transform active:scale-90 transition-all duration-300 hover:bg-emerald-600 hover:shadow-md hover:shadow-emerald-100 group/btn shadow-sm">
                <i class="fa-solid fa-plus text-xs group-hover:rotate-90 transition-transform"></i>
            </button>
        </div>
    </div>
</div>

