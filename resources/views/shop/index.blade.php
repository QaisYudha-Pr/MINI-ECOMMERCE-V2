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
    <section class="max-w-7xl mx-auto px-4 sm:px-8 mt-16" data-aos="fade-up" x-data="{ 
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
                    class="px-6 py-3 rounded-2xl border transition-all whitespace-nowrap {{ !request('category') || request('category') == 'all' ? 'bg-[#00AA5B] text-white shadow-lg border-[#00AA5B]' : 'bg-white text-gray-500 border-gray-100 hover:bg-gray-50 font-bold' }}">
                    <span class="text-[10px] font-black uppercase tracking-widest">semua kategori</span>
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('shop.public', array_merge(request()->query(), ['category' => $cat])) }}"
                        class="px-6 py-3 rounded-2xl border transition-all whitespace-nowrap {{ request('category') == $cat ? 'bg-[#00AA5B] text-white shadow-lg border-[#00AA5B]' : 'bg-white text-gray-500 border-gray-100 hover:bg-gray-50 font-bold' }}">
                        <span class="text-[10px] font-black uppercase tracking-widest">{{ $cat }}</span>
                    </a>
                @endforeach
            </div>

            <div class="shrink-0 flex items-center gap-3">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 px-5 py-3 bg-white border border-gray-100 rounded-2xl text-[10px] font-black uppercase tracking-widest text-gray-600 hover:bg-gray-50 transition-all">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/></svg>
                            {{ request('sort') === 'nearest' ? 'Terdekat' : 'Terbaru' }}
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('shop.public', array_merge(request()->query(), ['sort' => 'latest']))" class="text-[10px] font-bold uppercase tracking-widest">Terbaru</x-dropdown-link>
                        <x-dropdown-link :href="route('shop.public', array_merge(request()->query(), ['sort' => 'nearest']))" class="text-[10px] font-bold uppercase tracking-widest">Terdekat (Proximity)</x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
        @endif

    {{-- HEADER INFO --}}
    @if(isset($seller) && $seller)
        {{-- Profile Toko Tokopedia Style dengan Banner --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-10 overflow-hidden relative group">
            {{-- Banner Toko --}}
            @if($seller->banner)
            <div class="h-32 sm:h-44 w-full relative overflow-hidden">
                <img src="{{ Str::startsWith($seller->banner, ['http://', 'https://']) ? $seller->banner : asset($seller->banner) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Store Banner">
                <div class="absolute inset-0 bg-gradient-to-t from-white/90 via-transparent to-transparent"></div>
            </div>
            @else
            <div class="h-24 sm:h-32 w-full bg-gradient-to-br from-[#00AA5B]/10 via-[#00AA5B]/5 to-transparent relative">
                <div class="absolute inset-0 opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>
            </div>
            @endif

            <div class="p-6 relative">
                <div class="flex flex-col lg:flex-row gap-8 items-center -mt-12 sm:-mt-16 lg:-mt-0">
                    {{-- Logo Toko --}}
                    <div class="relative shrink-0 lg:-mt-20">
                        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl overflow-hidden border-4 border-white shadow-xl bg-white">
                            <img src="{{ $seller->avatar ? (Str::startsWith($seller->avatar, ['http://', 'https://']) ? $seller->avatar : asset('storage/' . $seller->avatar)) : 'https://ui-avatars.com/api/?name=' . urlencode($seller->nama_toko ?? $seller->name) . '&color=7F9CF5&background=EBF4FF' }}" class="w-full h-full object-cover">
                        </div>
                                <div class="absolute bottom-1 right-1 w-7 h-7 bg-[#00AA5B] rounded-full border-4 border-white flex items-center justify-center text-white shadow-lg">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745a3.066 3.066 0 01-2.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745a3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path></svg>
                                </div>
                            </div>

                    {{-- Info & Buttons --}}
                    <div class="flex-grow flex flex-col justify-center text-center lg:text-left">
                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3 mb-1">
                            <h1 class="text-xl font-bold text-gray-900 uppercase tracking-tight">{{ $seller->nama_toko ?? $seller->name }}</h1>
                            <div class="flex items-center gap-1 bg-green-50 px-2 py-0.5 rounded border border-green-100">
                                <span class="text-[#00AA5B] text-[8px] font-black uppercase tracking-tighter italic">Power Merchant</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            <span class="flex items-center gap-1">
                                <span class="text-gray-900" x-text="followerCount">0</span> Pengikut
                            </span>
                            <span class="text-gray-200">|</span>
                            <span class="flex items-center gap-1">
                                <span class="text-gray-900">{{ $seller->item_shops_count }}</span> Produk
                            </span>
                        </div>
                        <p class="text-gray-400 text-xs font-bold mb-5 mt-2">{{ $seller->alamat ?? 'Mojokerto, Jawa Timur' }}</p>
                        
                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3">
                            <button 
                                @click="toggleFollow({{ $seller->id }})"
                                :class="isFollowing ? 'bg-gray-100 text-gray-500 hover:bg-gray-200' : 'bg-[#00AA5B] text-white hover:bg-[#008f4d] shadow-sm'"
                                class="px-8 py-2 rounded-lg font-bold text-[10px] uppercase tracking-wider transition-all focus:outline-none focus:ring-2 focus:ring-[#00AA5B] focus:ring-offset-2"
                                x-text="isFollowing ? 'Unfollow' : 'Follow'"></button>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $seller->phone ?? '628123456789') }}?text=Halo%20{{ urlencode($seller->nama_toko ?? $seller->name) }},%20saya%20ingin%20bertanya%20mengenai%20produk%20Anda." target="_blank" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg font-bold text-[10px] uppercase tracking-wider hover:bg-gray-50 transition-colors bg-white shadow-sm">Chat Penjual</a>
                        </div>
                    </div>

                    {{-- Stats Area --}}
                    <div class="flex items-center gap-10 lg:gap-14 shrink-0 border-t lg:border-t-0 lg:border-l border-gray-100 pt-6 lg:pt-0 lg:pl-10">
                        <div class="text-center sm:text-left">
                            <div class="flex items-center justify-center sm:justify-start gap-1">
                                <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3-.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="text-sm font-bold text-gray-900">{{ number_format($seller->avg_rating ?? 0, 1) }}</span>
                                <span class="text-[10px] font-medium text-gray-400">({{ number_format($seller->total_reviews ?? 0) }})</span>
                            </div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5 whitespace-nowrap">Rating & Ulasan</p>
                        </div>
                        <div class="text-center sm:text-left">
                            <p class="text-sm font-bold text-gray-900">{{ ($seller->total_sold ?? 0) > 1000 ? number_format(($seller->total_sold ?? 0)/1000, 1) . 'rb' : ($seller->total_sold ?? 0) }}+</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5 whitespace-nowrap">Terjual</p>
                        </div>
                        <div class="hidden sm:block text-center sm:text-left border-l border-gray-100 pl-8">
                            <p class="text-sm font-bold text-gray-900">± 15 Menit</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5 whitespace-nowrap">Pesanan Diproses</p>
                        </div>
                    </div>
                </div>

                {{-- Tokopedia Mini Tabs --}}
                <div class="mt-8 pt-4 border-t border-gray-50 flex gap-8">
                    <button 
                        @click="activeTab = 'produk'"
                        :class="activeTab === 'produk' ? 'text-[#00AA5B] after:absolute after:bottom-0 after:left-0 after:w-full after:h-0.5 after:bg-[#00AA5B]' : 'text-gray-500 hover:text-gray-900'"
                        class="relative py-2 text-xs font-bold transition-all outline-none">Produk</button>
                    <button 
                        @click="activeTab = 'ulasan'"
                        :class="activeTab === 'ulasan' ? 'text-[#00AA5B] after:absolute after:bottom-0 after:left-0 after:w-full after:h-0.5 after:bg-[#00AA5B]' : 'text-gray-500 hover:text-gray-900'"
                        class="relative py-2 text-xs font-bold transition-all outline-none">Ulasan</button>
                </div>
            </div>
        </div>

        {{-- Section Divider & Title --}}
        <div class="flex justify-between items-center mb-6">
            <div x-show="activeTab === 'produk'" class="flex items-center gap-3">
                <h2 class="text-lg font-bold text-gray-900 tracking-tight uppercase">Semua Produk</h2>
                <div class="w-16 h-px bg-[#00AA5B]/20"></div>
            </div>
            
            @auth
                @if(auth()->id() === $seller->id || auth()->user()->hasRole('seller') || auth()->user()->hasRole('admin'))
                    <a href="{{ route('item-shop.create') }}"
                        class="px-8 py-3 bg-[#00AA5B] text-white rounded-xl hover:bg-[#008f4d] transition-all duration-300 font-black text-[10px] uppercase tracking-widest flex items-center gap-2 shadow-lg shadow-green-100">
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
                <h2 class="text-lg font-bold text-gray-900 mb-6 underline decoration-[#00AA5B] decoration-4 underline-offset-8">Ulasan Pembeli ({{ number_format($seller->total_reviews ?? 0) }})</h2>
                @if(isset($sellerReviews) && $sellerReviews->count() > 0)
                    <div class="space-y-6">
                        @foreach($sellerReviews as $review)
                            <div class="pb-6 border-b border-gray-50 last:border-0 last:pb-0">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-400 flex-shrink-0">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                    <div class="flex-grow">
                                        <div class="flex items-center justify-between mb-1">
                                            <h4 class="text-sm font-bold text-gray-800">{{ $review->user->name }}</h4>
                                            <span class="text-[10px] text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="flex items-center gap-1 mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-3 h-3 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }} fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3-.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endfor
                                        </div>
                                        <p class="text-gray-600 text-sm italic mb-2">"{{ $review->comment }}"</p>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] text-gray-400">Membeli:</span>
                                            <span class="text-[10px] font-bold text-[#00AA5B]">{{ $review->itemShop->nama_barang }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-400 font-medium italic">Toko ini belum memiliki ulasan pembeli.</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        {{-- PUBLIC HEADER --}}
        <div class="flex justify-between items-end mb-10">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-gray-900 uppercase tracking-tight">
                    {{ request('search') ? 'Hasil Pencarian: "' . request('search') . '"' : (request('category') ? 'Koleksi ' . request('category') : 'Semua Produk') }}
                </h2>
                <div class="flex flex-wrap items-center justify-between gap-4 mt-2">
                    <div class="flex items-center gap-2">
                        <span class="w-8 h-1 bg-[#00AA5B] rounded-full"></span>
                        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-[0.2em]">{{ $items->total() }} Produk Ditemukan</p>
                    </div>
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
    @endif

        {{-- PRODUCTS GRID --}}
        <div x-show="activeTab === 'produk'" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
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
                                    @if(isset($item->distance))
                                        <span class="text-[#00AA5B] ml-1">({{ number_format($item->distance, 1) }} km)</span>
                                    @endif
                                </span>

                                {{-- Quick Add Button --}}
                                <button @click.prevent="if({{ $item->stok }} > 0) $store.cart.add(items.find(i => i.id === {{ $item->id }})); else Swal.fire({icon: 'error', title: 'Habis bolo!', text: 'Produk ini sudah ludes terjual.', customClass: {popup: 'rounded-2xl'}})" 
                                    :class="{{ $item->stok }} <= 0 ? 'opacity-50 cursor-not-allowed bg-gray-100 text-gray-300' : 'bg-gray-50 rounded-xl text-gray-400 hover:bg-[#00AA5B] hover:text-white transition-all shadow-sm'"
                                    class="p-2 rounded-xl transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
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
        <div x-show="activeTab === 'produk'" class="mt-16">
            {{ $items->links() }}
        </div>
    </section>

    @include('components.cart-modal')

</x-app-layout>

<script>
    console.log('Halo dari item-shop public view!');
</script>

