<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
        <style>
            .cropper-view-box, .cropper-face { border-radius: 2rem; }
        </style>
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    @endpush

    <x-slot name="header">
        <div class="flex items-center justify-between" data-aos="fade-down">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="p-2 bg-white rounded-xl shadow-sm hover:shadow-md transition-all">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <h2 class="font-black text-xl text-gray-900 leading-tight uppercase tracking-tighter">
                    Product <span class="text-indigo-600">Detail</span>
                </h2>
            </div>
            
            {{-- Status Stok Individual --}}
            @if($itemShop->stok > 10)
                <div class="px-4 py-2 bg-green-100 text-green-700 rounded-xl font-black text-[10px] uppercase tracking-widest flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    Ready Stock
                </div>
            @elseif($itemShop->stok > 0)
                <div class="px-4 py-2 bg-orange-100 text-orange-700 rounded-xl font-black text-[10px] uppercase tracking-widest flex items-center gap-2">
                    <span class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></span>
                    Low Stock: {{ $itemShop->stok }} Left
                </div>
            @else
                <div class="px-4 py-2 bg-red-100 text-red-700 rounded-xl font-black text-[10px] uppercase tracking-widest flex items-center gap-2">
                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                    Out of Stock
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid lg:grid-cols-12 gap-12 items-start">
                
                {{-- LEFT COLUMN: IMAGES --}}
                <div class="lg:col-span-7 space-y-6 lg:sticky lg:top-24" data-aos="fade-right">
                    <div class="relative group rounded-[2.5rem] lg:rounded-[3rem] overflow-hidden shadow-2xl shadow-indigo-100 border-4 border-white bg-white">
                        <img src="{{ $itemShop->gambar ? asset($itemShop->gambar) : 'https://via.placeholder.com/800' }}" 
                             class="w-full h-[400px] lg:h-[600px] object-cover transition-transform duration-700 group-hover:scale-105">
                        
                        {{-- Floating Badges --}}
                        <div class="absolute top-6 left-6 flex flex-col gap-2">
                            <span class="px-4 py-2 bg-black/30 backdrop-blur-md text-white rounded-xl text-[10px] font-black uppercase tracking-widest border border-white/20">
                                {{ $itemShop->kategori ?? 'General' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: INFO & ACTIONS --}}
                <div class="lg:col-span-5 space-y-8" data-aos="fade-left">
                    
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-gray-100 border border-gray-100">
                        <div class="flex items-center gap-2 mb-4">
                            <img src="{{ $itemShop->user->avatar ? asset($itemShop->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($itemShop->user->name ?? 'Admin').'&background=random' }}" 
                                 class="w-8 h-8 rounded-full border border-gray-100">
                            <span class="text-xs font-bold text-gray-500">Sold by <span class="text-gray-900 font-extrabold">{{ $itemShop->user->name ?? 'Official Store' }}</span></span>
                        </div>

                        <h1 class="text-4xl lg:text-5xl font-black text-gray-900 leading-[1.1] tracking-tighter mb-4">
                            {{ $itemShop->nama_barang }}
                        </h1>

                        <div class="flex items-center gap-4 mb-8">
                             <div class="flex items-center gap-0.5">
                                @php $avg = $itemShop->reviews()->avg('rating') ?? 0; @endphp
                                @for($i=1; $i<=5; $i++)
                                    @if($i <= round($avg))
                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    @endif
                                @endfor
                                <span class="ml-2 text-sm font-black text-gray-900">{{ number_format($avg, 1) }}</span>
                            </div>
                            <span class="text-xs font-bold text-gray-400">({{ $itemShop->reviews()->count() }} Reviews)</span>
                        </div>

                        <div class="p-6 bg-gray-900 rounded-[2rem] text-white flex items-center justify-between mb-8 shadow-lg shadow-gray-300">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Current Price</p>
                                <div class="text-3xl font-black tracking-tight">
                                    <span class="text-lg text-gray-400 font-medium">Rp</span>{{ number_format($itemShop->harga, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            </div>
                        </div>

                        <div class="prose prose-sm text-gray-500 mb-8">
                            <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-2">Description</h3>
                            <p class="leading-relaxed">{{ $itemShop->deskripsi }}</p>
                        </div>

                    <div x-data="{
                        cart: JSON.parse(localStorage.getItem('minie_cart') || '[]'),
                        saveCart() { localStorage.setItem('minie_cart', JSON.stringify(this.cart)); },
                        item: {
                            id: {{ $itemShop->id }},
                            nama_barang: '{{ $itemShop->nama_barang }}',
                            harga: {{ $itemShop->harga }},
                            berat: {{ $itemShop->berat ?? 1000 }},
                            seller_lat: {{ $itemShop->user->latitude ?? 'null' }},
                            seller_lng: {{ $itemShop->user->longitude ?? 'null' }},
                            gambar: '{{ asset($itemShop->gambar) }}',
                            stok: {{ $itemShop->stok }}
                        },
                        
                        addToCart() {
                            if (this.item.stok <= 0) {
                                Swal.fire('Stok Habis', 'Maaf bolo, stok sudah habis!', 'warning');
                                return;
                            }
                            const exists = this.cart.find(c => c.id === this.item.id);
                            if (exists) {
                                Swal.fire('Sudah Ada', 'Barang sudah di keranjang bolo!', 'info');
                                return;
                            }
                            this.cart.push({ ...this.item, quantity: 1, selected: true });
                            this.saveCart();
                            $dispatch('notify', 'Masuk keranjang!');
                            window.dispatchEvent(new CustomEvent('cart-updated'));
                        },

                        checkoutNow() {
                            if (this.item.stok <= 0) return;
                            const exists = this.cart.find(c => c.id === this.item.id);
                            if (!exists) {
                                this.cart.push({ ...this.item, quantity: 1, selected: true });
                                this.saveCart();
                            } else {
                                exists.selected = true;
                                this.saveCart();
                            }
                            const toCheckout = this.cart.filter(i => i.id === this.item.id);
                            localStorage.setItem('checkout_items', JSON.stringify(toCheckout));
                            window.location.href = '{{ route('checkout.index') }}';
                        }
                    }">
                        @auth
                            <div class="grid grid-cols-4 gap-4">
                                <button @click="addToCart" class="col-span-2 py-5 bg-white border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-50 rounded-2xl font-black uppercase text-xs tracking-widest transition-all active:scale-95">
                                    + Keranjang
                                </button>
                                <button @click="checkoutNow" class="col-span-2 py-5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black uppercase text-xs tracking-widest transition-all shadow-xl shadow-indigo-200 active:scale-95">
                                    Beli Sekarang
                                </button>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="block w-full py-5 bg-gray-900 text-white text-center rounded-2xl font-black uppercase text-xs tracking-widest hover:shadow-xl transition-all">
                                Login to Buy
                            </a>
                        @endauth
                    </div>
                    </div>

                    {{-- REVIEWS PREVIEW --}}
                    <div class="bg-white rounded-[2.5rem] p-8 relative overflow-hidden shadow-xl border border-gray-100">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 rounded-full blur-3xl opacity-50 -mr-16 -mt-16"></div>
                        
                        <div class="relative z-10 flex items-center justify-between mb-8">
                            <h3 class="font-black text-xl text-gray-900 uppercase tracking-tighter">Latest <br>Reviews</h3>
                            <span class="text-xs font-bold text-gray-400">Wait for updates</span>
                        </div>

                        <div class="space-y-4">
                            @forelse($itemShop->reviews()->latest()->take(2)->get() as $review)
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 hover:bg-white hover:shadow-md transition-all">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-bold text-xs text-gray-900">{{ $review->user->name }}</span>
                                        <div class="flex items-center gap-0.5 mt-1">
                                            @for($i=1; $i<=5; $i++)
                                                @if($i <= $review->rating)
                                                    <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                @else
                                                    <svg class="w-3 h-3 text-gray-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    @if($review->photo)
                                        <img src="{{ asset($review->photo) }}" class="w-full h-20 object-cover rounded-xl mb-2 opacity-80 hover:opacity-100 transition-opacity">
                                    @endif
                                    <p class="text-xs text-gray-500 line-clamp-2">"{{ $review->comment }}"</p>
                                </div>
                            @empty
                                <div class="text-center py-6 text-gray-400 text-xs font-bold uppercase tracking-widest">No reviews yet</div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

            {{-- FULL REVIEW SECTION --}}
            <div class="mt-24 space-y-12" data-aos="fade-up">
                <div class="flex items-center gap-4 px-2">
                    <div class="h-12 w-1.5 bg-indigo-600 rounded-full"></div>
                    <div>
                        <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter leading-none">Customer <span class="text-indigo-600">Reviews</span></h2>
                        <p class="text-xs font-bold text-gray-400 mt-1 uppercase tracking-widest">What people say about this product</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-12 items-start">
                    {{-- Form Review (Kiri) --}}
                    <div class="md:col-span-1">
                        @auth
                            <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-white sticky top-24">
                                <h4 class="text-sm font-black uppercase tracking-widest mb-6">Write a Review</h4>
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
                    <div class="md:col-span-2 space-y-6">
                        @forelse($itemShop->reviews()->latest()->get() as $review)
                            <div class="group bg-white p-8 rounded-[2.5rem] border border-white shadow-lg shadow-gray-100 hover:shadow-xl transition-all duration-300">
                                <div class="flex items-start gap-4">
                                    <img src="{{ $review->user->avatar ? asset($review->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) . '&background=random' }}"
                                         class="w-12 h-12 rounded-2xl object-cover shadow-md group-hover:scale-110 transition-transform">
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <h5 class="font-black text-gray-900 uppercase text-xs tracking-widest">{{ $review->user->name }}</h5>
                                                <div class="flex items-center gap-0.5 mt-1">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                        @else
                                                            <svg class="w-3.5 h-3.5 text-gray-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-500 leading-relaxed text-sm mt-3 pl-4 border-l-2 border-indigo-100">"{{ $review->comment }}"</p>
                                        
                                        @if($review->photo)
                                            <div class="mt-4 pl-4">
                                                <img src="{{ asset($review->photo) }}" 
                                                    class="w-32 h-32 object-cover rounded-2xl shadow-md border-4 border-white cursor-pointer hover:scale-105 transition-all"
                                                    onclick="Swal.fire({imageUrl: '{{ asset($review->photo) }}', imageAlt: 'Review Photo', showConfirmButton: false, customClass: {popup: 'rounded-[2rem]'}})">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-gray-50 py-24 rounded-[3rem] border-2 border-dashed border-gray-200 text-center">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                </div>
                                <p class="text-gray-400 font-black uppercase tracking-widest text-[10px]">No reviews yet.</p>
                                <p class="text-gray-300 text-xs mt-1">Be the first to review this product!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
</x-app-layout>