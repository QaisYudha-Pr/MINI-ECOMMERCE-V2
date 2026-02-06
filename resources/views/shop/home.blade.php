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
    
        {{-- Data Items dari Database --}}
        items: {{ $items->map(function ($item) {
                // Ensure we use the real avatar path and fallback properly
                $avatar = $item->user->avatar ?? null;
                $sellerAvatar = $avatar 
                    ? (Str::startsWith($avatar, ['http://', 'https://']) ? $avatar : asset($avatar))
                    : 'https://ui-avatars.com/api/?name=' . urlencode($item->user->nama_toko ?? $item->user->name ?? 'OS') . '&color=00AA5B&background=EBF4FF';

                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
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
    }" class="bg-[#F8FAFC] min-h-screen pb-32">

        {{-- NEW: Tokopedia Style Banner Slider --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-8 mt-6">
            <div class="swiper bannerSwiper rounded-[2.5rem] overflow-hidden shadow-2xl relative group">
                <div class="swiper-wrapper">
                        @php
                            $sliders = isset($settings['home_sliders']) ? json_decode($settings['home_sliders'], true) : [];
                        @endphp

                        @if(count($sliders) > 0)
                            @foreach($sliders as $slider)
                                <div class="swiper-slide">
                                    <div class="relative w-full h-[200px] sm:h-[350px] bg-slate-100">
                                        <img src="{{ asset($slider) }}" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                                        <div class="absolute inset-0 flex items-center px-8 sm:px-16">
                                            <div class="max-w-xl text-left">
                                                <h2 class="text-2xl sm:text-5xl font-black text-white leading-tight drop-shadow-lg italic uppercase tracking-tighter">
                                                    {{ $settings['home_title'] ?? 'Belanja Keren di Mojokerto' }}
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            {{-- Default Banners if CMS is empty --}}
                            @php
                                $defaults = [
                                    ['title' => 'SELAMAT DATANG DI Q-STORE', 'desc' => 'Pusat belanja UMKM Mojokerto paling lengkap bolo!', 'bg' => 'bg-indigo-600'],
                                    ['title' => 'PRODUK LOKAL PILIHAN', 'desc' => 'Dukung ekonomi lokal dengan belanja produk asli Mojokerto.', 'bg' => 'bg-emerald-600'],
                                ];
                            @endphp
                            @foreach($defaults as $index => $banner)
                                <div class="swiper-slide">
                                    <div class="relative w-full h-[200px] sm:h-[350px] {{ $banner['bg'] }}">
                                        {{-- Fixed: Using direct SVG generation for absolute reliability --}}
                                        <div class="absolute inset-0 flex items-center justify-center opacity-10 pointer-events-none overflow-hidden">
                                            <i class="fa-solid fa-store text-[20rem] -rotate-12 translate-x-20"></i>
                                        </div>

                                        <div class="absolute inset-0 bg-gradient-to-tr from-black/40 to-transparent"></div>
                                        
                                        <div class="absolute inset-0 flex items-center px-8 sm:px-16">
                                            <div class="max-w-2xl text-left">
                                                <p class="text-[10px] sm:text-xs font-black text-white/70 uppercase tracking-[0.4em] mb-4">PLATFORM RESMI Q-STORE</p>
                                                <h2 class="text-3xl sm:text-6xl font-black text-white leading-none drop-shadow-2xl italic uppercase tracking-tighter mb-4">
                                                    {{ $banner['title'] }}
                                                </h2>
                                                <p class="text-[10px] sm:text-sm font-bold text-white/80 uppercase tracking-[0.2em] max-w-lg leading-relaxed">
                                                    {{ $banner['desc'] }}
                                                </p>
                                                <div class="mt-8 flex gap-4">
                                                    <div class="w-12 h-1 bg-white/30 rounded-full"></div>
                                                    <div class="w-12 h-1 bg-white/10 rounded-full"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
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

        {{-- 3. BENEFIT SECTION (NEW: FLOATING LUXURY DESIGN) --}}
        <section class="max-w-7xl mx-auto px-8 mt-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Benefit 1: UMKM Pilihan -->
                <div class="group relative bg-white p-8 rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-200/50 hover:shadow-orange-500/10 hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                    {{-- Abstract Background Element --}}
                    <div class="absolute -right-6 -bottom-6 text-orange-500/5 rotate-12 group-hover:scale-125 transition-transform duration-700">
                        <i class="fa-solid fa-shop text-[10rem]"></i>
                    </div>
                    
                    <div class="relative z-10 flex flex-col gap-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-orange-600 rounded-[1.5rem] flex items-center justify-center text-white shadow-lg shadow-orange-200 ring-4 ring-orange-50 transition-transform group-hover:rotate-6">
                            <i class="fa-solid fa-shop text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-black text-slate-900 uppercase tracking-tight mb-1">UMKM PILIHAN</h4>
                            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-[0.2em] leading-relaxed">Produk Lokal Terkurasi Dari Jantung Mojokerto</p>
                        </div>
                    </div>
                </div>

                <!-- Benefit 2: Q-Store Verified -->
                <div class="group relative bg-[#00AA5B] p-8 rounded-[3rem] shadow-xl shadow-emerald-200 hover:shadow-emerald-500/20 hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                    {{-- Abstract Background Element --}}
                    <div class="absolute -right-6 -bottom-6 text-white/10 -rotate-12 group-hover:scale-125 transition-transform duration-700">
                        <i class="fa-solid fa-circle-check text-[10rem]"></i>
                    </div>

                    <div class="relative z-10 flex flex-col gap-6">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-[1.5rem] flex items-center justify-center text-white shadow-xl ring-4 ring-white/10 transition-transform group-hover:-rotate-6">
                            <i class="fa-solid fa-circle-check text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-black text-white uppercase tracking-tight mb-1">Q-STORE VERIFIED</h4>
                            <p class="text-[11px] text-white/70 font-bold uppercase tracking-[0.2em] leading-relaxed">Jaminan Kualitas & Keaslian Produk 100%</p>
                        </div>
                    </div>
                </div>

                <!-- Benefit 3: Fast Delivery -->
                <div class="group relative bg-white p-8 rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-200/50 hover:shadow-indigo-500/10 hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                    {{-- Abstract Background Element --}}
                    <div class="absolute -right-6 -bottom-6 text-indigo-500/5 rotate-12 group-hover:scale-125 transition-transform duration-700">
                        <i class="fa-solid fa-truck-fast text-[10rem]"></i>
                    </div>

                    <div class="relative z-10 flex flex-col gap-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-[1.5rem] flex items-center justify-center text-white shadow-lg shadow-indigo-200 ring-4 ring-indigo-50 transition-transform group-hover:rotate-6">
                            <i class="fa-solid fa-truck-fast text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-black text-slate-900 uppercase tracking-tight mb-1">FAST DELIVERY</h4>
                            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-[0.2em] leading-relaxed">Pengiriman Kilat Khusus Wilayah Mojokerto</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- 4. TRUSTED SELLERS (IDE 3) --}}
        <section class="max-w-7xl mx-auto px-8 mt-16">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ $settings['trusted_sellers_title'] ?? 'Toko Pilihan Bolo' }}</h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $settings['trusted_sellers_subtitle'] ?? 'Belanja aman dari seller terpercaya' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6">
                @foreach($trustedSellers as $seller)
                <div class="group bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-500 flex items-center gap-6 relative overflow-hidden">
                    {{-- Decorative Circle --}}
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>

                    <div class="relative z-10 w-20 h-20 shrink-0">
                        <img src="{{ $seller->avatar ? (Str::startsWith($seller->avatar, ['http://', 'https://']) ? $seller->avatar : asset($seller->avatar)) : 'https://ui-avatars.com/api/?name=' . urlencode($seller->nama_toko ?? $seller->name) . '&color=7F9CF5&background=EBF4FF' }}" 
                             class="w-full h-full rounded-[2rem] object-cover shadow-md border-2 border-white">
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-white border-2 border-white shadow-sm">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path></svg>
                        </div>
                    </div>

                    <div class="relative z-10 flex-grow">
                        <h4 class="text-base font-black text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-1">{{ $seller->nama_toko ?? $seller->name }}</h4>
                        <div class="flex flex-col gap-1 mt-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $seller->item_shops_count }} Produk Tersedia</span>
                            <div class="flex items-center gap-1">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                <span class="text-[9px] font-black text-green-600 uppercase tracking-tighter">Verified Seller</span>
                            </div>
                        </div>
                        <a href="{{ route('shop.public', ['seller_id' => $seller->id]) }}" class="inline-flex mt-4 text-[9px] font-black uppercase tracking-[0.2em] text-indigo-600 hover:text-indigo-800 transition-colors">
                            Kunjungi Toko →
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        {{-- 5. PRODUK GRID --}}
        <section class="max-w-7xl mx-auto px-8 mt-24">
            <div class="mb-10 text-center">
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Produk Yang Baru Ditambahkan Bolo!</h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-2">Jangan Sampai Kehabisan Stok</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <template x-for="(item, index) in items" :key="item.id">
                    <div class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col h-full relative">
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

            {{-- Button: Lihat Semua Produk --}}
            <div class="mt-16 text-center">
                <a href="{{ route('shop.public') }}" 
                    class="inline-flex items-center gap-3 px-10 py-5 bg-white border-2 border-slate-100 rounded-3xl text-[11px] font-black uppercase tracking-[0.2em] text-slate-800 hover:border-[#00AA5B] hover:text-[#00AA5B] transition-all duration-300 group shadow-sm hover:shadow-xl hover:-translate-y-1">
                    Lihat Semua Produk
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </section>

        {{-- 6. MODAL & FOOTER --}}
        @include('components.cart-modal')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
       
        // Initialize Swiper
        document.addEventListener('DOMContentLoaded', function() {
            // Banner Swiper
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

            // Flash Sale Swiper
            new Swiper('.flashSaleSwiper', {
                slidesPerView: 'auto',
                spaceBetween: 20,
                grabCursor: true,
                speed: 800,
            });
        });
    </script>
</x-app-layout>
