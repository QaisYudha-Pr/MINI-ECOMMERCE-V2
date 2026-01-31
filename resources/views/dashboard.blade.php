<x-admin-layout>
    <div class="space-y-6">
{{-- Hero Welcome Section --}}
        <div class="relative bg-slate-900 overflow-hidden shadow-2xl sm:rounded-[3rem] p-10 group transition-all duration-500 hover:shadow-indigo-500/10">
             <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-10">
                <div class="max-w-xl">
                    <span class="inline-block px-4 py-1.5 rounded-full bg-indigo-500/20 backdrop-blur-md text-indigo-400 text-[10px] font-black uppercase tracking-[0.2em] mb-4 border border-indigo-500/20">
                        Pusat Komando Bolo
                    </span>
                    <h1 class="text-4xl lg:text-5xl font-black text-white tracking-tighter leading-[1.1] mb-6">
                        Halo @if(Auth::user()->hasRole('seller')) Juragan @else Bolo @endif, <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-indigo-200">{{ Auth::user()->name }}!</span> 🚀
                    </h1>
                    <p class="text-slate-400 text-sm font-medium leading-relaxed mb-8">
                        @if(Auth::user()->hasRole('seller')) 
                            Siap pantau perkembangan tokomu hari ini? Mari kita lihat performa penjualanmu.
                        @else 
                            Senang melihatmu kembali! Cek keranjangmu atau lihat apa yang sedang trending di koleksi kami hari ini.
                        @endif
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('shop.public') }}" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-white hover:text-slate-900 transition-all shadow-xl shadow-indigo-500/20 active:scale-95">
                            @if(Auth::user()->hasRole('seller')) Kelola Produk @else Lanjut Belanja @endif
                        </a>
                        @if(Auth::user()->hasRole('seller'))
                        <a href="{{ route('item-shop.create') }}" class="px-8 py-4 bg-white/5 text-white border border-white/10 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-white/10 transition-all active:scale-95">
                            Tambah Produk
                        </a>
                        @endif
                    </div>
                </div>

                {{-- Quick Stats Overlay for Hero --}}
                <div class="grid grid-cols-2 gap-4 w-full lg:w-80">
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-6 rounded-3xl text-center group/card transition-all hover:bg-white/10">
                        <div class="text-3xl font-black text-white mb-1">{{ $userTransactionsCount ?? 0 }}</div>
                        <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Pesanan</div>
                    </div>
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-6 rounded-3xl text-center group/card transition-all hover:bg-white/10">
                        <div class="text-3xl font-black text-white mb-1">{{ $userFavoritesCount ?? 0 }}</div>
                        <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Favorit</div>
                    </div>
                </div>
            </div>
            
            {{-- Abstract Background Graphics --}}
            <div class="absolute right-[-10%] top-[-10%] w-96 h-96 bg-indigo-600/20 rounded-full blur-[120px] group-hover:bg-indigo-600/30 transition-all duration-1000"></div>
            <div class="absolute left-[20%] bottom-[-20%] w-72 h-72 bg-indigo-400/10 rounded-full blur-[100px]"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @if(!Auth::user()->hasRole('admin'))
                {{-- User Specific: Favorites Summary --}}
                @php $favCount = Auth::user()->favoriteItems()->count(); @endphp
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 flex flex-col justify-between group hover:shadow-2xl hover:border-slate-200 transition-all duration-300 hover:-translate-y-1">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-xl font-black text-slate-900 tracking-tight">Daftar Suka</h4>
                            <div class="w-10 h-10 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500 group-hover:bg-slate-900 group-hover:text-white transition-all duration-500">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </div>
                        </div>
                        <div class="py-6 text-center">
                            @if($favCount > 0)
                                <div class="text-4xl font-black text-slate-900 mb-1 tracking-tighter">{{ $favCount }}</div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Produk Disimpan</p>
                            @else
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-relaxed opacity-60">Belum ada barang<br>favorit bolo.</p>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('wishlist.index') }}" class="w-full py-4 bg-rose-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-slate-900 transition-all text-center">
                        Lihat Suka
                    </a>
                </div>

                {{-- User Specific: Cart Summary --}}
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 flex flex-col justify-between group hover:shadow-2xl hover:border-slate-200 transition-all duration-300 hover:-translate-y-1" x-data>
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-xl font-black text-slate-900 tracking-tight">Keranjang Bolo</h4>
                            <div class="w-10 h-10 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:bg-slate-900 group-hover:text-white transition-all duration-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                        </div>
                        
                        <div class="space-y-4 max-h-[180px] overflow-y-auto no-scrollbar mb-6 text-left">
                            <template x-if="$store.cart.items.length === 0">
                                <div class="text-center py-6">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-relaxed opacity-60">Keranjang kosong,<br>yuk jajan!</p>
                                </div>
                            </template>
                            
                            <template x-for="item in $store.cart.items" :key="item.id">
                                <div class="flex items-center gap-4 p-3 bg-slate-50/50 rounded-2xl border border-slate-100/50">
                                    <img :src="item.gambar" class="w-10 h-10 rounded-xl object-cover">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[11px] font-black text-slate-800 truncate" x-text="item.nama_barang"></p>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest" x-text="`QTY: ${item.quantity}`"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[11px] font-black text-slate-900" x-text="new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(item.harga * item.quantity)"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" 
                        x-show="$store.cart.items.length > 0"
                        class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-slate-900 transition-all text-center block shadow-lg shadow-indigo-100">
                        Lanjut Checkout
                    </a>
                    <div x-show="$store.cart.items.length === 0" class="w-full py-4 bg-slate-100 text-slate-400 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] text-center cursor-not-allowed">
                        Kosong Bolo
                    </div>
                </div>
            @endif

            {{-- Pure User: Package Tracking (NEW) --}}
            @if(!Auth::user()->hasRole('seller') && !Auth::user()->hasRole('admin'))
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 flex flex-col justify-between group hover:shadow-2xl hover:border-slate-200 transition-all duration-300 hover:-translate-y-1">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-xl font-black text-slate-900 tracking-tight">Status Paket</h4>
                        <div class="w-10 h-10 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 group-hover:bg-slate-900 group-hover:text-white transition-all duration-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                        </div>
                    </div>
                    <div class="py-10 text-center">
                        @php $trackingCount = Auth::user()->transactions()->whereIn('status', ['paid', 'shipped', 'on_process'])->count(); @endphp
                        @if($trackingCount > 0)
                            <div class="text-5xl font-black text-slate-900 mb-1 tracking-tighter">{{ $trackingCount }}</div>
                            <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest leading-none">Paket Diproses</p>
                        @else
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-relaxed opacity-60">Belum ada paket<br>berjalan bolo.</p>
                        @endif
                    </div>
                </div>
                <a href="{{ route('transactions.index') }}" class="w-full py-4 @if($trackingCount > 0) bg-emerald-500 @else bg-slate-900 @endif text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-slate-800 transition-all text-center">
                    @if($trackingCount > 0) Lacak Pesanan @else Cari Barang @endif
                </a>
            </div>
            @endif

            {{-- Metrics/Stats or Action Cards --}}
            @if(Auth::user()->hasRole('seller') || Auth::user()->hasRole('admin'))
                <div class="{{ Auth::user()->hasRole('admin') ? 'md:col-span-2' : 'md:col-span-1' }} grid grid-cols-1 {{ Auth::user()->hasRole('admin') ? 'sm:grid-cols-2 lg:grid-cols-3' : '' }} gap-4">
                     <!-- Card: Total Items -->
                    <div class="bg-white p-6 rounded-[2rem] border border-slate-100 flex items-center justify-between shadow-xl shadow-slate-200/50 group hover:shadow-2xl hover:border-slate-200 transition-all duration-300">
                        <div>
                            <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.15em] mb-1">Total Items</div>
                            <div class="text-2xl font-black text-slate-900 leading-none">{{ $totalItems }}</div>
                        </div>
                        <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:bg-slate-900 group-hover:text-white transition-all duration-500">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                    </div>

                    @if(Auth::user()->hasRole('seller'))
                    <!-- Card: Wallet Balance (Bolo Style) -->
                    <div class="bg-indigo-600 p-6 rounded-[2rem] border border-indigo-500 shadow-xl shadow-indigo-200/50 group overflow-hidden relative transition-all duration-500 hover:shadow-indigo-400/50 hover:-translate-y-1">
                        <div class="relative z-10">
                            <div class="text-indigo-100/60 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Saldo Tersedia</div>
                            <div class="text-2xl font-black text-white leading-none">RP {{ number_format(Auth::user()->balance, 0, ',', '.') }}</div>
                            <a href="{{ route('admin.withdrawals.index') }}" 
                                class="mt-4 px-4 py-2 bg-white/10 backdrop-blur-md rounded-xl text-[9px] font-black text-white uppercase tracking-widest hover:bg-white hover:text-indigo-600 inline-flex items-center gap-2 transition-all">
                                Tarik Saldo
                                <svg class="w-3 h-3 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                        <div class="absolute top-2 right-2 opacity-20 group-hover:scale-125 transition-transform duration-500">
                             <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                    </div>
                    @endif

                    <!-- Card: Store Rating -->
                    <div class="bg-white p-6 rounded-[2rem] border border-slate-100 flex items-center justify-between shadow-xl shadow-slate-200/50 group transition-all duration-300 hover:shadow-2xl hover:border-slate-200">
                        <div>
                            <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.15em] mb-1">Rating Toko</div>
                            <div class="flex items-center gap-2 mt-1">
                                <div class="text-2xl font-black text-slate-900 leading-none">{{ number_format($storeRating, 1) }}</div>
                                <div class="flex items-center text-amber-400">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:bg-slate-900 group-hover:text-white transition-all duration-500">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        </div>
                    </div>

                    <!-- Card: Total Earnings -->
                    <div class="bg-white p-6 rounded-[2rem] border border-slate-100 flex items-center justify-between shadow-xl shadow-slate-200/50 group transition-all duration-300 hover:shadow-2xl hover:border-slate-200">
                        <div>
                            <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.15em] mb-1">Total Pendapatan</div>
                            <div class="text-2xl font-black text-slate-900 leading-none">RP {{ number_format($totalEarnings, 0, ',', '.') }}</div>
                        </div>
                        <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:bg-slate-900 group-hover:text-white transition-all duration-500">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>

                    <!-- Card: Orders -->
                    <div class="bg-white p-6 rounded-[2rem] border border-slate-100 flex items-center justify-between shadow-xl shadow-slate-200/50 group transition-all duration-300 hover:shadow-2xl hover:border-slate-200">
                         <div>
                            <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.15em] mb-1">Pesanan Sukses</div>
                            <div class="text-2xl font-black text-slate-900 leading-none">{{ $totalOrdersCount }}</div>
                        </div>
                         <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:bg-slate-900 group-hover:text-white transition-all duration-500">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                    </div>
                </div>

                {{-- Quick Action: New Product Card --}}
                
                <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white flex flex-col justify-between relative overflow-hidden h-full shadow-2xl shadow-indigo-100 group transition-all duration-500 hover:shadow-indigo-200 hover:-translate-y-1">
                     <div class="relative z-10">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6 group-hover:rotate-12 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <h4 class="font-black text-2xl mb-2 tracking-tight">Barang Baru?</h4>
                        <p class="text-indigo-100 text-xs font-medium mb-8 leading-relaxed opacity-80 uppercase tracking-tight">Tambah koleksi tokomu bolo!</p>
                        
                        <a href="{{ route('item-shop.create') }}" class="inline-flex items-center justify-center px-6 py-4 bg-white text-indigo-600 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-slate-900 hover:text-white transition-all shadow-xl active:scale-95 leading-none">
                            Upload Produk
                        </a>
                     </div>
                     <div class="absolute -bottom-8 -right-8 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                </div>
            @else
                {{-- Pure User: Compact Activity Row --}}
                <div class="md:col-span-3 grid grid-cols-1 sm:grid-cols-3 gap-6 mt-4">
                    <div class="bg-indigo-50/30 backdrop-blur-sm p-6 rounded-[2rem] border border-indigo-100/50 flex items-center gap-5 group hover:bg-white hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-500">
                        <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-indigo-600 shadow-sm group-hover:bg-slate-900 group-hover:text-white transition-all duration-500">
                             <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <div>
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] mb-1">Total Pesanan</div>
                            <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $userTransactionsCount }}</div>
                        </div>
                    </div>

                    <div class="bg-rose-50/30 backdrop-blur-sm p-6 rounded-[2rem] border border-rose-100/50 flex items-center gap-5 group hover:bg-white hover:shadow-2xl hover:shadow-rose-100/50 transition-all duration-500">
                        <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-rose-500 shadow-sm group-hover:bg-slate-900 group-hover:text-white transition-all duration-500">
                            <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <div>
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] mb-1">Favorit Saya</div>
                            <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $userFavoritesCount }}</div>
                        </div>
                    </div>

                    <div class="bg-amber-50/30 backdrop-blur-sm p-6 rounded-[2rem] border border-amber-100/50 flex items-center gap-5 group hover:bg-white hover:shadow-2xl hover:shadow-amber-100/50 transition-all duration-500">
                        <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-amber-500 shadow-sm group-hover:bg-slate-900 group-hover:text-white transition-all duration-500">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        </div>
                        <div>
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] mb-1">Ulasan Bolo</div>
                            <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $userReviewsCount }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('seller'))
        {{-- Revenue Dashboard Detailed Widgets --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pb-12 items-start">
            
            {{-- Widget: Revenue Timeline --}}
            <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 flex flex-col transition-all duration-300 hover:shadow-2xl hover:border-slate-200">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="font-black text-slate-900 text-lg tracking-tight">Garis Waktu Pendapatan</h4>
                        <span class="text-[10px] font-black uppercase text-slate-400">7 Hari Terakhir</span>
                    </div>
                    @php
                        $exportRoute = route('dashboard', ['export' => 'excel']);
                    @endphp
                    <a href="{{ $exportRoute }}" class="text-[9px] font-black uppercase tracking-widest text-indigo-600 hover:text-white bg-indigo-50 hover:bg-slate-900 px-4 py-2.5 rounded-xl transition-all duration-300 flex items-center shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Export Excel
                    </a>
                </div>
                <div id="revenueChart" class="w-full flex-grow min-h-[300px]"></div>
            </div>

            {{-- Widget: Top Best Sell In Market --}}
            <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 transition-all duration-300 hover:shadow-2xl hover:border-slate-200 flex flex-col">
                 <div class="flex items-center justify-between mb-6 shrink-0">
                    <div>
                        <h4 class="font-black text-slate-900 text-lg tracking-tight">Top Best Sell</h4>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Market Performance</p>
                    </div>
                    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 rounded-lg">
                        <span class="text-[10px] font-black uppercase text-orange-600 tracking-wider">🔥 Trending</span>
                    </div>
                </div>

                {{-- Enhanced Category Filter Bar --}}
                <div class="flex gap-3 overflow-x-auto no-scrollbar mb-6 shrink-0 pb-2">
                    <button onclick="filterCategory('all')" id="btn-cat-all"
                       class="btn-category whitespace-nowrap px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] transition-all border {{ $selectedCategory == 'all' ? 'bg-slate-900 border-slate-900 text-white shadow-md' : 'bg-white border-slate-100 text-slate-400 hover:border-slate-300 hover:text-slate-600' }}">
                        All
                    </button>
                    @foreach($categories as $cat)
                        <button onclick="filterCategory('{{ $cat }}')" id="btn-cat-{{ Str::slug($cat) }}"
                           class="btn-category whitespace-nowrap px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] transition-all border {{ strtolower($selectedCategory) == strtolower($cat) ? 'bg-slate-900 border-slate-900 text-white shadow-md' : 'bg-white border-slate-100 text-slate-400 hover:border-slate-300 hover:text-slate-600' }}">
                            {{ $cat }}
                        </button>
                    @endforeach
                </div>

                <div class="space-y-4 overflow-y-auto max-h-[300px] pr-2 custom-scrollbar flex-grow" id="best-sell-list">
                    @forelse($revenueByOffer as $data)
                        <div class="group">
                            <div class="flex flex-col mb-2">
                                <div class="flex justify-between items-end">
                                    <div class="flex flex-col">
                                        @if($data['kategori'])
                                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">{{ $data['kategori'] }}</span>
                                        @endif
                                        <span class="text-sm font-bold text-slate-700 group-hover:text-slate-900 transition-colors">{{ $data['name'] }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-black text-slate-900 block">RP {{ number_format($data['total'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="w-full bg-slate-50 rounded-full h-2 mt-3 overflow-hidden border border-slate-100/50">
                                    <div class="bg-indigo-600 h-full rounded-full transition-all duration-1000" 
                                         style="width: {{ ($data['total'] / $maxRevenue) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                         <div class="py-12 flex flex-col items-center justify-center bg-slate-50/50 rounded-3xl border-2 border-dashed border-slate-100">
                             <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center text-slate-300 mb-3">
                                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                             </div>
                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Belum ada penjualan<br>di kategori ini</p>
                         </div>
                    @endforelse
                </div>
            </div>

            @if(Auth::user()->hasRole('admin'))
            {{-- Widget: Revenue by User Role --}}
            <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 transition-all duration-300 hover:shadow-2xl hover:border-slate-200">
                 <div class="flex items-center justify-between mb-6">
                    <h4 class="font-black text-slate-900 text-lg tracking-tight">Total Belanja per User</h4>
                    <span class="text-[10px] font-black uppercase text-green-500">Top Buyers</span>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($revenueByUser as $usr)
                        <div class="py-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-500">
                                    {{ substr($usr['name'], 0, 1) }}
                                </div>
                                <span class="text-xs font-bold text-slate-700">{{ $usr['name'] }}</span>
                            </div>
                            <span class="text-xs font-black text-slate-900">RP {{ number_format($usr['total'], 0, ',', '.') }}</span>
                        </div>
                    @empty
                         <div class="py-8 text-center text-slate-400 text-xs italic">Belum ada data transaksi user.</div>
                    @endforelse
                </div>
            </div>
            @endif

            {{-- Widget: Recent Notifications --}}
             <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 transition-all duration-300 hover:shadow-2xl hover:border-slate-200" 
                  x-data="{ 
                    unreadCount: {{ $notifications->count() }},
                    markAllRead() {
                        fetch('{{ route('notifications.read-all') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        }).then(() => {
                            this.unreadCount = 0;
                            // Optionally hide or change style of notifications
                        });
                    }
                  }">
                 <div class="flex items-center justify-between mb-6">
                    <h4 class="font-black text-slate-900 text-lg tracking-tight">Notifikasi Terbaru</h4>
                    <div class="flex items-center gap-3">
                        <template x-if="unreadCount > 0">
                            <button @click="markAllRead()" class="text-[9px] font-black uppercase text-indigo-400 hover:text-indigo-600 transition-colors">Tandai Dibaca</button>
                        </template>
                        <span class="text-[10px] font-black uppercase text-indigo-500" x-text="unreadCount + ' Baru'"></span>
                    </div>
                </div>
                <div class="space-y-4 max-h-[400px] overflow-y-auto no-scrollbar">
                    @forelse($notifications as $notif)
                        <div class="flex items-start gap-4 p-3 rounded-2xl bg-slate-50/50 border border-slate-100/50 hover:bg-white hover:shadow-md transition-all group"
                             :class="unreadCount === 0 ? 'opacity-60 bg-white' : ''">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $notif->type === 'success' ? 'bg-emerald-50 text-emerald-600' : 'bg-indigo-50 text-indigo-600' }}">
                                @if($notif->type === 'success')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[11px] font-black text-slate-800 uppercase tracking-tight truncate">{{ $notif->title }}</p>
                                <p class="text-[10px] text-slate-500 mt-1 leading-relaxed">{{ $notif->message }}</p>
                                <p class="text-[8px] font-black text-indigo-400 mt-2 uppercase tracking-widest">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center">
                            <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 mx-auto mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tidak ada notifikasi baru</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
        @else
        {{-- For Regular User --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Recent Activity --}}
            <div class="lg:col-span-2 bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
                <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                    <div>
                        <h4 class="font-black text-slate-900 text-lg uppercase tracking-tight">Pesanan Terbaru</h4>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Monitor transaksi belanja Anda</p>
                    </div>
                    <a href="{{ route('transactions.index') }}" class="px-5 py-2.5 bg-indigo-50 text-indigo-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Order ID</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Total Belanja</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Status</th>
                                <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recentTransactions as $tx)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="text-xs font-black text-slate-700 group-hover:text-indigo-600 transition-colors">#{{ strtoupper(substr($tx->invoice_number, 0, 10)) }}</div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-xs font-black text-slate-900 leading-none">RP {{ number_format($tx->total_price, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-8 py-6">
                                    @php
                                        $statusClasses = [
                                            'success' => 'bg-emerald-50 text-emerald-600',
                                            'pending' => 'bg-amber-50 text-amber-600',
                                            'failed' => 'bg-rose-50 text-rose-600',
                                            'completed' => 'bg-indigo-50 text-indigo-600',
                                        ];
                                        $statusClass = $statusClasses[$tx->status] ?? 'bg-slate-50 text-slate-600';
                                    @endphp
                                    <span class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $statusClass }}">
                                        {{ str_replace('_', ' ', $tx->status) }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    {{ $tx->created_at->diffForHumans() }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 mb-4">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                        </div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Belum ada transaksi sama sekali, bolo.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Right Column side widgets --}}
            <div class="flex flex-col gap-6">
                {{-- CTA: Go to Shop --}}
                <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white flex flex-col justify-between relative overflow-hidden h-full shadow-2xl shadow-indigo-100 group transition-all duration-500 hover:shadow-indigo-200 hover:-translate-y-1">
                     <div class="relative z-10">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6 group-hover:rotate-12 transition-transform">
                             <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <h4 class="font-black text-2xl mb-2 tracking-tight">Lanjutkan Belanja!</h4>
                        <p class="text-indigo-100 text-xs font-medium mb-8 leading-relaxed opacity-80 uppercase tracking-tight">Temukan produk favoritmu sekarang bolo!</p>
                        
                        <a href="{{ route('shop.public') }}" class="inline-flex items-center justify-center px-6 py-4 bg-white text-indigo-600 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-slate-900 hover:text-white transition-all shadow-xl active:scale-95 leading-none">
                            Explore Shop
                        </a>
                     </div>
                     <div class="absolute -bottom-8 -right-8 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                </div>

                {{-- Support Info --}}
                <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-xl shadow-slate-200/50 transition-all duration-300 hover:shadow-2xl hover:border-slate-200">
                    <h5 class="font-black text-slate-900 text-lg mb-6 flex items-center gap-2 tracking-tight uppercase">
                        Butuh Bantuan?
                    </h5>
                    <div class="space-y-3">
                        <a href="{{ route('how-to') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 hover:bg-slate-900 transition-all duration-300 group">
                            <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-800 group-hover:text-white uppercase tracking-widest leading-none">Panduan Pengguna</p>
                                <p class="text-[9px] text-slate-400 group-hover:text-slate-300 font-medium mt-1">Pelajari cara belanja bolo</p>
                            </div>
                        </a>
                        
                        <a href="mailto:support@miniestore.com" class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 hover:bg-slate-900 transition-all duration-300 group">
                            <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-800 group-hover:text-white uppercase tracking-widest leading-none">Hubungi Support</p>
                                <p class="text-[9px] text-slate-400 group-hover:text-slate-300 font-medium mt-1">CS kami siap bantu 24/7</p>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Trending/Best Sellers for User --}}
                <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-xl shadow-slate-200/50 transition-all duration-300 hover:shadow-2xl hover:border-slate-200">
                    <h5 class="font-black text-slate-900 text-lg mb-6 flex items-center gap-2 tracking-tight uppercase">
                        <span class="text-orange-500">🔥</span> Trending
                    </h5>
                    <div class="space-y-4">
                        @forelse($bestSellers as $item)
                        <a href="{{ route('shop.show', $item->id) }}" class="flex items-center gap-4 p-3 rounded-2xl hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100 group">
                            <div class="w-14 h-14 rounded-2xl bg-slate-100 overflow-hidden flex-shrink-0 shadow-sm group-hover:scale-105 transition-transform">
                                @if($item->gambar)
                                    <img src="{{ Str::startsWith($item->gambar, 'image/') ? asset($item->gambar) : asset('storage/' . $item->gambar) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[11px] font-black text-slate-800 truncate group-hover:text-slate-900 transition-colors uppercase tracking-tight">{{ $item->nama_barang }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $item->total_terjual ?? 0 }} Terjual</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-black text-slate-900">RP {{ number_format($item->harga, 0, ',', '.') }}</p>
                            </div>
                        </a>
                        @empty
                        <p class="text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest py-8">Belum ada barang trending.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (!document.querySelector("#revenueChart")) return;

            var options = {
                series: [{
                    name: 'Pendapatan',
                    data: @json($revenueTimeline->pluck('total'))
                }],
                chart: {
                    type: 'area',
                    height: 300,
                    fontFamily: 'inherit',
                    toolbar: { show: false },
                    zoom: { enabled: false }
                },
                colors: ['#4f46e5'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.05,
                        stops: [0, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: @json($revenueTimeline->pluck('date')),
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 600 }
                    }
                },
                yaxis: {
                    labels: {
                        style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 600 },
                        formatter: function (value) {
                            if (value >= 1000000) return (value / 1000000).toFixed(1) + "Jt";
                            if (value >= 1000) return (value / 1000).toFixed(0) + "rb";
                            return value;
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "Rp " + new Intl.NumberFormat('id-ID').format(val);
                        }
                    }
                }
            };

            window.revenueChartInstance = new ApexCharts(document.querySelector("#revenueChart"), options);
            window.revenueChartInstance.render();
        });

        // AJAX Category Filter Logic
        window.filterCategory = function(category) {
            // Update UI Button States
            document.querySelectorAll('.btn-category').forEach(btn => {
                btn.className = 'btn-category whitespace-nowrap px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] transition-all border bg-white border-slate-100 text-slate-400 hover:border-indigo-200 hover:text-indigo-500';
            });
            
            const slug = category === 'all' ? 'all' : category.toLowerCase().replace(/\s+/g, '-');
            const activeBtn = document.getElementById('btn-cat-' + slug);
            if(activeBtn) {
                activeBtn.className = 'btn-category whitespace-nowrap px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] transition-all border bg-indigo-600 border-indigo-600 text-white shadow-md shadow-indigo-100';
            }

            // Show loading state
            const listContainer = document.getElementById('best-sell-list');
            listContainer.style.opacity = '0.5';

            // Fetch Data
            const url = window.location.pathname;
            fetch(`${url}?category=${category}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                listContainer.style.opacity = '1';

                // Update Chart
                if(window.revenueChartInstance && data.revenueTimeline) {
                    const dates = data.revenueTimeline.map(item => item.date);
                    const totals = data.revenueTimeline.map(item => item.total);
                    
                    window.revenueChartInstance.updateOptions({
                        xaxis: {
                            categories: dates
                        }
                    });
                    
                    window.revenueChartInstance.updateSeries([{
                        name: 'Pendapatan',
                        data: totals
                    }]);
                }

                let html = '';
                if(data.revenueByOffer.length > 0) {
                    data.revenueByOffer.forEach(item => {
                        html += `
                        <div class="group">
                            <div class="flex flex-col mb-2">
                                <div class="flex justify-between items-end">
                                    <div class="flex flex-col">
                                        ${item.kategori ? `<span class="text-[8px] font-black text-indigo-500 uppercase tracking-[0.2em] mb-1">${item.kategori}</span>` : ''}
                                        <span class="text-sm font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">${item.name}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-black text-slate-900 block">RP ${new Intl.NumberFormat('id-ID').format(item.total)}</span>
                                    </div>
                                </div>
                                <div class="w-full bg-slate-50 rounded-full h-2 mt-3 overflow-hidden border border-slate-100/50">
                                    <div class="bg-indigo-600 h-full rounded-full transition-all duration-1000" 
                                         style="width: ${(item.total / data.maxRevenue) * 100}%"></div>
                                </div>
                            </div>
                        </div>`;
                    });
                } else {
                    html = `
                         <div class="py-12 flex flex-col items-center justify-center bg-slate-50/50 rounded-3xl border-2 border-dashed border-slate-100">
                             <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center text-slate-300 mb-3">
                                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                             </div>
                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Belum ada penjualan<br>di kategori ini</p>
                         </div>`;
                }
                listContainer.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                listContainer.innerHTML = '<p class="text-center text-red-500 text-xs">Gagal memuat data.</p>';
                listContainer.style.opacity = '1';
            });
        };
    </script>
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f8fafc;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
    </style>
    @endpush
</x-admin-layout>
