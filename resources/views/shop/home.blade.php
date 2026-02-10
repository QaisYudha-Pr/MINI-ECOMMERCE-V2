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
            border-radius: 1.5rem !important;
            padding: 2rem !important;
        }

        .swal2-styled.swal2-confirm {
            background-color: var(--brand-600, #059669) !important;
            border-radius: 0.75rem !important;
            padding: 0.7rem 1.5rem !important;
            font-weight: 600 !important;
            font-size: 0.85rem !important;
        }

        .swal2-styled.swal2-cancel {
            border-radius: 0.75rem !important;
            padding: 0.7rem 1.5rem !important;
            font-weight: 600 !important;
            font-size: 0.85rem !important;
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
                    'seller_lng' => $item->user->longitude ?? 112.4385,
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
            <div class="swiper bannerSwiper rounded-2xl overflow-hidden shadow-lg relative group">
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
                                                <h2 class="text-2xl sm:text-5xl font-bold text-white leading-tight drop-shadow-lg uppercase tracking-tighter">
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
                                    ['title' => 'SELAMAT DATANG DI Q-STORE', 'desc' => 'Pusat belanja UMKM Mojokerto paling lengkap bolo!', 'bg' => 'bg-emerald-600'],
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
                                                <p class="text-[11px] sm:text-xs font-semibold text-white/70 uppercase tracking-widest mb-4">PLATFORM RESMI Q-STORE</p>
                                                <h2 class="text-3xl sm:text-5xl font-bold text-white leading-none drop-shadow-2xl tracking-tight mb-4">
                                                    {{ $banner['title'] }}
                                                </h2>
                                                <p class="text-xs sm:text-sm text-white/80 max-w-lg leading-relaxed">
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
                    <a href="{{ route('shop.public') }}" class="px-5 py-2.5 bg-black/30 backdrop-blur-xl border border-white/10 rounded-full text-white text-xs font-medium hover:bg-white hover:text-black transition-all duration-500 shadow-lg">
                        Selengkapnya
                    </a>
                </div>
            </div>
        </section>

        {{-- 3. BENEFIT SECTION (NEW: FLOATING LUXURY DESIGN) --}}
        <section class="max-w-7xl mx-auto px-8 mt-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Benefit 1: UMKM Pilihan -->
                <div class="group relative bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                    {{-- Abstract Background Element --}}
                    <div class="absolute -right-6 -bottom-6 text-emerald-500/5 rotate-12 group-hover:scale-125 transition-transform duration-700">
                        <i class="fa-solid fa-shop text-[10rem]"></i>
                    </div>
                    
                    <div class="relative z-10 flex flex-col gap-5">
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center text-white shadow-md">
                            <i class="fa-solid fa-shop text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-base font-semibold text-slate-900 mb-1">UMKM Pilihan</h4>
                            <p class="text-sm text-slate-400 leading-relaxed">Produk lokal terkurasi dari jantung Mojokerto</p>
                        </div>
                    </div>
                </div>

                <!-- Benefit 2: Q-Store Verified -->
                <div class="group relative bg-emerald-600 p-8 rounded-2xl shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                    {{-- Abstract Background Element --}}
                    <div class="absolute -right-6 -bottom-6 text-white/10 -rotate-12 group-hover:scale-125 transition-transform duration-700">
                        <i class="fa-solid fa-circle-check text-[10rem]"></i>
                    </div>

                    <div class="relative z-10 flex flex-col gap-5">
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center text-white shadow-md">
                            <i class="fa-solid fa-circle-check text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-base font-semibold text-white mb-1">Q-Store Verified</h4>
                            <p class="text-sm text-white/70 leading-relaxed">Jaminan kualitas & keaslian produk 100%</p>
                        </div>
                    </div>
                </div>

                <!-- Benefit 3: Fast Delivery -->
                <div class="group relative bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                    {{-- Abstract Background Element --}}
                    <div class="absolute -right-6 -bottom-6 text-emerald-500/5 rotate-12 group-hover:scale-125 transition-transform duration-700">
                        <i class="fa-solid fa-truck-fast text-[10rem]"></i>
                    </div>

                    <div class="relative z-10 flex flex-col gap-5">
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-xl flex items-center justify-center text-white shadow-md">
                            <i class="fa-solid fa-truck-fast text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-base font-semibold text-slate-900 mb-1">Fast Delivery</h4>
                            <p class="text-sm text-slate-400 leading-relaxed">Pengiriman kilat khusus wilayah Mojokerto</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- 4. TRUSTED SELLERS (IDE 3) --}}
        <section class="max-w-7xl mx-auto px-8 mt-16">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">{{ $settings['trusted_sellers_title'] ?? 'Toko Pilihan Bolo' }}</h2>
                    <p class="text-xs text-slate-400 mt-1">{{ $settings['trusted_sellers_subtitle'] ?? 'Belanja aman dari seller terpercaya' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6">
                @foreach($trustedSellers as $seller)
                <div class="group bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 flex items-center gap-5 relative overflow-hidden">
                    {{-- Decorative Circle --}}
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>

                    <div class="relative z-10 w-20 h-20 shrink-0">
                        <x-user-avatar :user="$seller" size="w-20 h-20" shape="rounded-xl" textSize="text-xl" />
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-white border-2 border-white shadow-sm">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path></svg>
                        </div>
                    </div>

                    <div class="relative z-10 flex-grow">
                        <h4 class="text-base font-semibold text-slate-900 group-hover:text-emerald-600 transition-colors line-clamp-1">{{ $seller->nama_toko ?? $seller->name }}</h4>
                        <div class="flex flex-col gap-1 mt-1">
                            <span class="text-xs text-slate-400">{{ $seller->item_shops_count }} Produk Tersedia</span>
                            <div class="flex items-center gap-1">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                <span class="text-xs text-green-600">Verified Seller</span>
                            </div>
                        </div>
                        <a href="{{ route('shop.public', ['seller_id' => $seller->id]) }}" class="inline-flex mt-3 text-xs font-medium text-emerald-600 hover:text-emerald-800 transition-colors">
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
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Produk Yang Baru Ditambahkan Bolo!</h2>
                <p class="text-sm text-slate-400 mt-2">Jangan sampai kehabisan stok</p>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($items as $item)
                    <x-product-card :item="$item" />
                @endforeach
            </div>

            {{-- Button: Lihat Semua Produk --}}
            <div class="mt-16 text-center">
                <a href="{{ route('shop.public') }}" 
                    class="inline-flex items-center gap-3 px-8 py-4 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 hover:border-emerald-600 hover:text-emerald-600 transition-all duration-300 group shadow-sm hover:shadow-md hover:-translate-y-0.5">
                    Lihat Semua Produk
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </section>
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

