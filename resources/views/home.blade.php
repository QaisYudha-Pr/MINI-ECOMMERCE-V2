<x-app-layout>
    {{-- Inisialisasi Alpine.js --}}
    {{-- Kita ambil data dari PHP dan ubah ke JSON agar bisa dibaca JavaScript --}}
    <div x-data="{ 
            search: '', 
            activeCategory: 'All',
            items: {{ $items->map(function($item) {
                return [
                    'id' => $item->id,
                    'nama_barang' => $item->nama_barang,
                    'harga' => $item->harga,
                    'gambar' => asset($item->gambar),
                    'kategori' => $item->kategori, {{-- Pastikan kolom ini ada di DB --}}
                    'avg_rating' => round($item->ratings_avg ?? 0),
                    'reviews_count' => $item->reviews_count ?? 0
                ];
            })->toJson() }},
            get filteredItems() {
                return this.items.filter(i => {
                    const matchSearch = i.nama_barang.toLowerCase().includes(this.search.toLowerCase());
                    const matchCategory = this.activeCategory === 'All' || i.kategori === this.activeCategory;
                    return matchSearch && matchCategory;
                });
            }
         }" 
         class="bg-[#f8fafc] min-h-screen pb-20">
        
        {{-- 1. HERO SECTION & SEARCH --}}
        <section class="relative overflow-hidden bg-indigo-900 rounded-[2.5rem] mx-4 sm:mx-6 mt-6 shadow-2xl shadow-indigo-200">
            <div class="absolute top-0 right-0 -translate-y-12 translate-x-12 w-64 h-64 bg-indigo-500 rounded-full blur-3xl opacity-20"></div>
            
            <div class="relative max-w-7xl mx-auto px-8 py-16 sm:py-24 flex flex-col items-center text-center">
                <span class="inline-block px-4 py-2 rounded-full bg-indigo-500/20 text-indigo-300 text-[10px] font-black uppercase tracking-[0.3em] mb-6">
                    Collection 2026
                </span>
                <h1 class="text-4xl sm:text-6xl font-black text-white leading-none tracking-tighter mb-8">
                    Upgrade Your Style <br> <span class="text-indigo-400">Minimalist</span> Gear.
                </h1>

                {{-- SEARCH INPUT --}}
                <div class="relative w-full max-w-lg group">
                    <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input 
                        x-model="search"
                        type="text" 
                        placeholder="Cari barang impianmu bolo..." 
                        class="w-full pl-16 pr-8 py-6 bg-white/10 backdrop-blur-md border border-white/20 rounded-[2rem] text-white placeholder-indigo-200/50 focus:ring-4 focus:ring-indigo-500 focus:bg-white focus:text-gray-900 transition-all shadow-2xl outline-none">
                </div>
            </div>
        </section>

        {{-- 2. KATEGORI SECTION (STATIS) --}}
        <section class="max-w-7xl mx-auto px-6 mt-16">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-black text-gray-900 uppercase tracking-widest">
                    Top <span class="text-indigo-600">Categories</span>
                </h2>
                <button @click="activeCategory = 'All'; search = ''" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:underline">Reset</button>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @php
                    $categories = [
                        ['name' => 'Fashion', 'icon' => '👕'],
                        ['name' => 'Gadgets', 'icon' => '📱'],
                        ['name' => 'Gaming', 'icon' => '🎮'],
                        ['name' => 'Home', 'icon' => '🏠'],
                        ['name' => 'Watch', 'icon' => '⌚'],
                        ['name' => 'Music', 'icon' => '🎧'],
                    ];
                @endphp

                @foreach ($categories as $cat)
                <button 
                    @click="activeCategory = '{{ $cat['name'] }}'"
                    :class="activeCategory === '{{ $cat['name'] }}' ? 'border-indigo-600 bg-indigo-50 ring-4 ring-indigo-100' : 'border-gray-100 bg-white'"
                    class="group p-6 rounded-[2rem] border transition-all duration-300 text-center">
                    <div class="text-3xl mb-3 group-hover:scale-110 transition-transform">{{ $cat['icon'] }}</div>
                    <span class="block text-[10px] font-black text-gray-900 uppercase tracking-widest">{{ $cat['name'] }}</span>
                </button>
                @endforeach
            </div>
        </section>

        {{-- 3. PRODUK GRID (LIVE) --}}
        <section id="produk" class="max-w-7xl mx-auto px-6 mt-20">
            <div class="flex items-center justify-between mb-10">
                <h2 class="text-xl font-black text-gray-900 uppercase tracking-widest">
                    Our <span class="text-indigo-600">Arrivals</span>
                </h2>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                    Showing <span x-text="filteredItems.length" class="text-indigo-600"></span> items
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                {{-- Loop Alpine.js --}}
                <template x-for="item in filteredItems" :key="item.id">
                    <div class="group bg-white rounded-[2.5rem] border border-gray-100 p-4 hover:shadow-2xl hover:shadow-gray-200/50 transition-all duration-500 relative">
                        
                        {{-- Image --}}
                        <div class="aspect-square rounded-[2rem] overflow-hidden bg-gray-50 mb-6">
                            <img :src="item.gambar"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                 :alt="item.nama_barang">
                        </div>

                        {{-- Content --}}
                        <div class="px-2">
                            <h3 class="font-black text-gray-900 text-sm leading-tight group-hover:text-indigo-600 transition-colors mb-2" x-text="item.nama_barang"></h3>

                            {{-- Rating --}}
                            <div class="flex items-center gap-2 mb-4">
                                <div class="flex text-yellow-400">
                                    <template x-for="i in 5">
                                        <svg class="w-3 h-3" 
                                             :class="i <= item.avg_rating ? 'fill-current' : 'text-gray-200 fill-none'" 
                                             stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.921-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                        </svg>
                                    </template>
                                </div>
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-tighter" x-text="`(${item.reviews_count} Reviews)`"></span>
                            </div>

                            <div class="flex items-center justify-between mt-4">
                                <p class="text-indigo-600 font-black text-lg">
                                    <span class="text-xs mr-0.5">Rp</span>
                                    <span x-text="new Intl.NumberFormat('id-ID').format(item.harga)"></span>
                                </p>
                                
                                <a :href="'/item-shop/' + item.id"
                                   class="w-10 h-10 bg-gray-900 text-white rounded-xl flex items-center justify-center hover:bg-indigo-600 transition-all shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </template>

                {{-- Empty State --}}
                <div x-show="filteredItems.length === 0" class="col-span-full py-20 text-center bg-white rounded-[3rem] border-2 border-dashed border-gray-100">
                    <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Produk tidak ditemukan bolo...</p>
                </div>
            </div>
        </section>

        {{-- 4. NEWSLETTER --}}
        <section class="max-w-7xl mx-auto px-6 mt-24">
            <div class="bg-indigo-600 rounded-[3rem] p-8 sm:p-16 flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-3xl font-black text-white mb-2">Dapatkan Promo!</h2>
                    <p class="text-indigo-100 text-sm font-medium">Berlangganan untuk update produk terbaru.</p>
                </div>
                <div class="relative z-10 flex gap-2 w-full md:w-auto">
                    <input type="email" placeholder="Email Anda" class="w-full md:w-64 px-6 py-4 rounded-2xl border-none outline-none focus:ring-4 focus:ring-indigo-300">
                    <button class="px-8 py-4 bg-gray-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl">Join</button>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>