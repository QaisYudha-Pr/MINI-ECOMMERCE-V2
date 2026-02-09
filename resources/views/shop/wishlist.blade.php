<x-admin-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Daftar Suka</h1>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="w-8 h-1 bg-rose-500 rounded-full"></span>
                        <p class="text-slate-500 font-bold text-xs">Produk impianmu yang tersimpan bolo</p>
                    </div>
                </div>

                @if(!$favorites->isEmpty())
                    <button onclick="addAllToCart()" class="px-8 py-4 bg-slate-900 text-white rounded-2xl hover:bg-emerald-600 transition-all duration-300 font-bold text-xs flex items-center gap-2 shadow-xl shadow-slate-200 group">
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
                    class="px-5 py-2.5 rounded-2xl whitespace-nowrap text-xs uppercase font-bold tracking-widest transition-all
                    {{ request('category', 'all') == 'all' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-100' : 'bg-white text-slate-400 hover:bg-slate-50 border border-slate-100' }}">
                    Semua
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('wishlist.index', ['category' => $category]) }}" 
                        class="px-5 py-2.5 rounded-2xl whitespace-nowrap text-xs uppercase font-bold tracking-widest transition-all
                        {{ request('category') == $category ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-100' : 'bg-white text-slate-400 hover:bg-slate-50 border border-slate-100' }}">
                        {{ $category }}
                    </a>
                @endforeach
            </div>
            @endif

            @if($favorites->isEmpty())
                <div class="bg-white rounded-2xl py-32 text-center border-2 border-dashed border-slate-200">
                    <div class="w-24 h-24 bg-rose-50 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm">
                        <svg class="w-12 h-12 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900">Belum ada yang disukai...</h2>
                    <p class="text-slate-400 font-bold text-xs mt-2">Yuk cari barang seru dan klik tombol hati!</p>
                    <a href="{{ route('shop.public') }}" class="inline-block mt-8 px-10 py-4 bg-slate-900 text-white rounded-2xl font-bold text-xs hover:bg-emerald-600 transition-all shadow-xl shadow-slate-100">
                        Eksplor Sekarang
                    </a>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    @foreach($favorites as $item)
                        <x-product-card :item="$item" :is-favorite="true" />
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
                confirmButtonColor: '#059669',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'IYO GASKEUN!',
                cancelButtonText: 'Enggak jadi',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-3 text-xs font-semibold',
                    cancelButton: 'rounded-xl px-6 py-3 text-xs font-semibold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    favoritesData.forEach(item => {
                        const cartItem = {
                            id: item.id,
                            nama_barang: item.nama_barang,
                            harga: item.harga,
                            gambar: window.location.origin + '/' + (item.gambar.startsWith('/') ? item.gambar.substring(1) : item.gambar),
                            berat: item.berat || 1000,
                            user_id: item.user_id,
                            seller_name: item.user ? (item.user.nama_toko || item.user.name) : 'Official Store',
                            seller_lat: item.user ? item.user.latitude : null,
                            seller_lng: item.user ? item.user.longitude : null
                        };
                        Alpine.store('cart').add(cartItem, 1, true); // true = silent
                    });

                    Swal.fire({
                        icon: 'success',
                        title: 'MANTAP BOLO!',
                        text: 'Semua barang favorit sudah masuk keranjang.',
                        timer: 2000,
                        showConfirmButton: false,
                        customClass: { popup: 'rounded-2xl' }
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
                                stockBadge.className = "bg-orange-500 text-white text-xs font-semibold px-2 py-1 rounded-lg shadow-sm";
                                stockBadge.innerText = `Sisa ${stat.stok}!`;
                            } else if (stat.stok == 0) {
                                stockBadge.className = "bg-slate-800 text-white text-xs font-semibold px-2 py-1 rounded-lg shadow-sm";
                                stockBadge.innerText = "Habis Bolo";
                            } else {
                                stockBadge.className = "bg-emerald-500 text-white text-xs font-semibold px-2 py-1 rounded-lg shadow-sm";
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

