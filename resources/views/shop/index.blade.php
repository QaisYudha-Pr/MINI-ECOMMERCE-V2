<x-app-layout>
    {{-- halaman selengkapnya --}}
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
            background-color: #00AA5B;
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
                class="px-8 py-4 rounded-[2rem] border transition-all whitespace-nowrap {{ !request('category') || request('category') == 'all' ? 'bg-[#00AA5B] text-white shadow-lg border-[#00AA5B]' : 'bg-white text-gray-500 border-gray-100 hover:bg-gray-50' }}">
                <span class="text-[10px] font-black uppercase tracking-widest">all</span>
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('shop.public', ['category' => $cat, 'search' => request('search')]) }}"
                    class="px-8 py-4 rounded-[2rem] border transition-all whitespace-nowrap {{ request('category') == $cat ? 'bg-[#00AA5B] text-white shadow-lg border-[#00AA5B]' : 'bg-white text-gray-500 border-gray-100 hover:bg-gray-50' }}">
                    <span class="text-[10px] font-black uppercase tracking-widest">{{ $cat }}</span>
                </a>
            @endforeach
        </div>

        {{-- HEADER INFO --}}
        <div class="flex justify-between items-end mb-10">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-gray-900 uppercase tracking-tight">
                    {{ request('search') ? 'Hasil Pencarian: "' . request('search') . '"' : (request('category') ? 'Koleksi ' . request('category') : 'Semua Produk') }}
                </h2>
                <div class="flex items-center gap-2 mt-2">
                    <span class="w-8 h-1 bg-[#00AA5B] rounded-full"></span>
                    <p class="text-gray-400 text-[10px] font-bold uppercase tracking-[0.2em]">{{ $items->total() }} Produk Ditemukan</p>
                </div>
            </div>
            
            @guest
                <a href="{{ route('seller.create') }}"
                    class="hidden sm:flex px-8 py-4 bg-[#00AA5B] text-white rounded-[2rem] hover:bg-[#008f4d] transition-all duration-300 font-black text-[10px] uppercase tracking-widest items-center gap-2 shadow-xl shadow-green-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Mulai Berjualan
                </a>
            @else
                @role('seller')
                    <a href="{{ route('item-shop.create') }}"
                        class="hidden sm:flex px-8 py-4 bg-[#00AA5B] text-white rounded-[2rem] hover:bg-[#008f4d] transition-all duration-300 font-black text-[10px] uppercase tracking-widest items-center gap-2 shadow-xl shadow-green-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Produk
                    </a>
                @else
                    @unlessrole('admin')
                        <a href="{{ route('seller.create') }}"
                            class="hidden sm:flex px-8 py-4 bg-[#00AA5B] text-white rounded-[2rem] hover:bg-[#008f4d] transition-all duration-300 font-black text-[10px] uppercase tracking-widest items-center gap-2 shadow-xl shadow-green-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Mulai Berjualan
                        </a>
                    @else
                        {{-- Admin can see add product too --}}
                        <a href="{{ route('item-shop.create') }}"
                            class="hidden sm:flex px-8 py-4 bg-[#00AA5B] text-white rounded-[2rem] hover:bg-[#008f4d] transition-all duration-300 font-black text-[10px] uppercase tracking-widest items-center gap-2 shadow-xl shadow-green-100">
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
                <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col h-full relative"
                    data-aos="fade-up" data-aos-delay="{{ ($index % 5) * 50 }}">
                    
                    {{-- IMAGE CONTAINER --}}
                    <div class="relative aspect-square overflow-hidden bg-gray-50 text-xs">
                        @if($item->gambar)
                            <img src="{{ asset($item->gambar) }}"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                alt="{{ $item->nama_barang }}" loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif

                        <div class="absolute top-3 left-3 flex flex-col gap-2">
                            @if($item->stok <= 5 && $item->stok > 0)
                                <span class="bg-red-500 text-white text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full shadow-lg">Sisa {{ $item->stok }}</span>
                            @elseif($item->stok == 0)
                                <span class="bg-gray-800 text-white text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full shadow-lg">Habis</span>
                            @endif
                        </div>
                    </div>

                    {{-- CONTENT --}}
                    <div class="p-4 flex flex-col flex-grow">
                        <a href="{{ route('shop.public', ['category' => $item->kategori]) }}" class="text-[9px] uppercase font-bold tracking-widest text-[#00AA5B] mb-1.5 hover:underline">{{ $item->kategori ?? 'Umum' }}</a>
                        
                        <h3 class="font-bold text-xs text-gray-800 line-clamp-2 h-10 mb-1.5 group-hover:text-[#00AA5B] transition-colors leading-relaxed">
                            <a href="{{ route('shop.show', $item->id) }}">{{ $item->nama_barang }}</a>
                        </h3>

                        <div class="mt-auto">
                            <div class="mb-1.5">
                                <span class="text-xs font-black text-gray-900">Rp</span>
                                <span class="text-sm font-black text-gray-900">{{ number_format($item->harga, 0, ',', '.') }}</span>
                            </div>

                            {{-- RATING & TERJUAL (SYNCED WITH HOME) --}}
                            <div class="flex items-center gap-1 mb-2">
                                <div class="flex items-center gap-0.5">
                                    <svg class="w-2.5 h-2.5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-[10px] text-gray-500 font-medium">{{ number_format($item->ratings_avg ?? 0, 1) }}</span>
                                </div>
                                <span class="text-gray-300 text-[10px]">|</span>
                                <span class="text-[10px] text-gray-500 font-medium">{{ $item->total_terjual > 1000 ? number_format($item->total_terjual/1000, 1) . 'rb+' : $item->total_terjual }} terjual</span>
                            </div>
                            
                            <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                                <span class="flex items-center gap-1 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                    <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $item->lokasi ?? 'Mojokerto' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-32 text-center bg-gray-50 rounded-[3rem] border-2 border-dashed border-gray-100">
                    <div class="max-w-xs mx-auto">
                        <div class="w-24 h-24 bg-white rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-gray-200/50">
                            <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-black text-gray-900 uppercase tracking-tight">Tidak Ada Hasil</h3>
                        <p class="text-xs font-medium text-gray-400 mt-2">Coba gunakan kata kunci atau kategori yang lebih umum bolo.</p>
                        <a href="{{ route('shop.public') }}" class="inline-block mt-8 px-8 py-3 bg-gray-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-black transition-all">Reset Filter</a>
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

