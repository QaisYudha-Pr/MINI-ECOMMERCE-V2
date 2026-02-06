<x-admin-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Daftar Suka</h1>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="w-8 h-1 bg-rose-500 rounded-full"></span>
                        <p class="text-slate-500 font-bold text-[10px] uppercase tracking-[0.2em]">Produk impianmu yang tersimpan bolo</p>
                    </div>
                </div>

                @if(!$favorites->isEmpty())
                    <button onclick="addAllToCart()" class="px-8 py-4 bg-slate-900 text-white rounded-[2rem] hover:bg-indigo-600 transition-all duration-300 font-black text-[10px] uppercase tracking-widest flex items-center gap-2 shadow-xl shadow-slate-200 group">
                        <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Borong Semua ke Keranjang
                    </button>
                @endif
            </div>

            {{-- Category Pills --}}
            @if(!$categories->isEmpty())
            <div class="flex items-center gap-2 mb-8 overflow-x-auto pb-4 no-scrollbar">
                <a href="{{ route('wishlist.index', ['category' => 'all']) }}" 
                    class="px-5 py-2.5 rounded-2xl whitespace-nowrap text-[10px] uppercase font-black tracking-widest transition-all
                    {{ request('category', 'all') == 'all' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-white text-slate-400 hover:bg-slate-50 border border-slate-100' }}">
                    Semua
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('wishlist.index', ['category' => $category]) }}" 
                        class="px-5 py-2.5 rounded-2xl whitespace-nowrap text-[10px] uppercase font-black tracking-widest transition-all
                        {{ request('category') == $category ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-white text-slate-400 hover:bg-slate-50 border border-slate-100' }}">
                        {{ $category }}
                    </a>
                @endforeach
            </div>
            @endif

            @if($favorites->isEmpty())
                <div class="bg-white rounded-[3rem] py-32 text-center border-2 border-dashed border-slate-200">
                    <div class="w-24 h-24 bg-rose-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-sm">
                        <svg class="w-12 h-12 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">Belum ada yang disukai...</h2>
                    <p class="text-slate-400 font-bold text-xs mt-2 uppercase tracking-widest">Yuk cari barang seru dan klik tombol hati!</p>
                    <a href="{{ route('shop.public') }}" class="inline-block mt-8 px-10 py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-indigo-600 transition-all shadow-xl shadow-slate-100">
                        Eksplor Sekarang
                    </a>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    @foreach($favorites as $item)
                        <div class="group bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col h-full relative">
                            
                            {{-- IMAGE CONTAINER --}}
                            <div class="relative aspect-square overflow-hidden bg-slate-50">
                                <a href="{{ route('shop.show', $item->id) }}">
                                    <img src="{{ asset($item->gambar) }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                        alt="{{ $item->nama_barang }}" loading="lazy">
                                </a>

                                {{-- Floating Unfavorite --}}
                                <div class="absolute top-3 right-3">
                                    <button onclick="toggleFavorite({{ $item->id }}, this)" 
                                        class="w-8 h-8 bg-white/90 backdrop-blur-md text-rose-500 rounded-xl flex items-center justify-center shadow-lg transition-all hover:bg-rose-500 hover:text-white hover:scale-110 active:scale-90">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    </button>
                                </div>

                                {{-- Stock Badge --}}
                                <div class="absolute top-3 left-3 flex flex-col gap-1">
                                    @if($item->stok <= 5 && $item->stok > 0)
                                        <span data-stock-id="{{ $item->id }}" class="bg-orange-500 text-white text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-lg shadow-sm">Sisa {{ $item->stok }}!</span>
                                    @elseif($item->stok == 0)
                                        <span data-stock-id="{{ $item->id }}" class="bg-slate-800 text-white text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-lg shadow-sm">Habis Bolo</span>
                                    @else
                                        <span data-stock-id="{{ $item->id }}" class="bg-emerald-500 text-white text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-lg shadow-sm">Stok {{ $item->stok }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- CONTENT --}}
                            <div class="p-4 flex flex-col flex-grow">
                                <span class="text-[9px] uppercase font-bold tracking-widest text-indigo-600 mb-1.5">{{ $item->kategori ?? 'Umum' }}</span>
                                
                                <h3 class="font-bold text-[11px] text-slate-800 line-clamp-2 h-10 mb-1.5 group-hover:text-indigo-600 transition-colors leading-relaxed">
                                    <a href="{{ route('shop.show', $item->id) }}">{{ $item->nama_barang }}</a>
                                </h3>

                                <div class="mt-auto">
                                    <div class="mb-1.5">
                                        <span class="text-[10px] font-black text-slate-900">Rp</span>
                                        <span class="text-sm font-black text-slate-900 tracking-tight">{{ number_format($item->harga, 0, ',', '.') }}</span>
                                    </div>

                                    <div class="flex items-center gap-1 mb-3">
                                        <div class="flex items-center gap-0.5">
                                            <svg class="w-2.5 h-2.5 text-yellow-500 fill-current" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            <span class="text-[10px] text-slate-500 font-bold tracking-tight">{{ number_format($item->ratings_avg, 1) }}</span>
                                        </div>
                                        <span class="text-slate-200 text-[10px]">|</span>
                                        <span class="text-[10px] text-slate-400 font-bold tracking-tighter">{{ $item->total_terjual }} terjual</span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between pt-3 border-t border-slate-50">
                                        <span class="flex items-center gap-1 text-[9px] font-black text-slate-400 uppercase tracking-widest truncate max-w-[100px]">
                                            <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $item->user->nama_toko ?? 'Mojokerto' }}
                                        </span>

                                        @if(isset($item->distance))
                                            <span class="text-[9px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">
                                                {{ number_format($item->distance, 1) }} km
                                            </span>
                                        @endif
                                    </div>

                                    <button onclick='Alpine.store("cart").add(@json($item))'
                                        class="w-full mt-4 py-3 bg-slate-900 group-hover:bg-indigo-600 text-white rounded-xl font-black text-[9px] uppercase tracking-[0.2em] transform active:scale-95 transition-all duration-300 shadow-lg shadow-slate-100">
                                        Beli Sekarang
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        const favoritesData = @json($favorites);

        function addAllToCart() {
            if (favoritesData.length === 0) return;

            Swal.fire({
                title: 'Borong Semua?',
                text: `Yakin mau masukin ${favoritesData.length} produk ke keranjang bolo?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'IYO GASKEUN!',
                cancelButtonText: 'Enggak jadi',
                customClass: {
                    popup: 'rounded-[2rem]',
                    confirmButton: 'rounded-xl px-6 py-3 text-xs font-black uppercase tracking-widest',
                    cancelButton: 'rounded-xl px-6 py-3 text-xs font-black uppercase tracking-widest'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    favoritesData.forEach(item => {
                        Alpine.store('cart').add(item, 1, true); // true = silent
                    });

                    Swal.fire({
                        icon: 'success',
                        title: 'MANTAP BOLO!',
                        text: 'Semua barang favorit sudah masuk keranjang.',
                        timer: 2000,
                        showConfirmButton: false,
                        customClass: { popup: 'rounded-[2rem]' }
                    });
                }
            });
        }

        async function toggleFavorite(itemId, btn) {
            try {
                const response = await fetch(`/favorite/${itemId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                
                if (response.ok) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    
                    if (!data.status) {
                        // Jika dihapus, hilangkan elemen dari DOM atau reload bolo
                        setTimeout(() => window.location.reload(), 500);
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Waduh...',
                    text: 'Gagal mengubah status favorit bolo.',
                });
            }
        }

        // Live Stock Polling
        function updateLiveStats() {
            fetch('{{ route('shop.stats') }}')
                .then(response => response.json())
                .then(data => {
                    data.forEach(stat => {
                        const stockBadge = document.querySelector(`[data-stock-id="${stat.id}"]`);
                        if (stockBadge) {
                            if (stat.stok <= 5 && stat.stok > 0) {
                                stockBadge.className = "bg-orange-500 text-white text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-lg shadow-sm";
                                stockBadge.innerText = `Sisa ${stat.stok}!`;
                            } else if (stat.stok == 0) {
                                stockBadge.className = "bg-slate-800 text-white text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-lg shadow-sm";
                                stockBadge.innerText = "Habis Bolo";
                            } else {
                                stockBadge.className = "bg-emerald-500 text-white text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-lg shadow-sm";
                                stockBadge.innerText = `Stok ${stat.stok}`;
                            }
                        }
                    });
                })
                .catch(err => console.error('Gagal update stok:', err));
        }

        // Update every 30 seconds
        setInterval(updateLiveStats, 30000);
    </script>
</x-admin-layout>
