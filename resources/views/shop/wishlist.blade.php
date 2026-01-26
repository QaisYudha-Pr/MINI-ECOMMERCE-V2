<x-admin-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-10">
                <h1 class="text-4xl font-black text-slate-900 tracking-tighter">Daftar Suka</h1>
                <p class="text-slate-500 font-bold text-sm mt-2 uppercase tracking-widest">Produk impianmu yang tersimpan bolo</p>
            </div>

            @if($favorites->isEmpty())
                <div class="bg-white rounded-[3rem] p-20 text-center border-2 border-dashed border-slate-200">
                    <div class="w-24 h-24 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-black text-slate-900">Belum ada yang disukai...</h2>
                    <p class="text-slate-400 font-bold mt-2">Yuk cari barang seru dan klik tombol hati!</p>
                    <a href="{{ route('shop.public') }}" class="inline-block mt-8 px-8 py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-black transition-all">
                        Eksplor Sekarang
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($favorites as $item)
                        <div class="group bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden hover:-translate-y-2 transition-all duration-500">
                            <div class="relative aspect-[4/5] overflow-hidden">
                                <img src="{{ asset($item->gambar) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                
                                {{-- Badge --}}
                                <div class="absolute top-5 left-5">
                                    <span class="px-4 py-1.5 bg-white/90 backdrop-blur-md rounded-full text-[10px] font-black text-slate-900 uppercase tracking-widest shadow-sm">
                                        {{ $item->kategori }}
                                    </span>
                                </div>

                                {{-- Favorite Button (Delete) --}}
                                <div class="absolute top-5 right-5">
                                    <button onclick="toggleFavorite({{ $item->id }}, this)" class="w-10 h-10 bg-rose-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-rose-200 transition-all hover:scale-110 active:scale-90">
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    </button>
                                </div>
                            </div>

                            <div class="p-8">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    </div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $item->user->name }}</p>
                                </div>
                                <h3 class="text-lg font-black text-slate-900 leading-tight mb-2 group-hover:text-indigo-600 transition-colors">{{ $item->nama_barang }}</h3>
                                <p class="text-xl font-black text-slate-900 tracking-tighter">Rp{{ number_format($item->harga, 0, ',', '.') }}</p>
                                
                                <div class="mt-6">
                                    <button onclick="addToCart({{ json_encode($item) }})" class="w-full py-4 bg-slate-50 text-slate-900 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                                        Tambah ke Keranjang
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
        async function toggleFavorite(itemId, btn) {
            try {
                const response = await fetch(`/favorite/${itemId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                if (!data.status) {
                    // Jika dihapus, hilangkan elemen dari DOM atau reload
                    window.location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        function addToCart(item) {
            window.dispatchEvent(new CustomEvent('add-to-cart', { detail: item }));
        }
    </script>
</x-admin-layout>
