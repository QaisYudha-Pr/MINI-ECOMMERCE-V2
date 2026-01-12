<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3" data-aos="fade-right">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M16 11V7a4 4 0 118 0m-4 5v2a3 3 0 01-3 3h-6a3 3 0 01-3-3V7a3 3 0 013-3h6a3 3 0 013 3V7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h2 class="font-black text-xl text-gray-800 leading-tight uppercase tracking-widest">
                Detail <span class="text-indigo-600">Produk</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f8fafc]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            {{-- MAIN SECTION: PRODUCT INFO --}}
            <div class="bg-white/70 backdrop-blur-md overflow-hidden shadow-2xl shadow-gray-200/50 rounded-[3rem] p-6 md:p-12 border border-white" data-aos="fade-up">
                <div class="grid md:grid-cols-2 gap-16 items-center">

                    {{-- KIRI: GAMBAR PRODUK --}}
                    <div class="relative group">
                        <div class="absolute -inset-6 bg-gradient-to-tr from-indigo-50 to-purple-50 rounded-[4rem] opacity-50 blur-2xl -z-10"></div>
                        <div class="relative overflow-hidden rounded-[2.5rem] shadow-2xl border-[12px] border-white group-hover:border-indigo-50 transition-all duration-500">
                            <img src="{{ $itemShop->gambar ? asset($itemShop->gambar) : 'https://via.placeholder.com/800' }}"
                                 alt="{{ $itemShop->nama_barang }}"
                                 class="w-full h-[550px] object-cover transform group-hover:scale-105 transition-transform duration-1000">
                            
                            <div class="absolute top-6 left-6">
                                <div class="bg-white/80 backdrop-blur-md px-5 py-2.5 rounded-2xl shadow-xl border border-white/50 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-indigo-600 rounded-full animate-pulse"></span>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-800">Premium Item</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- KANAN: DETAIL INFO --}}
                    <div class="flex flex-col">
                        <nav class="flex items-center gap-3 mb-8">
                            <a href="{{ route('home') }}" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-indigo-600">Store</a>
                            <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="3"/></svg>
                            <span class="text-[10px] font-black uppercase tracking-widest text-indigo-600">{{ $itemShop->kategori ?? 'Product' }}</span>
                        </nav>

                        <div class="mb-6">
                            <h1 class="text-5xl font-black text-gray-900 leading-tight tracking-tighter mb-4">
                                {{ $itemShop->nama_barang }}
                            </h1>
                            
                            {{-- Quick Rating Summary --}}
                            <div class="flex items-center gap-4">
                                <div class="flex text-yellow-400">
                                    @php $avg = $itemShop->reviews()->avg('rating') ?? 0; @endphp
                                    @for($i=1; $i<=5; $i++)
                                        <span class="text-xl">{{ $i <= round($avg) ? '★' : '☆' }}</span>
                                    @endfor
                                </div>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">({{ $itemShop->reviews()->count() }} Reviews)</span>
                            </div>
                        </div>

                        <div class="mb-10 p-6 bg-indigo-50/50 rounded-[2rem] border border-indigo-100 inline-block self-start">
                            <div class="text-[10px] font-black uppercase tracking-widest text-indigo-400 mb-1">Price</div>
                            <div class="text-4xl font-black text-indigo-600 flex items-start gap-1">
                                <span class="text-lg mt-1">Rp</span>{{ number_format($itemShop->harga, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="space-y-4 mb-12">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-[2px] bg-indigo-600 rounded-full"></div>
                                <h3 class="text-xs font-black uppercase tracking-widest text-gray-900">Description</h3>
                            </div>
                            <p class="text-gray-500 leading-relaxed font-medium">
                                {{ $itemShop->deskripsi }}
                            </p>
                        </div>

                        {{-- Tombol Beli --}}
                        <div class="mt-auto">
                            @auth
                                <div class="flex gap-5">
                                    <button class="flex-[3] px-10 py-6 bg-indigo-600 text-white rounded-3xl font-black uppercase tracking-widest text-xs shadow-2xl shadow-indigo-200 hover:shadow-indigo-400 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3">
                                        Checkout Now
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                    <button class="flex-1 px-8 py-6 bg-white text-gray-400 border-2 border-gray-50 rounded-3xl hover:text-red-500 hover:bg-red-50 transition-all">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    </button>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="block w-full text-center px-10 py-6 bg-gray-900 text-white rounded-3xl font-black uppercase tracking-widest text-xs hover:bg-gray-800 transition-all">
                                    Sign In to Purchase
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            {{-- REVIEW SECTION: FULL WIDTH --}}
            <div class="space-y-8" data-aos="fade-up">
                <div class="flex items-center gap-4 px-6">
                    <div class="h-12 w-1.5 bg-indigo-600 rounded-full"></div>
                    <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Customer <span class="text-indigo-600">Reviews</span></h2>
                </div>

                <div class="grid md:grid-cols-3 gap-12 items-start">
                    {{-- Form Review (Kiri) --}}
                    <div class="md:col-span-1">
                        @auth
                            <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-100 sticky top-24">
                                <h4 class="text-sm font-black uppercase tracking-widest mb-6">Write a Review</h4>
                                <form action="{{ route('reviews.store', $itemShop->id) }}" method="POST" class="space-y-5">
                                    @csrf
                                    <div>
                                        <label class="text-[10px] font-black uppercase text-gray-400 block mb-2">Stars</label>
                                        <select name="rating" class="w-full rounded-2xl border-gray-100 focus:ring-indigo-500 font-bold">
                                            <option value="5">⭐⭐⭐⭐⭐ 5/5</option>
                                            <option value="4">⭐⭐⭐⭐ 4/5</option>
                                            <option value="3">⭐⭐⭐ 3/5</option>
                                            <option value="2">⭐⭐ 2/5</option>
                                            <option value="1">⭐ 1/5</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-black uppercase text-gray-400 block mb-2">Message</label>
                                        <textarea name="comment" rows="4" class="w-full rounded-2xl border-gray-100 focus:ring-indigo-500 text-sm" placeholder="Tell us about the product..."></textarea>
                                    </div>
                                    <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all">
                                        Submit Review
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="bg-indigo-900 p-8 rounded-[2.5rem] text-center text-white">
                                <p class="text-sm font-medium mb-4">Want to share your experience?</p>
                                <a href="{{ route('login') }}" class="inline-block px-6 py-3 bg-white text-indigo-900 rounded-xl font-black text-[10px] uppercase tracking-widest">Login First</a>
                            </div>
                        @endauth
                    </div>

                    {{-- List Review (Kanan) --}}
                    <div class="md:col-span-2 space-y-6">
                        @forelse($itemShop->reviews()->latest()->get() as $review)
                            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm flex gap-6 hover:shadow-md transition-shadow">
                                <img src="{{ $review->user->avatar ? asset($review->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) . '&background=4f46e5&color=fff' }}"
                                     class="w-14 h-14 rounded-2xl object-cover shadow-lg">
                                <div class="flex-1">
                                    <div class="flex justify-between items-center mb-2">
                                        <h5 class="font-black text-gray-900 uppercase text-xs tracking-widest">{{ $review->user->name }}</h5>
                                        <span class="text-[10px] font-bold text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="flex text-yellow-400 text-xs mb-3">
                                        @for ($i = 1; $i <= 5; $i++)
                                            {{ $i <= $review->rating ? '★' : '☆' }}
                                        @endfor
                                    </div>
                                    <p class="text-gray-500 italic leading-relaxed text-sm">"{{ $review->comment }}"</p>
                                </div>
                            </div>
                        @empty
                            <div class="bg-gray-50 py-20 rounded-[3rem] border-2 border-dashed border-gray-200 text-center">
                                <span class="text-4xl mb-4 block">💬</span>
                                <p class="text-gray-400 font-black uppercase tracking-widest text-[10px]">No reviews yet bolo.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>