<x-admin-layout>
    <div class="space-y-8 pb-10 px-4 sm:px-6 lg:px-8">
        {{-- Modern Greeting Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-1">
                    @php
                        $hour = date('H');
                        $greeting = 'Selamat Pagi';
                        if ($hour >= 12 && $hour < 15) $greeting = 'Selamat Siang';
                        elseif ($hour >= 15 && $hour < 18) $greeting = 'Selamat Sore';
                        elseif ($hour >= 18 || $hour < 5) $greeting = 'Selamat Malam';
                    @endphp
                    {{ $greeting }}, <span class="text-indigo-600">{{ Auth::user()->name }}</span>! 
                </h1>
                <p class="text-slate-400 text-sm font-medium">Berikut adalah ringkasan aktivitasmu hari ini.</p>
            </div>

            @if($profileIncomplete)
            <div class="flex-1 max-w-xl">
                <div class="bg-amber-50 border border-amber-100 rounded-[2rem] p-4 flex items-center gap-4 shadow-sm animate-pulse">
                    <div class="w-10 h-10 bg-amber-500 rounded-2xl flex items-center justify-center text-white shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-[11px] font-black text-amber-900 uppercase tracking-tight italic">Alamat & Pinpoint Belum Lengkap!</p>
                        <p class="text-[10px] text-amber-700 font-medium leading-tight">Kurir butuh alamat lengkap & pinpoint peta agar ongkir akurat & paket sampai depan pintu bolo.</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="px-5 py-2.5 bg-amber-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-slate-900 transition-all shadow-lg shadow-amber-200/50">Lengkapi</a>
                </div>
            </div>
            @endif
            
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex flex-col items-end mr-2 text-right">
                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest">{{ date('D, d M Y') }}</span>
                    <span class="text-xs font-bold text-slate-900">Sistem Berjalan Normal</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 shadow-sm relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @if($notifications->count() > 0)
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-rose-500 border-2 border-white rounded-full"></span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Role-Based Core Action Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @if(Auth::user()->hasRole('admin'))
                <!-- Admin Core Cards -->
                <x-stat-card title="Total Revenue" value="RP {{ number_format($totalEarnings, 0, ',', '.') }}" 
                    growth="{{ ($orderGrowth >= 0 ? '+' : '') . number_format($orderGrowth, 1) }}%" 
                    icon="revenue" color="indigo" />
                
                <x-stat-card title="Pengguna Baru" value="{{ $totalUsers }}" 
                    growth="{{ ($userGrowth >= 0 ? '+' : '') . number_format($userGrowth, 1) }}%" 
                    icon="users" color="emerald" />
                
                <x-stat-card title="Barang Terdaftar" value="{{ $totalItems }}" 
                    growth="{{ ($itemGrowth >= 0 ? '+' : '') . number_format($itemGrowth, 1) }}%" 
                    icon="box" color="amber" />
                
                <x-stat-card title="Perlu Diproses" value="{{ $pendingOrders }}" 
                    growth="Action Required" icon="shield" color="rose" />
            @elseif(Auth::user()->hasRole('seller'))
                <!-- Seller Core Cards -->
                @if($sellerTotalItems == 0)
                <div class="col-span-1 lg:col-span-4 bg-indigo-600 rounded-[2.5rem] p-8 text-white relative overflow-hidden group">
                    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="max-w-md text-center md:text-left">
                            <h2 class="text-2xl font-black mb-2 uppercase italic tracking-tight">Toko Anda Belum Ada Produk!</h2>
                            <p class="text-indigo-100/80 text-sm font-medium">Ayo tambahkan produk pertama anda untuk mulai berjualan dan mendapatkan cuan di platform ini.</p>
                        </div>
                        <a href="{{ route('item-shop.create') }}" class="px-8 py-4 bg-white text-indigo-600 rounded-2xl text-[12px] font-black uppercase tracking-[0.2em] shadow-xl hover:bg-slate-900 hover:text-white transition-all">Tambah Produk Sekarang</a>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
                </div>
                @endif
                
                <div class="col-span-1 lg:col-span-2 relative overflow-hidden bg-slate-900 p-8 rounded-[2.5rem] shadow-2xl group transition-all duration-500 hover:shadow-indigo-500/20 active:scale-[0.98]">
                    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                    <div class="relative z-10 h-full flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <span class="px-3 py-1 bg-white/10 backdrop-blur-md rounded-lg text-[10px] font-black text-indigo-300 uppercase tracking-widest border border-white/5">Saldo Dompet</span>
                            </div>
                            <h2 class="text-4xl font-black text-white tracking-tight mb-2">RP {{ number_format(Auth::user()->balance ?? 0, 0, ',', '.') }}</h2>
                            <p class="text-slate-400 text-xs font-medium uppercase tracking-widest">Tarik saldo hasil jualanmu kapan saja bolo.</p>
                        </div>
                        <div class="mt-8 flex gap-3">
                            <a href="{{ route('admin.withdrawals.index') }}" class="px-6 py-3 bg-indigo-600 hover:bg-white hover:text-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all flex items-center gap-2 shadow-lg shadow-indigo-500/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                Tarik Dana
                            </a>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-7 rounded-[2.5rem] border border-slate-100 flex flex-col justify-between shadow-[0_20px_50px_rgba(0,0,0,0.02)] group hover:shadow-[0_20px_50px_rgba(245,158,11,0.08)] transition-all">
                    <div>
                        <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <h4 class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Pesanan Perlu Diproses</h4>
                        <div class="text-3xl font-black text-slate-900">{{ $pendingOrders }}</div>
                    </div>
                    <a href="{{ route('transactions.index') }}?status=paid" class="mt-4 text-[10px] font-black text-amber-600 uppercase tracking-widest flex items-center gap-1 hover:gap-2 transition-all">
                        Kelola Pesanan <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
                <div class="bg-white p-7 rounded-[2.5rem] border border-slate-100 flex flex-col justify-between shadow-[0_20px_50px_rgba(0,0,0,0.02)] group hover:shadow-[0_20px_50px_rgba(225,29,72,0.08)] transition-all">
                    <div>
                        <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500 mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <h4 class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Barang Habis</h4>
                        <div class="text-3xl font-black text-slate-900">{{ $outOfStockItems }}</div>
                    </div>
                    <a href="{{ route('item-shop.index') }}" class="mt-4 text-[10px] font-black text-rose-600 uppercase tracking-widest flex items-center gap-1 hover:gap-2 transition-all">
                        Update Stok <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>

                {{-- Buyer Perspective for Seller --}}
                <div class="col-span-1 lg:col-span-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 pt-4">
                    <div class="bg-indigo-50/50 p-6 rounded-[2rem] border border-indigo-100/50 flex items-center justify-between group hover:bg-white hover:shadow-xl transition-all duration-500">
                        <div class="flex items-center gap-4">
                            <button @click="$store.cart.show = true" class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-indigo-600 shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-all transform hover:scale-110 active:scale-95">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </button>
                            <div>
                                <h4 class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Keranjang Saya</h4>
                                <p class="text-sm font-black text-slate-800" x-data x-text="$store.cart.items.length > 0 ? $store.cart.items.length + ' Produk' : 'Kosong'"></p>
                            </div>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="p-2 bg-indigo-600 text-white rounded-xl hover:bg-slate-900 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>
                    <div class="bg-emerald-50/50 p-6 rounded-[2rem] border border-emerald-100/50 flex items-center justify-between group hover:bg-white hover:shadow-xl transition-all duration-500">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm group-hover:bg-emerald-600 group-hover:text-white transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                            </div>
                            <div>
                                <h4 class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Pesanan Saya</h4>
                                <p class="text-sm font-black text-slate-800">{{ $userTransactionsCount }} Transaksi</p>
                            </div>
                        </div>
                        <a href="{{ route('transactions.index') }}" class="p-2 bg-emerald-600 text-white rounded-xl hover:bg-slate-900 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>
                    <div class="bg-rose-50/50 p-6 rounded-[2rem] border border-rose-100/50 flex items-center justify-between group hover:bg-white hover:shadow-xl transition-all duration-500">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-rose-600 shadow-sm group-hover:bg-rose-600 group-hover:text-white transition-all">
                                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-[10px] font-black text-rose-400 uppercase tracking-widest">Wishlist</h4>
                                <p class="text-sm font-black text-slate-800">{{ $userFavoritesCount }} Produk</p>
                            </div>
                        </div>
                        <a href="{{ route('wishlist.index') }}" class="p-2 bg-rose-600 text-white rounded-xl hover:bg-slate-900 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>
                </div>
            @else
                <!-- Bolo/User Core Cards -->
                <div class="bg-white p-7 rounded-[2.5rem] border border-slate-100 flex flex-col justify-between shadow-[0_20px_50px_rgba(0,0,0,0.02)] group hover:shadow-[0_20px_50px_rgba(79,70,229,0.08)] transition-all overflow-hidden relative" x-data>
                    <div class="absolute -right-2 -top-2 w-16 h-16 bg-indigo-50 rounded-full blur-xl opacity-50"></div>
                    <div>
                        <button @click="$store.cart.show = true" class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500 shadow-sm relative transform hover:scale-110 active:scale-95">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </button>
                        <h4 class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Keranjang Bolo</h4>
                        <div class="text-2xl font-black text-slate-800 tracking-tight" x-text="$store.cart.items.length > 0 ? $store.cart.items.length + ' Produk' : 'Kosong'"></div>
                    </div>
                    <template x-if="$store.cart.items.length > 0">
                        <a href="{{ route('checkout.index') }}" class="mt-8 py-3.5 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] text-center shadow-lg shadow-indigo-100 hover:bg-slate-900 transition-all active:scale-95 leading-none">Ke Checkout</a>
                    </template>
                    <template x-if="$store.cart.items.length === 0">
                        <button disabled class="mt-8 py-3.5 bg-slate-100 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] text-center cursor-not-allowed leading-none">Keranjang Kosong</button>
                    </template>
                </div>

                <div class="bg-white p-7 rounded-[2.5rem] border border-slate-100 flex flex-col justify-between shadow-[0_20px_50px_rgba(0,0,0,0.02)] group hover:shadow-[0_20px_50px_rgba(16,185,129,0.08)] transition-all overflow-hidden relative">
                    <div class="absolute -right-2 -top-2 w-16 h-16 bg-emerald-50 rounded-full blur-xl opacity-50"></div>
                    <div>
                        <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                        </div>
                        <h4 class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Status Pengiriman</h4>
                        <div class="text-2xl font-black text-slate-800 tracking-tight">{{ $userTransactionsCount }} Transaksi</div>
                    </div>
                    <a href="{{ route('transactions.index') }}" class="mt-8 py-3.5 bg-emerald-500 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] text-center shadow-lg shadow-emerald-100 hover:bg-slate-900 transition-all active:scale-95 leading-none">Lihat Paket</a>
                </div>

                <div class="bg-white p-7 rounded-[2.5rem] border border-slate-100 flex flex-col justify-between shadow-[0_20px_50px_rgba(0,0,0,0.02)] group hover:shadow-[0_20px_50px_rgba(225,29,72,0.08)] transition-all overflow-hidden relative">
                    <div class="absolute -right-2 -top-2 w-16 h-16 bg-rose-50 rounded-full blur-xl opacity-50"></div>
                    <div>
                        <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500 mb-6 group-hover:bg-rose-500 group-hover:text-white transition-all duration-500 shadow-sm">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <h4 class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Daftar Suka</h4>
                        <div class="text-2xl font-black text-slate-800 tracking-tight">{{ $userFavoritesCount }} Produk</div>
                    </div>
                    <a href="{{ route('wishlist.index') }}" class="mt-8 py-3.5 bg-rose-500 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] text-center shadow-lg shadow-rose-100 hover:bg-slate-900 transition-all active:scale-95 leading-none">Cek Wishlist</a>
                </div>

                 <div class="bg-indigo-600 p-7 rounded-[2.5rem] flex flex-col justify-between shadow-2xl shadow-indigo-100 group hover:-translate-y-1 transition-all overflow-hidden relative">
                    <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-1000"></div>
                    <div>
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white mb-6 group-hover:rotate-12 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <h4 class="text-indigo-100/70 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Koleksi Baru</h4>
                        <div class="text-2xl font-black text-white tracking-tight">Gas Jajan!</div>
                    </div>
                    <a href="{{ route('shop.public') }}" class="mt-8 py-3.5 bg-white text-indigo-600 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] text-center shadow-lg hover:bg-slate-900 hover:text-white transition-all leading-none">Mulai</a>
                </div>
            @endif
        </div>

        {{-- Analytics & Recent Activity --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('seller'))
                <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)]">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h4 class="font-black text-slate-900 text-lg uppercase tracking-tight italic">Analis Penjualan</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Garis pendapatan 7 hari terakhir</p>
                        </div>
                        <div class="flex gap-2">
                             <a href="{{ route('dashboard', ['export' => 'excel']) }}" class="text-[9px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-4 py-2 rounded-xl hover:bg-indigo-600 hover:text-white transition-all">Export Excel</a>
                        </div>
                    </div>
                    @if($revenueTimeline->sum('total') > 0)
                        <div id="revenueChart" class="min-h-[350px]"></div>
                    @else
                        <div class="min-h-[350px] flex flex-col items-center justify-center bg-slate-50/50 rounded-[2rem] border border-dashed border-slate-200">
                             <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-slate-300 shadow-sm mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                             </div>
                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Belum Ada Data Penjualan</p>
                        </div>
                    @endif
                </div>
                @endif

                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] overflow-hidden transition-all hover:shadow-[0_20px_50px_rgba(0,0,0,0.04)]">
                    <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-white/50 backdrop-blur-md sticky top-0 z-10">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h4 class="font-black text-slate-900 text-lg uppercase tracking-tight">Order History</h4>
                        </div>
                        @if($recentTransactions->count() > 0)
                            <a href="{{ route('transactions.index') }}" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-5 py-2.5 rounded-2xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm">View All</a>
                        @endif
                    </div>
                    
                    @if($recentTransactions->count() > 0)
                    <div class="p-4 sm:p-8 space-y-4">
                        @foreach($recentTransactions as $tx)
                            <div class="group relative bg-slate-50/50 hover:bg-white p-5 rounded-[2rem] border border-transparent hover:border-slate-100 hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500 overflow-hidden">
                                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="relative">
                                            <div class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform duration-500 overflow-hidden shadow-inner">
                                                @php $txUser = $tx->user; @endphp
                                                @if($txUser && $txUser->avatar)
                                                    <img src="{{ asset($txUser->avatar) }}" class="w-full h-full object-cover">
                                                @else
                                                    <svg class="w-7 h-7 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                @endif
                                            </div>
                                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-white rounded-lg shadow-sm flex items-center justify-center border border-slate-50">
                                                <svg class="w-3.5 h-3.5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <h5 class="text-xs font-black text-slate-900 uppercase tracking-tight">{{ $txUser->name ?? 'Guest User' }}</h5>
                                                <span class="text-[8px] font-black px-2 py-0.5 bg-white border border-slate-100 text-slate-400 rounded-full uppercase tracking-tighter shadow-sm">#{{ strtoupper(substr($tx->invoice_number ?? $tx->id, -8)) }}</span>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $tx->created_at->diffForHumans() }}</span>
                                                <span class="w-1.5 h-1.5 bg-slate-200 rounded-full"></span>
                                                <span class="text-[9px] font-black text-indigo-600 uppercase tracking-tight italic">RP {{ number_format($tx->total_price, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        @php
                                            $styleStatus = match($tx->status) {
                                                'paid', 'success' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                'shipped' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                'completed' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                                'pending', 'waiting_confirmation' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                'failed', 'canceled' => 'bg-rose-50 text-rose-600 border-rose-100',
                                                default => 'bg-slate-50 text-slate-600 border-slate-100'
                                            };
                                            $labelStatus = match($tx->status) {
                                                'paid', 'success' => 'PEMBAYARAN',
                                                'shipped' => 'DIKIRIM',
                                                'completed' => 'SELESAI',
                                                'pending' => 'MENUNGGU',
                                                'waiting_confirmation' => 'KONFIRMASI',
                                                'failed' => 'GAGAL',
                                                'canceled' => 'BATAL',
                                                default => strtoupper(str_replace('_', ' ', $tx->status))
                                            };
                                            $iconStatus = match($tx->status) {
                                                'paid', 'success' => '<svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM5 12a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/></svg>',
                                                'shipped' => '<svg class="w-2.5 h-2.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>',
                                                'completed' => '<svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>',
                                                default => ''
                                            };
                                        @endphp
                                        <div class="inline-flex items-center px-4 py-2 rounded-xl border {{ $styleStatus }} text-[8px] font-black uppercase tracking-[0.15em] shadow-sm transition-all duration-300 group-hover:scale-105">
                                            {!! $iconStatus !!}
                                            {{ $labelStatus }}
                                        </div>
                                        <a href="{{ route('transactions.show', $tx->id) }}" class="w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-100 hover:shadow-lg transition-all active:scale-95">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="absolute left-0 top-0 w-1.5 h-full bg-indigo-600 -translate-x-full group-hover:translate-x-0 transition-transform duration-500"></div>
                            </div>
                        @endforeach
                    </div>
                    @else
                    <div class="p-12 text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-slate-200">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <h5 class="text-sm font-black text-slate-800 uppercase tracking-tight mb-2">Belum Ada Jejak Belanja</h5>
                        <p class="text-[11px] text-slate-400 font-medium max-w-[200px] mx-auto mb-8">Sepertinya kamu belum melakukan transaksi apapun bolo. Ayo cari barang impianmu!</p>
                        <a href="{{ route('shop.public') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-900 transition-all shadow-lg shadow-indigo-100">
                            Mulai Belanja 
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <div class="space-y-8">
                <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)]">
                    <h5 class="font-black text-slate-900 text-lg mb-6 uppercase tracking-tight border-b border-slate-50 pb-4"> Lagi Hot Banget Loh Rek</h5>
                    <div class="space-y-4">
                        @forelse($bestSellers->take(5) as $item)
                        <a href="{{ route('shop.show', $item->id) }}" class="flex items-center gap-4 group cursor-pointer hover:bg-slate-50 p-2 rounded-2xl transition-all">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden shrink-0 border border-white shadow-sm">
                                <img src="{{ Str::startsWith($item->gambar, 'image/') ? asset($item->gambar) : asset('storage/' . $item->gambar) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[11px] font-black text-slate-800 truncate uppercase tracking-tight">{{ $item->nama_barang }}</p>
                                <p class="text-[10px] text-emerald-500 font-bold uppercase tracking-widest italic">{{ $item->total_terjual ?? 0 }} Terjual</p>
                            </div>
                        </a>
                        @empty
                        <div class="py-10 text-center">
                            <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Toko Belum Buka Bolo</p>
                        </div>
                        @endforelse
                    </div>
                    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('seller'))
                    <a href="{{ route('item-shop.index') }}" class="mt-6 block text-center py-3 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-lg hover:bg-indigo-600 transition-all">Kelola Barang</a>
                    @endif
                </div>

                <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden group shadow-2xl">
                    <div class="relative z-10 h-full flex flex-col">
                        <h4 class="font-black text-white text-lg tracking-tight uppercase italic mb-6">Pemberitahuan</h4>
                        <div class="space-y-4 max-h-[300px] overflow-y-auto no-scrollbar pb-4">
                            @forelse($notifications as $n)
                                <div class="p-4 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 transition-all">
                                    <p class="text-[10px] font-black text-white uppercase tracking-tight mb-1 italic">{{ $n->title }}</p>
                                    <p class="text-[9px] text-indigo-100/70 leading-relaxed font-bold uppercase tracking-tight">{{ $n->message }}</p>
                                    <p class="text-[8px] font-black text-indigo-400 mt-2 uppercase tracking-widest text-right">{{ $n->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <p class="text-[10px] font-black text-indigo-300 text-center py-10 uppercase tracking-[0.2em] opacity-50">Semua aman terkendali!</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartDom = document.querySelector("#revenueChart");
            if (!chartDom) return;
            
            var options = {
                series: [{ name: 'Revenue', data: @json($revenueTimeline->pluck('total') ?? []) }],
                chart: { type: 'area', height: 350, fontFamily: 'Plus Jakarta Sans, sans-serif', toolbar: { show: false }, zoom: { enabled: false } },
                colors: ['#4f46e5'],
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05, stops: [0, 100] } },
                stroke: { curve: 'smooth', width: 3 },
                xaxis: { 
                    categories: @json($revenueTimeline->pluck('date') ?? []), 
                    labels: { style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 700 } },
                    axisBorder: { show: false }, axisTicks: { show: false }
                },
                yaxis: { 
                    labels: { 
                        style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 700 }, 
                        formatter: (v) => "Rp " + v.toLocaleString() 
                    } 
                },
                grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                tooltip: { theme: 'dark' },
                dataLabels: { enabled: false }
            };
            new ApexCharts(chartDom, options).render();
        });
    </script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @endpush
</x-admin-layout>
