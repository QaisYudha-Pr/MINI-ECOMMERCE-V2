<x-app-layout>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeInUp 0.4s ease-out forwards;
        }

        .product-card {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.1);
        }

        .category-pill {
            transition: all 0.2s;
        }

        .category-pill.active {
            background-color: emerald-600;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 170, 91, 0.2);
        }

        .category-pill:not(.active):hover {
            background-color: #f3f4f6;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    {{-- PRODUK SECTION --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-8 mt-16" data-aos="fade-up">
        {{-- CATEGORY BAR (SYNCED WITH HOME) --}}
        <div class="flex gap-4 overflow-x-auto no-scrollbar pb-8">
            <a href="{{ route('shop.public', ['search' => request('search')]) }}"
                class="px-8 py-4 rounded-2xl border transition-all whitespace-nowrap {{ !request('category') || request('category') == 'all' ? 'bg-emerald-600 text-white shadow-lg border-emerald-600' : 'bg-white text-gray-500 border-gray-100 hover:bg-gray-50' }}">
                <span class="text-xs font-semibold">all</span>
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('shop.public', ['category' => $cat, 'search' => request('search')]) }}"
                    class="px-8 py-4 rounded-2xl border transition-all whitespace-nowrap {{ request('category') == $cat ? 'bg-emerald-600 text-white shadow-lg border-emerald-600' : 'bg-white text-gray-500 border-gray-100 hover:bg-gray-50' }}">
                    <span class="text-xs font-semibold">{{ $cat }}</span>
                </a>
            @endforeach
        </div>

        {{-- HEADER INFO --}}
        <div class="flex justify-between items-end mb-10">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">
                    {{ request('search') ? 'Hasil Pencarian: "' . request('search') . '"' : (request('category') ? 'Koleksi ' . request('category') : 'Semua Produk') }}
                </h2>
                <div class="flex items-center gap-2 mt-2">
                    <span class="w-8 h-1 bg-emerald-600 rounded-full"></span>
                    <p class="text-gray-400 text-xs font-bold">{{ $items->total() }} Produk Ditemukan</p>
                </div>
            </div>
            
            @guest
                <a href="{{ route('seller.create') }}"
                    class="hidden sm:flex px-8 py-4 bg-emerald-600 text-white rounded-2xl hover:bg-[#008f4d] transition-all duration-300 font-bold text-xs items-center gap-2 shadow-xl shadow-emerald-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Mulai Berjualan
                </a>
            @else
                @role('seller')
                    <a href="{{ route('item-shop.create') }}"
                        class="hidden sm:flex px-8 py-4 bg-emerald-600 text-white rounded-2xl hover:bg-[#008f4d] transition-all duration-300 font-bold text-xs items-center gap-2 shadow-xl shadow-emerald-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Produk
                    </a>
                @else
                    @unlessrole('admin')
                        <a href="{{ route('seller.create') }}"
                            class="hidden sm:flex px-8 py-4 bg-emerald-600 text-white rounded-2xl hover:bg-[#008f4d] transition-all duration-300 font-bold text-xs items-center gap-2 shadow-xl shadow-emerald-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Mulai Berjualan
                        </a>
                    @else
                        {{-- Admin can see add product too --}}
                        <a href="{{ route('item-shop.create') }}"
                            class="hidden sm:flex px-8 py-4 bg-emerald-600 text-white rounded-2xl hover:bg-[#008f4d] transition-all duration-300 font-bold text-xs items-center gap-2 shadow-xl shadow-emerald-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Produk (Admin)
                        </a>
                    @endunlessrole
                @endrole
            @endguest
        </div>

        {{-- PRODUCTS GRID --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @forelse ($items as $index => $item)
                <div class="group relative bg-white rounded-2xl p-3 transition-all duration-500 hover:shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-transparent hover:border-gray-50 flex flex-col h-full"
                    data-aos="fade-up" data-aos-delay="{{ ($index % 5) * 50 }}">
                    <!-- Image Area -->
                    <div class="relative aspect-square rounded-2xl overflow-hidden bg-gray-50 mb-4">
                        @if($item->gambar)
                            <img src="{{ asset($item->gambar) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $item->nama_barang }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-200">
                                <i class="fa-solid fa-image text-4xl"></i>
                            </div>
                        @endif
                        
                        <!-- Stock Badge overlay -->
                        <div class="absolute top-3 left-3">
                            <div class="bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1.5 border border-white/50">
                                @if($item->stok <= 5 && $item->stok > 0)
                                    <span class="text-xs font-semibold text-orange-600">Sisa {{ $item->stok }}!</span>
                                @elseif($item->stok == 0)
                                    <span class="text-xs font-semibold text-rose-600">Habis</span>
                                @else
                                    <span class="text-xs font-semibold text-emerald-600">Tersedia</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Info Area -->
                    <div class="px-2 flex flex-col flex-grow">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-0.5 bg-gray-100 text-xs font-bold text-gray-500 rounded-md">{{ $item->kategori ?? 'Produk' }}</span>
                            <div class="flex items-center gap-1 ml-auto">
                                <i class="fa-solid fa-star text-xs text-amber-400"></i>
                                <span class="text-[11px] font-bold text-gray-900">{{ number_format($item->reviews_avg_rating ?? 5.0, 1) }}</span>
                            </div>
                        </div>

                        <h3 class="font-bold text-sm text-gray-900 mb-1 group-hover:text-emerald-600 transition-colors line-clamp-1">
                            <a href="{{ route('shop.show', $item->id) }}">{{ $item->nama_barang }}</a>
                        </h3>
                        
                        <p class="text-xs text-gray-400 font-medium mb-4 line-clamp-1">
                            {{ Str::limit($item->deskripsi, 40) }}
                        </p>

                        <!-- Price Area -->
                        <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-xs font-medium text-gray-400 leading-none mb-1">Harga</span>
                                <div class="flex items-baseline gap-0.5">
                                    <span class="text-xs font-bold text-gray-900">Rp</span>
                                    <span class="text-base font-bold text-gray-900 tracking-tight">{{ number_format($item->harga, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            
                            <a href="{{ route('shop.show', $item->id) }}" class="w-10 h-10 bg-gray-900 text-white rounded-xl flex items-center justify-center transition-all duration-300 hover:bg-emerald-500 hover:shadow-lg hover:shadow-emerald-200 group-hover:translate-x-1">
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>

            @empty
                <div class="col-span-full py-32 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-100">
                    <div class="max-w-xs mx-auto">
                        <div class="w-24 h-24 bg-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-gray-200/50">
                            <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Tidak Ada Hasil</h3>
                        <p class="text-xs font-medium text-gray-400 mt-2">Coba gunakan kata kunci atau kategori yang lebih umum bolo.</p>
                        <a href="{{ route('shop.public') }}" class="inline-block mt-8 px-8 py-3 bg-gray-900 text-white rounded-2xl text-xs font-semibold hover:bg-black transition-all">Reset Filter</a>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        <div class="mt-16">
            {{ $items->links() }}
        </div>
    </section>

</x-app-layout>

<script>
    console.log('Halo dari item-shop public view!');
</script>


