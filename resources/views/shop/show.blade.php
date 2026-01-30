<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
        <style>
            .cropper-view-box, .cropper-face { border-radius: 2rem; }
        </style>
    @endpush
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    @endpush

    

    <div class="py-4 bg-white min-h-screen">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- BREADCRUMBS --}}
            <nav class="flex items-center gap-2 text-[12px] font-medium text-gray-500 mb-8 overflow-x-auto whitespace-nowrap pb-1">
                <a href="{{ route('home') }}" class="text-emerald-500 hover:text-emerald-600 transition-colors">Home</a>
                <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('shop.public', ['category' => $itemShop->kategori]) }}" class="text-emerald-500 hover:text-emerald-600 transition-colors">{{ $itemShop->kategori ?? 'Umum' }}</a>
                <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-gray-400 truncate">{{ $itemShop->nama_barang }}</span>
            </nav>

            <div class="grid lg:grid-cols-12 gap-10 items-start">
                
                {{-- LEFT: PRODUCT IMAGES --}}
                <div class="lg:col-span-4 lg:sticky lg:top-24 space-y-4">
                    <div class="aspect-square rounded-[2rem] overflow-hidden border border-gray-100 shadow-sm bg-gray-50">
                        <img src="{{ $itemShop->gambar ? asset($itemShop->gambar) : 'https://via.placeholder.com/800' }}" 
                             class="w-full h-full object-cover shadow-inner">
                    </div>
                </div>

                {{-- CENTER: PRODUCT INFO --}}
                <div class="lg:col-span-5 space-y-8">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 leading-tight mb-2">{{ $itemShop->nama_barang }}</h1>
                        
                        <div class="flex items-center gap-3 text-sm">
                            <span class="text-gray-500">Terjual <span class="text-gray-900 font-bold">{{ $itemShop->stok > 100 ? '1 rb+' : $itemShop->stok . ' terjual' }}</span></span>
                            <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                <span class="text-gray-900 font-bold">{{ number_format($itemShop->reviews()->avg('rating') ?? 5, 1) }}</span>
                                <span class="text-gray-400">({{ $itemShop->reviews()->count() }} rating)</span>
                            </div>
                        </div>

                        <div class="mt-4 text-4xl font-extrabold text-gray-900 flex items-center gap-1">
                            <span class="text-xl font-bold mt-1">Rp</span>
                            {{ number_format($itemShop->harga, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="border-y border-gray-100 py-6 group/seller">
                        <a href="{{ route('shop.public', ['seller_id' => $itemShop->user_id]) }}" class="flex items-center justify-between hover:bg-gray-50 p-2 -m-2 rounded-2xl transition-all">
                            <div class="flex items-center gap-4">
                                <img src="{{ $itemShop->user->avatar ? (Str::startsWith($itemShop->user->avatar, ['http://', 'https://']) ? $itemShop->user->avatar : asset($itemShop->user->avatar)) : 'https://ui-avatars.com/api/?name='.urlencode($itemShop->user->nama_toko ?? $itemShop->user->name ?? 'Admin').'&background=EBF4FF&color=7F9CF5' }}" 
                                     class="w-12 h-12 rounded-full border border-gray-100 object-cover">
                                <div>
                                    <h3 class="font-bold text-gray-900 group-hover/seller:text-[#00AA5B] transition-colors uppercase text-sm tracking-tight">{{ $itemShop->user->nama_toko ?? $itemShop->user->name ?? 'Official Store' }}</h3>
                                    <p class="text-[10px] text-green-500 font-bold flex items-center gap-1 uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                        Online
                                    </p>
                                </div>
                            </div>
                            <div class="px-4 py-2 border border-gray-200 rounded-lg text-[10px] font-black uppercase tracking-widest text-gray-600 group-hover/seller:border-[#00AA5B] group-hover/seller:text-[#00AA5B] transition-all">
                                Kunjungi Toko
                            </div>
                        </a>
                    </div>

                    <div class="space-y-4" x-data="{ tab: 'detail' }">
                        <div class="flex border-b border-gray-200">
                            <button @click="tab = 'detail'" :class="tab === 'detail' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500'" class="px-4 py-2 border-b-2 font-bold text-sm transition-all">Detail Produk</button>
                            <button @click="tab = 'info'" :class="tab === 'info' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500'" class="px-4 py-2 border-b-2 font-bold text-sm transition-all">Info Penting</button>
                        </div>
                        
                        <div x-show="tab === 'detail'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-x-2" x-transition:enter-end="opacity-100 transform translate-x-0">
                            <div class="text-sm space-y-3">
                                <div class="flex gap-10">
                                    <span class="text-gray-400 w-24">Kondisi</span>
                                    <span class="text-gray-900 font-medium">Baru</span>
                                </div>
                                <div class="flex gap-10">
                                    <span class="text-gray-400 w-24">Berat Satuan</span>
                                    <span class="text-gray-900 font-medium">{{ $itemShop->berat ?? 1000 }} g</span>
                                </div>
                                <div class="flex gap-10">
                                    <span class="text-gray-400 w-24">Kategori</span>
                                    <a href="{{ route('shop.public', ['category' => $itemShop->kategori]) }}" class="text-emerald-500 font-bold hover:underline">{{ $itemShop->kategori ?? 'Umum' }}</a>
                                </div>
                                <div class="prose prose-sm text-gray-600 max-w-none pt-4">
                                    {!! nl2br(e($itemShop->deskripsi)) !!}
                                </div>
                            </div>
                        </div>

                        <div x-show="tab === 'info'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-2" x-transition:enter-end="opacity-100 transform translate-x-0" x-cloak>
                            <div class="text-sm space-y-5">
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-2">Kebijakan Pengembalian</h4>
                                    <p class="text-gray-600 leading-relaxed text-xs">Produk dapat dikembalikan dalam waktu 2x24 jam setelah barang diterima jika terdapat kerusakan pabrik atau kesalahan pengiriman. Wajib menyertakan video unboxing tanpa terputus.</p>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-2">Informasi Pengiriman</h4>
                                    <p class="text-gray-600 leading-relaxed text-xs">Pesanan sebelum jam 15:00 WIB akan dikirim pada hari yang sama. Estimasi pengiriman tergantung pada lokasi dan kurir yang dipilih.</p>
                                </div>
                                <div class="p-4 bg-orange-50 rounded-2xl border border-orange-100 italic text-[11px] text-orange-700">
                                    *Warna produk mungkin sedikit berbeda karena pencahayaan pada foto atau pengaturan layar monitor Anda.
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- REVIEW PREVIEW IN CENTER --}}
                    <div class="pt-8 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-gray-900">Ulasan Pembeli</h3>
                            <button class="text-emerald-500 text-sm font-bold">Lihat Semua</button>
                        </div>
                        <div class="space-y-4">
                            @forelse($itemShop->reviews()->latest()->take(2)->get() as $review)
                                <div class="p-4 bg-gray-50 rounded-2xl">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="flex text-yellow-400 h-3">
                                            @for($i=1; $i<=5; $i++)
                                                <svg class="w-3 h-3 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            @endfor
                                        </div>
                                        <span class="text-[10px] text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm font-bold text-gray-900 mb-1">{{ $review->user->name }}</p>
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $review->comment }}</p>
                                </div>
                            @empty
                                <p class="text-gray-400 text-sm italic">Belum ada ulasan untuk produk ini.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- RIGHT: STICKY CHECKOUT CARD --}}
                <div class="lg:col-span-3 lg:sticky lg:top-24 space-y-4" 
                    x-data="{
                        isFavorited: {{ Auth::check() && Auth::user()->favoriteItems()->where('item_shop_id', $itemShop->id)->exists() ? 'true' : 'false' }},
                        quantity: 1,
                        item: {
                            id: {{ $itemShop->id }},
                            user_id: {{ $itemShop->user_id }},
                            nama_barang: '{{ $itemShop->nama_barang }}',
                            harga: {{ $itemShop->harga }},
                            berat: {{ $itemShop->berat ?? 1000 }},
                            seller_lat: {{ $itemShop->user->latitude ?? 'null' }},
                            seller_lng: {{ $itemShop->user->longitude ?? 'null' }},
                            seller_name: '{{ $itemShop->user->nama_toko ?? $itemShop->user->name }}',
                            gambar: '{{ asset($itemShop->gambar) }}',
                            stok: {{ $itemShop->stok }}
                        },

                        toggleFavorite() {
                            @auth
                                fetch('{{ route('favorite.toggle', $itemShop->id) }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(r => r.json())
                                .then(data => {
                                    this.isFavorited = data.status;
                                    Swal.fire({
                                        toast: true,
                                        position: 'top-end',
                                        icon: 'success',
                                        title: data.message,
                                        showConfirmButton: false,
                                        timer: 1500,
                                        customClass: { popup: 'rounded-2xl' }
                                    });
                                });
                            @else
                                window.location.href = '{{ route('login') }}';
                            @endauth
                        },

                        shareProduct() {
                            if (navigator.share) {
                                navigator.share({
                                    title: '{{ $itemShop->nama_barang }}',
                                    text: 'Cek produk keren ini di MiniQ-Store!',
                                    url: window.location.href,
                                }).catch(console.error);
                            } else {
                                navigator.clipboard.writeText(window.location.href);
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Link disalin ke clipboard!',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        },
                        
                        checkoutNow() {
                            if (this.item.stok <= 0) return;
                            
                            // Tambahkan ke keranjang secara silent (tanpa alert)
                            $store.cart.add(this.item, this.quantity, true);
                            
                            // Ambil hanya item ini untuk dicheckout
                            const toCheckout = $store.cart.items.filter(i => i.id === this.item.id);
                            localStorage.setItem('checkout_items', JSON.stringify(toCheckout));
                            window.location.href = '{{ route('checkout.index') }}';
                        }
                    }">
                    
                    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm space-y-6">
                        <h4 class="font-bold text-gray-900">Atur jumlah dan catatan</h4>
                        
                        {{-- Quantity Selector --}}
                        <div class="flex items-center gap-4">
                            <div class="flex items-center border border-gray-200 rounded-lg p-1">
                                <button @click="if(quantity > 1) quantity--" class="w-8 h-8 flex items-center justify-center text-emerald-500 hover:bg-emerald-50 rounded-md transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                </button>
                                <input type="number" x-model="quantity" class="w-12 text-center border-none focus:ring-0 text-sm font-bold text-gray-900" readonly>
                                <button @click="if(quantity < {{ $itemShop->stok }}) quantity++" class="w-8 h-8 flex items-center justify-center text-emerald-500 hover:bg-emerald-50 rounded-md transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </button>
                            </div>
                            <span class="text-sm font-medium text-gray-900">Stok: <span class="font-bold">{{ $itemShop->stok }}</span></span>
                        </div>

                        {{-- Subtotal --}}
                        <div class="flex justify-between items-end">
                            <span class="text-sm text-gray-500 font-medium">Subtotal</span>
                            <div class="text-lg font-bold text-gray-900">
                                <span class="text-xs">Rp</span><span x-text="(quantity * {{ $itemShop->harga }}).toLocaleString('id-ID')"></span>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="space-y-2 pt-2">
                            @auth
                                <button @click="$store.cart.add(item, quantity)" class="w-full py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold text-sm transition-all shadow-sm">
                                    + Keranjang
                                </button>
                                <button @click="checkoutNow" class="w-full py-2.5 bg-white border border-emerald-500 text-emerald-500 hover:bg-emerald-50 rounded-xl font-bold text-sm transition-all">
                                    Beli Langsung
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="block w-full py-2.5 bg-gray-900 text-white text-center rounded-xl font-bold text-sm transition-all">
                                    Login untuk Membeli
                                </a>
                            @endauth
                        </div>

                        {{-- Secondary Actions (Share & Wishlist) --}}
                        <div class="flex items-center justify-center gap-6 pt-2 border-t border-gray-50">
                            <button @click="toggleFavorite" class="flex items-center gap-2 text-xs font-bold transition-colors" :class="isFavorited ? 'text-rose-500' : 'text-gray-500 hover:text-rose-500'">
                                <svg class="w-4 h-4 transition-transform group-hover:scale-110" :fill="isFavorited ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <span x-text="isFavorited ? 'Wishlist' : 'Wishlist'"></span>
                            </button>
                            <div class="w-px h-4 bg-gray-200"></div>
                            <button @click="shareProduct" class="flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-emerald-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                                Share
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            {{-- FULL REVIEW SECTION --}}
            <div class="mt-24 space-y-12" data-aos="fade-up" x-data="{
                filters: {
                    media: false,
                    ratings: []
                },
                toggleRating(rating) {
                    if (this.filters.ratings.includes(rating)) {
                        this.filters.ratings = this.filters.ratings.filter(r => r !== rating);
                    } else {
                        this.filters.ratings.push(rating);
                    }
                },
                shouldShow(itemRating, hasMedia) {
                    const ratingMatch = this.filters.ratings.length === 0 || this.filters.ratings.includes(itemRating);
                    const mediaMatch = !this.filters.media || hasMedia;
                    return ratingMatch && mediaMatch;
                }
            }">
                <div class="flex items-center gap-4 px-2">
                    <div class="h-12 w-1.5 bg-indigo-600 rounded-full"></div>
                    <div>
                        <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter leading-none">Ulasan <span class="text-indigo-600">Pembeli</span></h2>
                        <p class="text-xs font-bold text-gray-400 mt-1 uppercase tracking-widest">Apa yang mereka katakan tentang produk ini</p>
                    </div>
                </div>

                @php
                    $totalReviews = $itemShop->reviews()->count();
                    $avgRating = $itemShop->reviews()->avg('rating') ?? 0;
                    $stars = [];
                    for($i=5; $i>=1; $i--) {
                        $count = $itemShop->reviews()->where('rating', $i)->count();
                        $stars[$i] = [
                            'count' => $count,
                            'percent' => $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0
                        ];
                    }
                @endphp

                {{-- SUMMARY BOX --}}
                <div class="bg-white p-8 rounded-[3rem] shadow-xl shadow-gray-100 border border-gray-50">
                    <div class="grid md:grid-cols-3 gap-12 items-center">
                        <div class="text-center md:border-r border-gray-100 pr-8">
                            <div class="flex items-center justify-center gap-2 mb-2">
                                <span class="text-6xl font-black text-gray-900 tracking-tighter">{{ number_format($avgRating, 1) }}</span>
                                <span class="text-gray-400 font-bold text-xl">/ 5.0</span>
                            </div>
                            <div class="flex justify-center text-yellow-400 mb-4">
                                @for($i=1; $i<=5; $i++)
                                    <svg class="w-6 h-6 {{ $i <= round($avgRating) ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @endfor
                            </div>
                            <p class="text-sm font-bold text-gray-900">100% pembeli merasa puas</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $totalReviews }} rating • {{ $totalReviews }} ulasan</p>
                        </div>

                        <div class="md:col-span-2 space-y-3">
                            @foreach($stars as $star => $data)
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-1 w-8">
                                        <svg class="w-3.5 h-3.5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        <span class="text-xs font-bold text-gray-600">{{ $star }}</span>
                                    </div>
                                    <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-emerald-500 rounded-full transition-all duration-1000" style="width: {{ $data['percent'] }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-gray-400 w-12 text-right">({{ $data['count'] }})</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- PHOTO GALLERY FROM REVIEWS --}}
                @php $reviewPhotos = $itemShop->reviews()->whereNotNull('photo')->latest()->take(10)->get(); @endphp
                @if($reviewPhotos->count() > 0)
                    <div class="space-y-4">
                        <h4 class="text-sm font-black uppercase tracking-widest text-gray-900 px-2">Foto dari Pembeli</h4>
                        <div class="flex gap-4 overflow-x-auto pb-4 px-2 no-scrollbar">
                            @foreach($reviewPhotos as $rp)
                                <div class="flex-shrink-0 w-32 h-32 rounded-2xl overflow-hidden border-2 border-white shadow-md hover:scale-105 transition-transform cursor-pointer"
                                     onclick="Swal.fire({imageUrl: '{{ asset($rp->photo) }}', showConfirmButton: false, customClass: {popup: 'rounded-3xl'}})">
                                    <img src="{{ asset($rp->photo) }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="grid md:grid-cols-3 gap-12 items-start">
                    {{-- Sidebar Filters & Form (Kiri) --}}
                    <div class="md:col-span-1 space-y-8 sticky top-24">
                        {{-- FILTER REVIEWS --}}
                        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
                            <h4 class="font-bold text-gray-900 mb-6 flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 01-.293.707V17l-4 4v-6.586a1 1 0 01.293-.707L15.414 7.293A1 1 0 0015 6H5a1 1 0 00-1 1v2.586a1 1 0 01-.293.707L3.293 10.293A1 1 0 003 11v8a1 1 0 001 1h16a1 1 0 001-1v-8a1 1 0 00-.293-.707L16 6V4z"/></svg>
                                Filter Ulasan
                            </h4>
                            
                            <div class="space-y-6">
                                <div>
                                    <p class="text-[10px] font-black uppercase text-gray-400 mb-3 tracking-widest">Media</p>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" x-model="filters.media" class="w-5 h-5 rounded-lg border-gray-200 text-emerald-500 focus:ring-emerald-500 transition-all">
                                        <span class="text-sm text-gray-600 group-hover:text-emerald-600 transition-colors">Dengan Foto & Video</span>
                                    </label>
                                </div>

                                <div>
                                    <p class="text-[10px] font-black uppercase text-gray-400 mb-3 tracking-widest">Rating</p>
                                    <div class="space-y-2">
                                        @for($i=5; $i>=1; $i--)
                                            <label class="flex items-center gap-3 cursor-pointer group">
                                                <input type="checkbox" @change="toggleRating({{ $i }})" class="w-5 h-5 rounded-lg border-gray-200 text-emerald-500 focus:ring-emerald-500">
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                    <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors">{{ $i }}</span>
                                                </div>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>

                        @auth
                            <div class="bg-white p-8 rounded-[2rem] shadow-xl shadow-gray-200/50 border border-white">
                                <h4 class="text-xs font-black uppercase tracking-widest mb-6 text-gray-900">Tulis Ulasan</h4>
                                <form action="{{ route('reviews.store', $itemShop->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5" 
                                    x-data="{ 
                                        rating: 5, 
                                        preview: null, 
                                        showCropper: false,
                                        cropper: null,
                                        handleFile(e) {
                                            const file = e.target.files[0];
                                            if (!file) return;
                                            const reader = new FileReader();
                                            reader.onload = (event) => {
                                                this.showCropper = true;
                                                const img = document.getElementById('cropper-target');
                                                img.src = event.target.result;
                                                this.$nextTick(() => {
                                                    if (this.cropper) this.cropper.destroy();
                                                    this.cropper = new Cropper(img, {
                                                        aspectRatio: 1,
                                                        viewMode: 1,
                                                        dragMode: 'move',
                                                        background: false,
                                                        autoCropArea: 1
                                                    });
                                                });
                                            };
                                            reader.readAsDataURL(file);
                                        },
                                        saveCrop() {
                                            const canvas = this.cropper.getCroppedCanvas({ width: 800, height: 800 });
                                            this.preview = canvas.toDataURL('image/jpeg');
                                            document.getElementById('cropped_data').value = this.preview;
                                            this.showCropper = false;
                                        }
                                    }">
                                    @csrf
                                    <input type="hidden" name="rating" :value="rating">
                                    <input type="hidden" name="cropped_photo" id="cropped_data">

                                    {{-- Modal Cropper --}}
                                    <div x-show="showCropper" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm" x-cloak>
                                        <div class="bg-white rounded-[2.5rem] p-8 max-w-xl w-full shadow-2xl" @click.away="showCropper = false">
                                            <div class="flex justify-between items-center mb-6">
                                                <h3 class="text-xl font-black uppercase tracking-tighter">Sesuaikan Foto</h3>
                                                <button type="button" @click="showCropper = false" class="text-gray-400 hover:text-gray-600">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                            <div class="aspect-square bg-gray-100 rounded-2xl overflow-hidden mb-8 border border-gray-100 shadow-inner">
                                                <img id="cropper-target" src="" class="max-w-full block">
                                            </div>
                                            <div class="grid grid-cols-2 gap-4">
                                                <button type="button" @click="showCropper = false" class="py-4 bg-gray-100 text-gray-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-200 transition-all">Batal</button>
                                                <button type="button" @click="saveCrop" class="py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all">Potong & Simpan</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-[10px] font-black uppercase text-gray-400 block mb-3">Rate this product</label>
                                        <div class="flex items-center gap-2 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                            <template x-for="i in 5">
                                                <button type="button" @click="rating = i" class="transition-all duration-200 transform hover:scale-125 focus:outline-none">
                                                    <svg class="w-8 h-8" :class="rating >= i ? 'text-yellow-400' : 'text-gray-200'" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                </button>
                                            </template>
                                            <span class="ml-auto text-xs font-black text-gray-400" x-text="rating + '/5'"></span>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-[10px] font-black uppercase text-gray-400 block mb-2">Upload Photo (Optional)</label>
                                        <div class="relative group">
                                            <input type="file" name="photo" accept="image/*" class="hidden" id="review-photo" 
                                                @change="handleFile($event)">
                                            <label for="review-photo" class="flex flex-col items-center justify-center w-full h-32 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-100 transition-all overflow-hidden relative">
                                                <template x-if="!preview">
                                                    <div class="text-center">
                                                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                        <span class="text-[10px] font-black uppercase text-gray-400">Pilih Foto</span>
                                                    </div>
                                                </template>
                                                <template x-if="preview">
                                                    <div class="relative w-full h-full group/preview">
                                                        <img :src="preview" class="w-full h-full object-cover">
                                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/preview:opacity-100 transition-opacity flex items-center justify-center">
                                                            <button type="button" @click="preview = null; document.getElementById('cropped_data').value = ''; document.getElementById('review-photo').value = ''" 
                                                                class="p-2 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-all transform hover:scale-110">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </template>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-[10px] font-black uppercase text-gray-400 block mb-2">Message</label>
                                        <textarea name="comment" rows="4" class="w-full p-4 rounded-2xl border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 text-sm transition-all resize-none outline-none" placeholder="Tell us about the product..." required></textarea>
                                    </div>
                                    <button type="submit" class="w-full py-4 bg-gray-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg hover:bg-black transition-all transform hover:-translate-y-1">
                                        Submit Review
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="bg-indigo-600 p-8 rounded-[2.5rem] text-center text-white shadow-xl shadow-indigo-200">
                                <span class="text-4xl mb-2 block">🔒</span>
                                <p class="text-sm font-black uppercase tracking-widest mb-4">Login required</p>
                                <p class="text-indigo-200 text-xs mb-6 leading-relaxed">Please sign in to share your experience with this product.</p>
                                <a href="{{ route('login') }}" class="inline-block w-full py-4 bg-white text-indigo-600 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-50 transition-all">Login Now</a>
                            </div>
                        @endauth
                    </div>

                    {{-- List Review (Kanan) --}}
                    <div class="md:col-span-2 space-y-8">
                        @forelse($itemShop->reviews()->latest()->get() as $review)
                            <div class="bg-white border-b border-gray-100 pb-8 last:border-0"
                                 x-show="shouldShow({{ $review->rating }}, {{ $review->photo ? 'true' : 'false' }})"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0">
                                <div class="flex items-center gap-1 text-xs mb-3">
                                    <div class="flex text-yellow-400">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        @endfor
                                    </div>
                                    <span class="text-gray-400 ml-2 font-medium">{{ $review->created_at->diffForHumans() }}</span>
                                </div>

                                <div class="flex items-center gap-3 mb-4">
                                    <img src="{{ $review->user->avatar ? asset($review->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) . '&background=random' }}"
                                         class="w-10 h-10 rounded-full object-cover">
                                    <span class="font-bold text-gray-900 text-sm tracking-tight">{{ $review->user->name }}</span>
                                </div>

                                <div class="pl-0 space-y-4">
                                    <p class="text-gray-700 leading-relaxed text-sm">{{ $review->comment }}</p>
                                    
                                    @if($review->photo)
                                        <div class="flex gap-2">
                                            <img src="{{ asset($review->photo) }}" 
                                                class="w-24 h-24 object-cover rounded-xl shadow-sm border border-gray-100 cursor-pointer hover:opacity-90 transition-opacity"
                                                onclick="Swal.fire({imageUrl: '{{ asset($review->photo) }}', showConfirmButton: false, customClass: {popup: 'rounded-3xl'}})">
                                        </div>
                                    @endif

                                    <div class="flex items-center gap-6 pt-2">
                                        <button class="flex items-center gap-2 text-gray-400 hover:text-emerald-500 transition-colors group">
                                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.704a2 2 0 011.94 1.515l.604 2.417a2 2 0 01-1.94 2.485H14.41l.592 2.366a1.117 1.117 0 01-1.45 1.342L10 17.586V10h4zM10 10V4.586a1 1 0 00-1.707-.707l-2.586 2.586a1 1 0 000 1.414l2.586 2.586A1 1 0 0010 9.414V10z"/></svg>
                                            <span class="text-xs font-bold">Membantu</span>
                                        </button>
                                        <button class="text-gray-300 hover:text-gray-500 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-gray-50 py-24 rounded-[3rem] border-2 border-dashed border-gray-200 text-center">
                                <p class="text-gray-400 font-black uppercase tracking-widest text-[10px]">Belum ada ulasan.</p>
                                <p class="text-gray-300 text-xs mt-1">Jadilah yang pertama mengulas produk ini!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('components.cart-modal')
</x-app-layout>