<div x-data x-show="$store.cart.show" 
    x-effect="document.body.style.overflow = $store.cart.show ? 'hidden' : ''"
    @open-cart.window="$store.cart.show = true"
    class="fixed inset-0 z-[100] overflow-hidden" x-cloak>

    {{-- Overlay --}}
    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-[2px]" x-show="$store.cart.show"
        x-transition:enter="transition ease-out duration-300" 
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" 
        x-transition:leave="transition ease-in duration-200" 
        @click="$store.cart.show = false"></div>

    {{-- Sliding Panel --}}
    <div class="fixed inset-y-0 right-0 max-w-full flex">
        <div class="w-screen max-w-md" x-show="$store.cart.show"
            x-transition:enter="transform transition ease-in-out duration-300" 
            x-transition:enter-start="translate-x-full" 
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300" 
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full">

            <div class="h-full flex flex-col bg-white shadow-lg overflow-hidden sm:rounded-l-[2.5rem]">
                {{-- Header --}}
                <div class="px-6 py-6 sm:px-8 sm:py-8 flex items-center justify-between border-b border-gray-100 bg-white sticky top-0 z-10">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Keranjang <span class="text-emerald-500">Bolo</span></h2>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <p class="text-xs font-bold text-gray-400" x-text="$store.cart.items.length + ' Items Ready'"></p>
                        </div>
                    </div>
                    <button @click="$store.cart.show = false" class="group p-2 hover:bg-gray-100 rounded-xl transition-all duration-300">
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-gray-900 group-hover:rotate-90 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>

                {{-- List Items --}}
                <div class="flex-1 overflow-y-auto px-4 sm:px-6 py-4 space-y-4 no-scrollbar bg-gray-50/30">
                    {{-- Select All Section --}}
                    <template x-if="$store.cart.items.length > 0">
                        <div class="flex items-center justify-between bg-white p-4 rounded-2xl border border-gray-100 shadow-sm mb-2">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative flex items-center">
                                    <input type="checkbox"
                                        :checked="$store.cart.items.length > 0 && $store.cart.items.every(i => i.selected)"
                                        @change="$store.cart.toggleAll(!($store.cart.items.every(i => i.selected)))"
                                        class="peer w-5 h-5 rounded-lg border-gray-300 text-emerald-500 focus:ring-emerald-500 transition-all cursor-pointer">
                                </div>
                                <span class="text-xs font-bold text-gray-700 group-hover:text-emerald-600 transition-colors">Pilih Semua</span>
                            </label>
                            <div class="flex items-center gap-3">
                                <button @click="if(confirm('Kosongkan semua isi keranjang bolo?')) { $store.cart.items = []; $store.cart.save(); }" 
                                    class="text-[10px] font-bold text-rose-400 hover:text-rose-600 transition-colors uppercase tracking-tight">
                                    Hapus Semua
                                </button>
                                <span class="text-xs font-bold text-gray-400 bg-gray-50 px-2 py-1 rounded-md" x-text="$store.cart.selectedCount + ' Terpilih'"></span>
                            </div>
                        </div>
                    </template>

                    {{-- Loop Item Keranjang --}}
                    <div class="space-y-3">
                        <template x-for="(item, index) in $store.cart.items" :key="item.id">
                            <div class="group relative flex items-center gap-4 bg-white p-3 sm:p-4 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-emerald-100 transition-all font-sans"
                                 :class="{'opacity-60': !item.selected}">
                                
                                <input type="checkbox" x-model="item.selected"
                                    @change="$store.cart.save()"
                                    class="w-5 h-5 rounded-lg border-gray-300 text-emerald-500 focus:ring-emerald-500 cursor-pointer">

                                <div class="relative w-16 h-16 sm:w-20 sm:h-20 shrink-0">
                                    <img :src="item.gambar" class="w-full h-full rounded-xl object-cover shadow-sm" loading="lazy">
                                    <div class="absolute -top-1 -right-1 bg-white p-1 rounded-lg shadow-sm border border-gray-50" x-show="item.quantity > 1">
                                        <span class="text-[11px] font-semibold text-emerald-600" x-text="'x' + item.quantity"></span>
                                    </div>
                                </div>

                                <div class="flex-grow min-w-0">
                                    <h4 class="font-bold text-gray-900 text-xs sm:text-sm truncate hover:text-emerald-600 cursor-default" x-text="item.nama_barang"></h4>
                                    <div class="flex items-center gap-1 mt-0.5">
                                        <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" /></svg>
                                        <span class="text-xs font-bold text-gray-400" x-text="item.seller_name || 'Official Store'"></span>
                                    </div>
                                    <p class="text-emerald-600 font-bold text-sm mt-2 font-mono"
                                       x-text="'Rp' + new Intl.NumberFormat('id-ID').format(item.harga)"></p>
                                    
                                    {{-- Mini Qty Controller --}}
                                    <div class="flex items-center gap-3 mt-2">
                                        <button @click="if(item.quantity > 1) { item.quantity--; $store.cart.save() }" 
                                                class="w-6 h-6 flex items-center justify-center bg-gray-50 rounded-lg text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 12H4" stroke-width="3" stroke-linecap="round"/></svg>
                                        </button>
                                        <span class="text-xs font-bold text-gray-800 w-4 text-center" x-text="item.quantity"></span>
                                        <button @click="if(item.quantity < (item.stok || 99)) { item.quantity++; $store.cart.save() }"
                                                class="w-6 h-6 flex items-center justify-center bg-gray-50 rounded-lg text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <button @click="$store.cart.remove(index)"
                                    class="p-2 text-gray-300 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all group-hover:opacity-100 sm:opacity-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    {{-- Empty State --}}
                    <template x-if="$store.cart.items.length === 0">
                        <div class="h-full flex flex-col items-center justify-center text-center py-12 px-8">
                            <div class="relative mb-6">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="absolute -bottom-2 -right-2 bg-white p-2 rounded-xl shadow-sm border border-gray-50 rotate-12">
                                    <span class="text-xl">🛒</span>
                                </div>
                            </div>
                            <h3 class="text-sm font-bold text-gray-900 uppercase">Keranjang Kosong</h3>
                            <p class="text-[11px] text-gray-400 font-bold mt-2 leading-relaxed">Wah belum ada belanjaan nih bolo. Yuk cari barang keren atau cek produk hot di bawah!</p>
                            
                            {{-- Trending Sneak Peek inside Empty Cart --}}
                            @php 
                                $trending = \App\Models\ItemShop::orderBy('total_terjual', 'desc')->take(2)->get(); 
                            @endphp
                            @if($trending->count() > 0)
                            <div class="mt-10 w-full space-y-3">
                                <p class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em] mb-4">Lagi Hot Banget</p>
                                @foreach($trending as $hot)
                                <div class="flex items-center gap-4 p-3 bg-white rounded-2xl border border-slate-100 shadow-sm text-left group hover:border-emerald-300 transition-all cursor-pointer"
                                     @click='$store.cart.add({!! json_encode(["id" => $hot->id, "nama_barang" => $hot->nama_barang, "harga" => $hot->harga, "gambar" => asset($hot->gambar), "seller_name" => $hot->user->name ?? "Official Store"]) !!}); $store.cart.show = true;'>
                                    <img src="{{ asset($hot->gambar) }}" class="w-10 h-10 rounded-xl object-cover shrink-0">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[10px] font-bold text-slate-800 truncate mb-0.5 uppercase tracking-tighter">{{ $hot->nama_barang }}</p>
                                        <p class="text-[10px] text-emerald-600 font-bold">Rp {{ number_format($hot->harga, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            <a href="{{ route('shop.public') }}" @click="$store.cart.show = false" class="mt-8 px-10 py-4 bg-gray-900 text-white rounded-2xl font-bold text-[10px] uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-100 flex items-center justify-center gap-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Mulai Belanja
                            </a>
                        </div>
                    </template>
                </div>

                {{-- Footer --}}
                <div class="shrink-0 p-6 sm:p-8 bg-white border-t border-gray-100 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.05)] sticky bottom-0">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <span class="text-xs font-bold text-gray-400">Estimasi Total</span>
                            <div class="text-2xl font-bold text-gray-900 mt-1 flex items-baseline gap-1">
                                <span class="text-xs text-emerald-500">Rp</span><span x-text="new Intl.NumberFormat('id-ID').format($store.cart.total)"></span>
                            </div>
                        </div>
                        <div class="text-right">
                             <span class="text-xs font-bold text-gray-400" x-text="$store.cart.selectedCount + ' Barang'"></span>
                        </div>
                    </div>

                    {{-- Voucher Entry --}}
                    <div class="mb-6 p-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200 group focus-within:border-emerald-500 transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-white rounded-xl shadow-sm border border-slate-100 flex items-center justify-center text-emerald-600 group-hover:rotate-12 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                            </div>
                            <input type="text" placeholder="Punya kode voucher bolo?" class="flex-1 bg-transparent border-none text-xs font-bold placeholder:text-slate-400 focus:ring-0 p-0">
                            <button class="text-[10px] font-bold text-emerald-600 uppercase hover:text-emerald-700">Pakai</button>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex gap-3">
                            <button @click="$store.cart.items = $store.cart.items.filter(i => !i.selected); $store.cart.save()"
                                :disabled="$store.cart.selectedCount === 0"
                                class="flex-1 py-3 bg-rose-50 text-rose-500 rounded-2xl font-semibold uppercase text-xs tracking-widest disabled:opacity-30 disabled:grayscale hover:bg-rose-100 transition-all border border-rose-100 flex items-center justify-center gap-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                Hapus
                            </button>

                            <button @click="$store.cart.checkout(true)"
                                :disabled="$store.cart.selectedCount === 0"
                                class="flex-[2] py-3 bg-emerald-500 text-white rounded-2xl font-semibold uppercase text-xs tracking-widest disabled:opacity-50 hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-100 flex items-center justify-center gap-2">
                                Checkout Terpilih
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" /></svg>
                            </button>
                        </div>
                        
                        <button @click="$store.cart.checkout(false)" 
                            :disabled="$store.cart.items.length === 0"
                            class="w-full bg-gray-900 text-white py-4 rounded-2xl font-semibold uppercase text-xs tracking-[0.2em] hover:bg-black transition-all disabled:opacity-30 flex items-center justify-center gap-2 group">
                            Checkout Semua
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

