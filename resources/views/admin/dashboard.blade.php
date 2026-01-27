<x-admin-layout>
    <div class="space-y-6">
        {{-- Welcome Section --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-8 border border-slate-100 relative">
             <div class="relative z-10">
                <h3 class="text-2xl font-bold text-slate-900">Welcome, {{ Auth::user()->name }} 👋</h3>
                <p class="text-slate-500 mt-2">Here is what's happening with your account today.</p>
            </div>
            <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-indigo-50 to-transparent hidden md:block"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @if(!Auth::user()->hasRole('admin'))
                {{-- User Specific: Favorites Summary --}}
                @php
                    $favCount = Auth::user()->favoriteItems()->count();
                @endphp
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-xl font-black text-slate-900 tracking-tight">Daftar Suka</h4>
                            <div class="w-10 h-10 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500 transition-transform group-hover:scale-110">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </div>
                        </div>
                        
                        <div class="py-6 text-center">
                            @if($favCount > 0)
                                <div class="text-4xl font-black text-slate-900 mb-1">{{ $favCount }}</div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Produk Disimpan</p>
                            @else
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest leading-relaxed text-balance">Belum ada barang<br>favorit bolo.</p>
                            @endif
                        </div>
                    </div>

                    <a href="{{ route('wishlist.index') }}" 
                        class="w-full py-4 bg-rose-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-rose-600 transition-all text-center">
                        Lihat Semua Suka
                    </a>
                </div>

                {{-- User Specific: Cart Summary --}}
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 flex flex-col justify-between" x-data>
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-xl font-black text-slate-900 tracking-tight">Keranjang Bolo</h4>
                            <div class="w-10 h-10 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                        </div>
                        
                        <div class="space-y-4 max-h-[180px] overflow-y-auto no-scrollbar mb-6 text-left">
                            <template x-if="$store.cart.items.length === 0">
                                <div class="text-center py-6">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest leading-relaxed">Keranjang kosong,<br>yuk jajan!</p>
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
                        class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-black transition-all text-center shadow-lg shadow-slate-200">
                        Lanjut Checkout
                    </a>
                    <div x-show="$store.cart.items.length === 0" class="w-full py-4 bg-slate-100 text-slate-400 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] text-center cursor-not-allowed">
                        Kosong Bolo
                    </div>
                </div>
            @endif

            {{-- Metrics/Stats or Action Cards --}}
            @if(Auth::user()->hasRole('seller') || Auth::user()->hasRole('admin'))
                <div class="{{ Auth::user()->hasRole('admin') ? 'md:col-span-2' : 'md:col-span-1' }} grid grid-cols-1 {{ Auth::user()->hasRole('admin') ? 'sm:grid-cols-3' : '' }} gap-4">
                     <!-- Card: Total Items -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 flex items-center justify-between shadow-sm">
                        <div>
                            <div class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Total Items</div>
                            <div class="text-2xl font-black text-slate-900">{{ $totalItems }}</div>
                        </div>
                        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                    </div>

                    @if(Auth::user()->hasRole('seller'))
                    <!-- Card: Wallet Balance -->
                    <div class="bg-slate-900 p-6 rounded-2xl border border-slate-800 flex items-center justify-between shadow-xl shadow-slate-200 group overflow-hidden relative">
                        <div class="relative z-10">
                            <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Saldo Tersedia</div>
                            <div class="text-2xl font-black text-white">RP {{ number_format(Auth::user()->balance, 0, ',', '.') }}</div>
                            <a href="{{ route('admin.withdrawals.index') }}" 
                                class="mt-3 text-[9px] font-bold text-indigo-400 uppercase tracking-widest hover:text-indigo-300 flex items-center gap-2">
                                Tarik Saldo
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                        <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center text-indigo-400 relative z-10">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                    </div>
                    @endif

                    <!-- Card: Total Earnings -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 flex items-center justify-between transition-transform hover:scale-[1.02] shadow-sm">
                        <div>
                            <div class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Total Pendapatan</div>
                            <div class="text-2xl font-black text-indigo-600">RP {{ number_format($totalEarnings, 0, ',', '.') }}</div>
                        </div>
                        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>

                    <!-- Card: Orders -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 flex items-center justify-between shadow-sm">
                         <div>
                            <div class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Pesanan Sukses</div>
                            <div class="text-2xl font-black text-slate-900">{{ $totalOrdersCount }}</div>
                        </div>
                         <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                    </div>
                </div>

                {{-- Quick Action (Responsive adjustment) --}}
                <div class="bg-indigo-600 rounded-2xl p-6 text-white flex flex-col justify-between relative overflow-hidden h-full shadow-lg shadow-indigo-100/50">
                     <div class="relative z-10">
                        <h4 class="font-bold text-lg mb-2">New Product</h4>
                        <p class="text-indigo-100 text-sm mb-6">Tambahkan Produk baru dalam tokomu.</p>
                        <a href="{{ route('item-shop.create') }}" class="inline-flex w-full items-center justify-center px-4 py-3 bg-white text-indigo-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-colors shadow-lg active:scale-95">
                            + Add Item
                        </a>
                     </div>
                     <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
                </div>
            @else
                <div class="md:col-span-3 grid grid-cols-1 sm:grid-cols-3 gap-4">
                     <!-- Card: User Transactions -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 flex items-center justify-between">
                        <div>
                            <div class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1 text-blue-600">Total Pesanan</div>
                            <div class="text-2xl font-black text-slate-900">{{ $userTransactionsCount }}</div>
                        </div>
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                    </div>

                    <!-- Card: User Favorites -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 flex items-center justify-between">
                        <div>
                            <div class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1 text-rose-600">Favorit</div>
                            <div class="text-2xl font-black text-rose-600">{{ $userFavoritesCount }}</div>
                        </div>
                        <div class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600">
                             <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                    </div>

                    <!-- Card: User Reviews -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 flex items-center justify-between">
                         <div>
                            <div class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1 text-amber-600">Ulasan Saya</div>
                            <div class="text-2xl font-black text-slate-900">{{ $userReviewsCount }}</div>
                        </div>
                         <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('seller'))
        {{-- Revenue Dashboard Detailed Widgets --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pb-12 items-start">
            
            {{-- Widget: Revenue Timeline --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm h-full flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="font-bold text-slate-900">Garis Waktu Pendapatan</h4>
                        <span class="text-[10px] font-black uppercase text-slate-400">7 Hari Terakhir</span>
                    </div>
                    @php
                        $exportRoute = route('dashboard', ['export' => 'excel']);
                    @endphp
                    <a href="{{ $exportRoute }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center bg-indigo-50 hover:bg-indigo-100 px-3 py-2 rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Export Excel
                    </a>
                </div>
                <div id="revenueChart" class="w-full flex-grow min-h-[300px]"></div>
            </div>

            {{-- Widget: Top Best Sell In Market --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm transition-all hover:shadow-md h-full flex flex-col">
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
                       class="btn-category whitespace-nowrap px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] transition-all border {{ $selectedCategory == 'all' ? 'bg-indigo-600 border-indigo-600 text-white shadow-md shadow-indigo-100' : 'bg-white border-slate-100 text-slate-400 hover:border-indigo-200 hover:text-indigo-500' }}">
                        All
                    </button>
                    @foreach($categories as $cat)
                        <button onclick="filterCategory('{{ $cat }}')" id="btn-cat-{{ Str::slug($cat) }}"
                           class="btn-category whitespace-nowrap px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] transition-all border {{ strtolower($selectedCategory) == strtolower($cat) ? 'bg-indigo-600 border-indigo-600 text-white shadow-md shadow-indigo-100' : 'bg-white border-slate-100 text-slate-400 hover:border-indigo-200 hover:text-indigo-500' }}">
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
                                            <span class="text-[8px] font-black text-indigo-500 uppercase tracking-[0.2em] mb-1">{{ $data['kategori'] }}</span>
                                        @endif
                                        <span class="text-sm font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">{{ $data['name'] }}</span>
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
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                 <div class="flex items-center justify-between mb-6">
                    <h4 class="font-bold text-slate-900">Total Belanja per User</h4>
                    <span class="text-[10px] font-black uppercase text-green-500">Top Buyers</span>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($revenueByUser as $usr)
                        <div class="py-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold">
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

            {{-- Widget: Geographical (Placeholder/Status) --}}
             <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                 <div class="flex items-center justify-between mb-6">
                    <h4 class="font-bold text-slate-900">Penyebaran Geografis</h4>
                    <span class="text-[10px] font-black uppercase text-slate-400">Status Pengiriman</span>
                </div>
                <div class="flex flex-col items-center justify-center py-6">
                    <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600 mb-4 animate-pulse">
                         <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    </div>
                    <p class="text-xs font-bold text-slate-600">Layanan Aktif: Mojokerto</p>
                    <p class="text-[10px] text-slate-400 mt-1">Sistem Sedang Menganalisis Titik Pengiriman...</p>
                </div>
            </div>

        </div>
        @else
        {{-- For Regular User --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Recent Activity --}}
            <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                    <div>
                        <h4 class="font-bold text-slate-900 text-lg">Pesanan Terbaru</h4>
                        <p class="text-xs text-slate-500">Monitor transaksi belanja Anda</p>
                    </div>
                    <a href="#" class="text-xs font-bold text-indigo-600 hover:underline">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400">Order ID</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400">Total</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recentTransactions as $tx)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold text-slate-700">#{{ strtoupper(substr($tx->invoice_number, 0, 8)) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-black text-slate-900">RP {{ number_format($tx->total_price, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClasses = [
                                            'success' => 'bg-emerald-50 text-emerald-600',
                                            'pending' => 'bg-amber-50 text-amber-600',
                                            'failed' => 'bg-rose-50 text-rose-600',
                                        ];
                                        $statusClass = $statusClasses[$tx->status] ?? 'bg-slate-50 text-slate-600';
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase {{ $statusClass }}">
                                        {{ $tx->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500">
                                    {{ $tx->created_at->diffForHumans() }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center text-slate-300 mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                        </div>
                                        <p class="text-sm font-medium text-slate-400">Belum ada transaksi</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Right Column CTA --}}
            <div class="space-y-6">
                <div class="bg-indigo-600 rounded-3xl p-8 text-white relative overflow-hidden group">
                    <div class="relative z-10">
                        <h4 class="font-bold text-xl mb-3">Lanjutkan Belanja!</h4>
                        <p class="text-indigo-100 text-sm mb-6 leading-relaxed">Temukan berbagai produk kebutuhan harianmu dengan harga terbaik di store kami.</p>
                        <a href="/" class="inline-flex items-center px-6 py-3 bg-white text-indigo-600 rounded-2xl font-black text-xs uppercase tracking-wider hover:bg-slate-50 transition-all transform group-hover:translate-x-1 shadow-xl">
                            Eksplor Sekarang
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                    <div class="absolute -right-4 -bottom-4 w-40 h-40 bg-white opacity-5 rounded-full blur-3xl group-hover:opacity-10 transition-opacity"></div>
                </div>

                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                    <h5 class="font-bold text-slate-900 mb-4">Butuh Bantuan?</h5>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span class="text-xs text-slate-600 font-medium">Panduan Pengguna</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="text-xs text-slate-600 font-medium">Hubungi Support</span>
                        </div>
                    </div>
                </div>

                {{-- Trending/Best Sellers for User --}}
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                    <h5 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <span class="text-orange-500">🔥</span> Trending Sekarang
                    </h5>
                    <div class="space-y-4">
                        @foreach($bestSellers as $item)
                        <div class="flex items-center gap-3 group">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0">
                                @if($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-slate-800 truncate group-hover:text-indigo-600 transition-colors">{{ $item->nama_barang }}</p>
                                <p class="text-[10px] text-slate-400 font-medium">{{ $item->total_terjual }} Terjual</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-black text-indigo-600">RP {{ number_format($item->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @endforeach
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
