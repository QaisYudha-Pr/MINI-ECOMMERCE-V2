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
            background-color: var(--brand-600, #059669);
            color: white;
            box-shadow: 0 4px 12px var(--brand-100, rgba(209, 250, 229, 0.5));
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
    <section class="max-w-7xl mx-auto px-4 sm:px-8 mt-16" x-data="{ 
        activeTab: 'produk',
        isFollowing: {{ $isFollowing ? 'true' : 'false' }},
        followerCount: {{ $seller ? $seller->followers()->count() : 0 }},
        items: {{ $items->map(function($item) {
            return [
                'id' => $item->id,
                'user_id' => $item->user_id,
                'nama_barang' => $item->nama_barang,
                'harga' => $item->harga,
                'gambar' => asset($item->gambar),
                'kategori' => $item->kategori,
                'stok' => $item->stok,
                'berat' => $item->berat ?? 1000,
                'lokasi' => $item->lokasi ?? 'Mojokerto'
            ];
        })->values()->toJson() }},
        toggleFollow(userId) {
            @guest
                window.location.href = '{{ route('login') }}';
                return;
            @endguest
            fetch(`/follow/${userId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.followerCount = data.count;
                if (data.status === 'followed') {
                    this.isFollowing = true;
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Follow!',
                        text: 'Kamu akan mendapatkan update produk terbaru dari toko ini.',
                        timer: 1500,
                        showConfirmButton: false,
                        customClass: { popup: 'rounded-2xl' }
                    });
                } else {
                    this.isFollowing = false;
                    Swal.fire({
                        icon: 'info',
                        title: 'Berhasil Unfollow',
                        text: 'Kamu berhenti mengikuti toko ini.',
                        timer: 1500,
                        showConfirmButton: false,
                        customClass: { popup: 'rounded-2xl' }
                    });
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gagal memproses permintaan.'
                });
            });
        }
    }">
        @if(!isset($seller))
        {{-- CATEGORY BAR & FILTER --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 pb-8 border-b border-gray-50">
            <div class="flex gap-3 overflow-x-auto no-scrollbar scroll-smooth grow pb-2 md:pb-0">
                <a href="{{ route('shop.public', array_merge(request()->query(), ['category' => 'all'])) }}"
                    class="px-6 py-3 rounded-2xl border transition-all whitespace-nowrap {{ !request('category') || request('category') == 'all' ? 'bg-emerald-600 text-white shadow-lg border-emerald-600' : 'bg-white text-gray-500 border-gray-100 hover:bg-gray-50 font-bold' }}">
                    <span class="text-xs font-semibold">semua kategori</span>
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('shop.public', array_merge(request()->query(), ['category' => $cat])) }}"
                        class="px-6 py-3 rounded-2xl border transition-all whitespace-nowrap {{ request('category') == $cat ? 'bg-emerald-600 text-white shadow-lg border-emerald-600' : 'bg-white text-gray-500 border-gray-100 hover:bg-gray-50 font-bold' }}">
                        <span class="text-xs font-semibold">{{ $cat }}</span>
                    </a>
                @endforeach
            </div>

            <div class="shrink-0 flex items-center gap-3">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 px-5 py-3 bg-white border border-gray-100 rounded-2xl text-xs font-semibold text-gray-600 hover:bg-gray-50 transition-all">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/></svg>
                            {{ request('sort') === 'nearest' ? 'Terdekat' : 'Terbaru' }}
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('shop.public', array_merge(request()->query(), ['sort' => 'latest']))" class="text-xs font-bold">Terbaru</x-dropdown-link>
                        <x-dropdown-link :href="route('shop.public', array_merge(request()->query(), ['sort' => 'nearest']))" class="text-xs font-bold">Terdekat (Proximity)</x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
        @endif

    {{-- HEADER INFO --}}
    @if(isset($seller) && $seller)
        {{-- Seller Profile Card - Premium Design --}}
        <div class="mb-10 relative">
            {{-- Banner --}}
            <div class="relative h-40 sm:h-52 rounded-t-3xl overflow-hidden">
                @if($seller->banner)
                <img src="{{ Str::startsWith($seller->banner, ['http://', 'https://']) ? $seller->banner : asset($seller->banner) }}" class="w-full h-full object-cover" alt="Store Banner">
                @else
                <div class="w-full h-full bg-gradient-to-br from-emerald-600 via-emerald-400 to-emerald-700"></div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

                {{-- Overlay Content on Banner --}}
                <div class="absolute bottom-0 left-0 right-0 p-6 flex items-end gap-5">
                    {{-- Avatar --}}
                    <div class="relative shrink-0">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl overflow-hidden border-[3px] border-white shadow-lg bg-white">
                            @if($seller->avatar)
                                <img src="{{ Str::startsWith($seller->avatar, ['http://', 'https://']) ? $seller->avatar : asset('storage/' . $seller->avatar) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white text-2xl sm:text-3xl font-black">
                                    {{ strtoupper(substr($seller->nama_toko ?? $seller->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-emerald-600 rounded-lg border-2 border-white flex items-center justify-center text-white shadow-lg">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path></svg>
                        </div>
                    </div>

                    {{-- Name & Badge --}}
                    <div class="flex-1 min-w-0 pb-1">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <h1 class="text-lg sm:text-xl font-bold text-white truncate">{{ $seller->nama_toko ?? $seller->name }}</h1>
                            @php $badge = $seller->getMerchantBadge(); @endphp
                            <span class="px-2.5 py-0.5 {{ $badge['color'] }} text-xs font-semibold rounded-md shadow-sm border border-white/20">{{ $badge['icon'] }} {{ $badge['label'] }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-white/70 text-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="truncate">{{ $seller->alamat ?? 'Mojokerto, Jawa Timur' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom Section --}}
            <div class="bg-white rounded-b-3xl border border-t-0 border-gray-100 shadow-sm">
                <div class="px-6 py-5">
                    <div class="flex flex-col lg:flex-row items-center gap-6">
                        {{-- Stats Row --}}
                        <div class="flex items-center gap-6 lg:gap-10 mt-1">
                            {{-- Unified Rating --}}
                            <div class="text-center group relative cursor-help">
                                <div class="flex flex-col items-center">
                                    <div class="flex items-center justify-center gap-0.5 mb-1">
                                        @php $rating = (float)($seller->avg_rating ?? 0); @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $rating ? 'text-amber-400' : ($i - 0.8 <= $rating ? 'text-amber-300' : 'text-gray-200') }} fill-current" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <div class="flex items-center justify-center gap-1">
                                        <span class="text-sm font-bold text-gray-900">{{ number_format($rating, 1) }}</span>
                                        <span class="text-[10px] text-gray-400 font-bold">({{ number_format($seller->total_reviews ?? 0) }})</span>
                                    </div>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1 group-hover:text-emerald-500 transition-colors">Rating Toko</p>
                                </div>
                                
                                {{-- Tooltip Breakdown --}}
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-3 w-52 bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-gray-100 p-4 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-[100] text-left">
                                    <div class="space-y-3">
                                        <div>
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="text-[10px] font-bold text-gray-400 uppercase">Kepuasan Produk</span>
                                                <span class="text-[10px] font-bold text-emerald-600">{{ number_format($seller->product_avg ?? 0, 1) }}</span>
                                            </div>
                                            <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-amber-400 rounded-full" style="width: {{ ($seller->product_avg ?? 0) * 20 }}%"></div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="text-[10px] font-bold text-gray-400 uppercase">Kualitas Layanan</span>
                                                <span class="text-[10px] font-bold text-emerald-600">{{ number_format($seller->service_avg ?? 0, 1) }}</span>
                                            </div>
                                            <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ ($seller->service_avg ?? 0) * 20 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="absolute bottom-[-6px] left-1/2 -translate-x-1/2 w-3 h-3 bg-white border-r border-b border-gray-100 rotate-45"></div>
                                </div>
                            </div>

                            <div class="w-px h-8 bg-gray-100"></div>

                            <div class="text-center">
                                <p class="text-lg font-bold text-gray-900" x-text="followerCount">0</p>
                                <p class="text-[11px] font-bold text-gray-400">Pengikut</p>
                            </div>

                            <div class="w-px h-8 bg-gray-100"></div>

                            <div class="text-center">
                                <p class="text-lg font-bold text-gray-900">{{ $seller->item_shops_count }}</p>
                                <p class="text-[11px] font-bold text-gray-400">Produk</p>
                            </div>

                            <div class="w-px h-8 bg-gray-100"></div>

                            <div class="text-center">
                                <p class="text-lg font-bold text-gray-900">{{ ($seller->total_sold ?? 0) > 1000 ? number_format(($seller->total_sold ?? 0)/1000, 1) . 'rb' : ($seller->total_sold ?? 0) }}+</p>
                                <p class="text-[11px] font-bold text-gray-400">Terjual</p>
                            </div>
                            <div class="w-px h-8 bg-gray-100 hidden sm:block"></div>
                            <div class="text-center hidden sm:block">
                                <p class="text-lg font-bold text-gray-900">±15<span class="text-xs text-gray-400 font-bold"> min</span></p>
                                <p class="text-[11px] font-bold text-gray-400">Diproses</p>
                            </div>
                            <div class="w-px h-8 bg-gray-100 hidden sm:block"></div>
                            <div class="text-center hidden sm:block">
                                <div class="flex items-center justify-center gap-1 mb-0.5">
                                    @if($seller->isOnline())
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                                    <span class="text-xs font-bold text-emerald-600">Online</span>
                                    @else
                                    <span class="w-2 h-2 bg-gray-300 rounded-full"></span>
                                    <span class="text-xs font-bold text-gray-400">Offline</span>
                                    @endif
                                </div>
                                <p class="text-[11px] font-bold text-gray-400">Status</p>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-2 shrink-0">
                            <button 
                                @click="toggleFollow({{ $seller->id }})"
                                :class="isFollowing ? 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-200' : 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-lg shadow-emerald-100'"
                                class="px-6 py-2.5 rounded-xl font-bold text-xs transition-all"
                                x-text="isFollowing ? 'Following' : 'Follow'"></button>
                            @auth
                            <a href="{{ route('chat.start', $seller) }}" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold text-xs hover:bg-gray-50 hover:border-gray-300 transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                Chat
                            </a>
                            @else
                            <a href="{{ route('login') }}" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold text-xs hover:bg-gray-50 hover:border-gray-300 transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                Chat
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>

                {{-- Tabs --}}
                <div class="px-6 border-t border-gray-50 flex gap-0">
                    <button 
                        @click="activeTab = 'produk'"
                        :class="activeTab === 'produk' ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-400 border-b-2 border-transparent hover:text-gray-600'"
                        class="px-5 py-3 text-xs font-semibold transition-all">Produk</button>
                    <button 
                        @click="activeTab = 'ulasan'"
                        :class="activeTab === 'ulasan' ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-400 border-b-2 border-transparent hover:text-gray-600'"
                        class="px-5 py-3 text-xs font-semibold transition-all">Ulasan</button>
                </div>
            </div>
        </div>

        {{-- Section Divider & Title --}}
        <div class="flex justify-between items-center mb-6">
            <div x-show="activeTab === 'produk'" class="flex items-center gap-3">
                <h2 class="text-lg font-bold text-gray-900 tracking-tight uppercase">Semua Produk</h2>
                <div class="w-16 h-px bg-emerald-600/20"></div>
            </div>
            
            @auth
                @if($seller && (auth()->id() === $seller->id))
                    <a href="{{ route('item-shop.create') }}"
                        class="px-8 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-all duration-300 font-bold text-xs flex items-center gap-2 shadow-lg shadow-emerald-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Produk
                    </a>
                @endif
            @endauth
        </div>

        {{-- Tab Ulasan --}}
        <div x-show="activeTab === 'ulasan'" x-cloak class="mt-6 mb-12">
            <div class="bg-white rounded-xl border border-gray-100 p-6 md:p-8">
                <h2 class="text-lg font-bold text-gray-900 mb-6 underline decoration-emerald-600 decoration-4 underline-offset-8">Ulasan Pembeli ({{ number_format($seller->total_reviews ?? 0) }})</h2>
                @if(isset($sellerReviews) && $sellerReviews->count() > 0)
                    <div class="space-y-6">
                        @foreach($sellerReviews as $review)
                            <div class="pb-6 border-b border-gray-50 last:border-0 last:pb-0">
                                @php
                                    $reviewer = $review->user ?? $review->buyer;
                                @endphp
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-400 flex-shrink-0">
                                        {{ substr($reviewer->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div class="flex-grow">
                                        <div class="flex items-center justify-between mb-1">
                                            <h4 class="text-sm font-bold text-gray-800">{{ $reviewer->name ?? 'Pembeli' }}</h4>
                                            <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="flex items-center gap-1 mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-3 h-3 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }} fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3-.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endfor
                                        </div>
                                        <p class="text-gray-600 text-sm mb-2">"{{ $review->comment }}"</p>
                                        <div class="flex items-center gap-2">
                                            @if($review->itemShop)
                                                <span class="text-xs text-gray-400">Membeli:</span>
                                                <span class="text-xs font-bold text-emerald-600">{{ $review->itemShop->nama_barang }}</span>
                                            @else
                                                <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-100 flex items-center gap-1">
                                                    <i class="fa-solid fa-store"></i> Ulasan Toko
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-400 font-medium">Toko ini belum memiliki ulasan pembeli.</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        {{-- PUBLIC HEADER --}}
        <div class="flex justify-between items-end mb-10">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">
                    {{ request('search') ? 'Hasil Pencarian: "' . request('search') . '"' : (request('category') ? 'Koleksi ' . request('category') : 'Semua Produk') }}
                </h2>
                <div class="flex flex-wrap items-center justify-between gap-4 mt-2">
                    <div class="flex items-center gap-2">
                        <span class="w-8 h-1 bg-emerald-600 rounded-full"></span>
                        <p class="text-gray-400 text-xs font-bold">{{ $items->total() }} Produk Ditemukan</p>
                    </div>
                </div>
            </div>
            
            @guest
                <a href="{{ route('seller.create') }}"
                    class="hidden sm:flex px-8 py-4 bg-emerald-600 text-white rounded-2xl hover:bg-emerald-700 transition-all duration-300 font-bold text-xs items-center gap-2 shadow-xl shadow-emerald-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Mulai Berjualan
                </a>
            @else
                @unlessrole('seller|admin')
                    <a href="{{ route('seller.create') }}"
                        class="hidden sm:flex px-8 py-4 bg-emerald-600 text-white rounded-2xl hover:bg-emerald-700 transition-all duration-300 font-bold text-xs items-center gap-2 shadow-xl shadow-emerald-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Mulai Berjualan
                    </a>
                @endunlessrole
            @endguest
        </div>
    @endif

        {{-- PRODUCTS GRID --}}
        <div x-show="activeTab === 'produk'" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @forelse ($items as $index => $item)
                <x-product-card :item="$item" />
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
        <div x-show="activeTab === 'produk'" class="mt-16">
            {{ $items->links() }}
        </div>
    </section>

</x-app-layout>

<script>
    console.log('Halo dari item-shop public view!');
</script>


