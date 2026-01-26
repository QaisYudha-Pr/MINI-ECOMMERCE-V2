<x-app-layout>
    {{-- 1. HEADER & STYLING --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Swiper Custom Style */
        .swiper-pagination-bullet {
            background: white !important;
            opacity: 0.5;
            width: 6px;
            height: 6px;
            transition: all 0.3s;
        }
        .swiper-pagination-bullet-active {
            opacity: 1;
            width: 20px;
            border-radius: 10px;
        }

        /* Styling SweetAlert biar matching sama tema minimalist */
        .swal2-popup {
            border-radius: 2.5rem !important;
            padding: 2rem !important;
        }

        .swal2-styled.swal2-confirm {
            background-color: #00AA5B !important;
            border-radius: 1rem !important;
            padding: 0.8rem 2rem !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            font-size: 0.8rem !important;
            letter-spacing: 0.1em !important;
        }

        .swal2-styled.swal2-cancel {
            border-radius: 1rem !important;
            padding: 0.8rem 2rem !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            font-size: 0.8rem !important;
        }
    </style>

    {{-- 2. Alpine.js Root Container --}}
    <div x-data="{
        search: '',
        activeCategory: 'all',
    
        {{-- Data Items dari Database --}}
        items: {{ $items->filter(fn($i) => !empty(trim($i->kategori)))->map(function ($item) {
                // Ensure we use the real avatar path and fallback properly
                $avatar = $item->user->avatar ?? null;
                $sellerAvatar = $avatar 
                    ? (Str::startsWith($avatar, ['http://', 'https://']) ? $avatar : asset($avatar))
                    : 'https://ui-avatars.com/api/?name=' . urlencode($item->user->nama_toko ?? $item->user->name ?? 'OS') . '&color=00AA5B&background=EBF4FF';

                return [
                    'id' => $item->id,
                    'nama_barang' => $item->nama_barang,
                    'harga' => $item->harga,
                    'gambar' => asset($item->gambar),
                    'kategori' => $item->kategori,
                    'stok' => $item->stok,
                    'rating' => number_format($item->ratings_avg ?? 0, 1, '.', ','),
                    'reviews_count' => $item->reviews_count ?? 0,
                    'total_terjual' => $item->total_terjual ?? 0,
                    'lokasi' => $item->lokasi ?? 'Mojokerto',
                    'berat' => $item->berat ?? 1000,
                    'seller_name' => $item->user->nama_toko ?? $item->user->name ?? 'Official Store',
                    'seller_avatar' => $sellerAvatar,
                    'seller_lat' => $item->user->latitude ?? -7.4726,
                    'seller_lng' => $item->user->longitude ?? 112.4382,
                ];
            })->values()->toJson() }},

        {{-- Helper Formatters --}}
        formatSold(num) {
            if (num >= 1000000) return (num/1000000).toFixed(1).replace('.', ',').replace(',0', '') + 'jt+';
            if (num >= 1000) return (num/1000).toFixed(1).replace('.', ',').replace(',0', '') + 'rb+';
            return num;
        },
        formatPrice(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        },
    
        get categories() {
            const availableItems = this.items.filter(item => item.stok > 0);
            return ['all', ...new Set(availableItems.map(item => item.kategori))];
        },
    
        get filteredItems() {
            return this.items.filter(item => {
                const isAvailable = item.stok > 0;
                const matchSearch = item.nama_barang.toLowerCase().includes(this.search.toLowerCase());
                const matchCategory = this.activeCategory === 'all' || item.kategori === this.activeCategory;
                return isAvailable && matchSearch && matchCategory;
            });
        }
    }" class="bg-[#F8FAFC] min-h-screen pb-32">

        {{-- NEW: Tokopedia Style Banner Slider --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-8 mt-6" data-aos="fade-down">
            <div class="swiper bannerSwiper rounded-[2.5rem] overflow-hidden shadow-2xl relative group">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="relative w-full h-[180px] sm:h-[350px]">
                            <img src="{{ asset('banners/banner1.png') }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="relative w-full h-[180px] sm:h-[350px]">
                            <img src="{{ asset('banners/banner2.png') }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        </div>
                    </div>
                </div>
                
                {{-- Pagination Dots --}}
                <div class="swiper-pagination !left-10 !bottom-8 !text-left !w-auto"></div>

                {{-- Promo Button --}}
                <div class="absolute bottom-8 right-10 z-10">
                    <a href="{{ route('shop.public') }}" class="px-5 py-2.5 bg-black/30 backdrop-blur-xl border border-white/10 rounded-full text-white text-[10px] font-black uppercase tracking-widest hover:bg-white hover:text-black transition-all duration-500 shadow-xl">
                        Selengkapnya
                    </a>
                </div>
            </div>
        </section>

        {{-- 3. BENEFIT SECTION (REPLACING SEARCH STRIP) --}}
        <section class="max-w-7xl mx-auto px-8 mt-12" data-aos="fade-up">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Benefit 1 -->
                <div class="group bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-md transition-all flex items-center gap-5">
                    <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-gray-900 uppercase tracking-tight">Gratis Ongkir</h4>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Min. Belanja Rp0</p>
                    </div>
                </div>

                <!-- Benefit 2 -->
                <div class="group bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-md transition-all flex items-center gap-5">
                    <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center text-[#00AA5B] group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-gray-900 uppercase tracking-tight">MiniQ Original</h4>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Garansi Produk Asli</p>
                    </div>
                </div>

                <!-- Benefit 3 -->
                <div class="group bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-md transition-all flex items-center gap-5">
                    <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-gray-900 uppercase tracking-tight">COD Mojokerto</h4>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Local Pride</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- 4. KATEGORI --}}
        <section class="max-w-7xl mx-auto px-8 mt-16" data-aos="fade-up">
            <div class="flex gap-4 overflow-x-auto no-scrollbar pb-4">
                <template x-for="cat in categories" :key="cat">
                    <a :href="'{{ route('shop.public') }}' + (cat === 'all' ? '' : '?category=' + encodeURIComponent(cat))"
                        :class="activeCategory === cat ? 'bg-[#00AA5B] text-white shadow-lg' :
                            'bg-white text-gray-500 border-gray-100'"
                        class="px-8 py-4 rounded-[2rem] border transition-all whitespace-nowrap">
                        <span class="text-[10px] font-black uppercase tracking-widest" x-text="cat"></span>
                    </a>
                </template>
            </div>
        </section>

        {{-- 5. PRODUK GRID --}}
        <section class="max-w-7xl mx-auto px-8 mt-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <template x-for="(item, index) in filteredItems" :key="item.id">
                    <div class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col h-full relative"
                        data-aos="fade-up" :data-aos-delay="index * 50">

                        <!-- Image Section -->
                        <div class="relative aspect-square overflow-hidden bg-gray-50">
                            <img :src="item.gambar"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            
                            <a :href="'/shop/items/' + item.id"
                                class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-all"></a>
                        </div>

                        <!-- Content Section -->
                        <div class="p-3 flex flex-col flex-grow">
                            <!-- Title -->
                            <a :href="'/shop/items/' + item.id" class="block mb-1">
                                <h3 class="text-xs font-bold text-gray-800 leading-relaxed line-clamp-2 h-10 group-hover:text-[#00AA5B] transition-colors"
                                    x-text="item.nama_barang"></h3>
                            </a>

                            <!-- Price -->
                            <div class="mb-1.5">
                                <span class="text-xs font-black text-gray-900">Rp</span>
                                <span class="text-sm font-black text-gray-900" x-text="formatPrice(item.harga)"></span>
                            </div>

                            <!-- Rating & Sold -->
                            <div class="flex items-center gap-1 mb-1.5">
                                <div class="flex items-center gap-0.5">
                                    <svg class="w-2.5 h-2.5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-[10px] text-gray-500 font-medium" x-text="item.rating"></span>
                                </div>
                                <span class="text-gray-300 text-[10px]">|</span>
                                <span class="text-[10px] text-gray-500 font-medium" x-text="formatSold(item.total_terjual) + ' terjual'"></span>
                            </div>

                            <!-- Location & Seller Info -->
                            <div class="flex items-center justify-between mt-auto">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-1">
                                        <div class="bg-[#00AA5B]/10 p-0.5 rounded-full">
                                            <svg class="w-2.5 h-2.5 text-[#00AA5B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                        </div>
                                        <span class="text-[10px] text-gray-500 font-medium truncate w-24" x-text="item.lokasi"></span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <img :src="item.seller_avatar" class="w-3 h-3 rounded-full border border-gray-100">
                                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter" x-text="item.seller_name"></span>
                                    </div>
                                </div>
                                
                                <!-- Quick Add Button -->
                                <button @click="$store.cart.add(item)" 
                                    class="p-2 bg-gray-50 rounded-xl text-gray-400 hover:bg-[#00AA5B] hover:text-white transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </section>

        {{-- 6. MODAL & FOOTER --}}
        @include('components.cart-modal')
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });

        // Initialize Swiper
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper('.bannerSwiper', {
                loop: true,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                speed: 1000,
            });
        });
    </script>
</x-app-layout>
