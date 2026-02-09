<x-admin-layout>
    <div class="space-y-8 pb-10 px-4 sm:px-6 lg:px-8">
        {{-- Modern Greeting Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight mb-1">
                    @php
                        $hour = date('H');
                        $greeting = 'Selamat Pagi';
                        if ($hour >= 12 && $hour < 15) $greeting = 'Selamat Siang';
                        elseif ($hour >= 15 && $hour < 18) $greeting = 'Selamat Sore';
                        elseif ($hour >= 18 || $hour < 5) $greeting = 'Selamat Malam';
                    @endphp
                    {{ $greeting }}, <span class="text-emerald-600">{{ Auth::user()->name }}</span>! 
                </h1>
                <p class="text-slate-400 text-sm font-medium">Berikut adalah ringkasan aktivitasmu hari ini.</p>
            </div>

            @if($profileIncomplete)
            <div class="flex-1 max-w-xl">
                <div class="bg-white border border-slate-100 rounded-2xl p-4 flex items-center gap-4 shadow-sm transition-all group hover:shadow-md">
                    <div class="w-10 h-10 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shrink-0 shadow-lg shadow-emerald-100 group-hover:rotate-12 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-semibold text-slate-900">Profil Belum Lengkap!</p>
                        <p class="text-xs text-slate-500 leading-tight">Lengkapi alamat & pinpoint peta agar ongkir akurat & paket sampai depan pintu.</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-semibold hover:bg-emerald-600 transition-all">Lengkapi</a>
                </div>
            </div>
            @endif
            
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex flex-col items-end mr-2 text-right">
                    <span class="text-xs text-slate-400">{{ date('D, d M Y') }}</span>
                    <span class="text-xs font-medium text-slate-900">Sistem Berjalan Normal</span>
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            @if(Auth::user()->hasRole('admin'))
                <!-- Admin Core Cards -->
                <x-stat-card title="Omzet Platform" value="RP {{ number_format($totalEarnings, 0, ',', '.') }}" 
                    growth="Gross Sales" 
                    icon="revenue" color="emerald" />

                <x-stat-card title="Keuntungan" value="RP {{ number_format($platformProfit, 0, ',', '.') }}" 
                    growth="Fee & Komisi" 
                    icon="revenue" color="emerald" />
                
                <x-stat-card title="Pengguna" value="{{ $totalUsers }}" 
                    growth="{{ ($userGrowth >= 0 ? '+' : '') . number_format($userGrowth, 1) }}%" 
                    icon="users" color="emerald" />
                
                <x-stat-card title="Produk" value="{{ $totalItems }}" 
                    growth="{{ ($itemGrowth >= 0 ? '+' : '') . number_format($itemGrowth, 1) }}%" 
                    icon="box" color="emerald" />
                
                <x-stat-card title="Perlu Proses" value="{{ $pendingOrders }}" 
                    growth="Action Required" icon="shield" color="emerald" />
            @elseif(Auth::user()->hasRole('seller') || Auth::user()->hasRole('courier'))
                <!-- Seller & Courier Control Center -->

                {{-- ===== SELLER / COURIER HERO PROFILE CARD ===== --}}
                <div class="col-span-1 lg:col-span-3 mb-2">
                    <div class="relative overflow-hidden bg-white p-8 md:p-10 rounded-2xl border border-slate-100 shadow-sm h-full">
                        {{-- Subtle decorative elements --}}
                        <div class="absolute -right-16 -top-16 w-56 h-56 bg-emerald-50/50 rounded-full blur-[80px]"></div>
                        <div class="absolute -left-8 -bottom-8 w-40 h-40 bg-slate-50 rounded-full blur-[60px]"></div>

                        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center gap-6">
                            {{-- Avatar --}}
                            <div class="relative shrink-0">
                                <div class="w-20 h-20 md:w-24 md:h-24 rounded-2xl overflow-hidden ring-4 ring-slate-50 shadow-md">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ asset(Auth::user()->avatar) }}" class="w-full h-full object-cover" alt="Avatar">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center">
                                            <span class="text-3xl font-bold text-white/90">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-7 h-7 bg-emerald-500 rounded-xl border-[3px] border-white flex items-center justify-center shadow-sm">
                                    @if(Auth::user()->hasRole('seller'))
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    @else
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                    @endif
                                </div>
                            </div>

                            {{-- User Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[11px] font-bold border border-emerald-100">
                                        {{ Auth::user()->hasRole('seller') ? 'Verified Seller' : 'Kurir Aktif' }}
                                    </span>
                                    @if(Auth::user()->hasRole('seller') && Auth::user()->nama_toko)
                                        <span class="px-3 py-1 bg-slate-50 rounded-full text-[11px] font-bold text-slate-500 border border-slate-100">
                                            {{ Auth::user()->nama_toko }}
                                        </span>
                                    @endif
                                    @if(Auth::user()->hasRole('seller'))
                                        <span class="px-3 py-1 bg-amber-50 rounded-full text-xs font-bold text-amber-600 border border-amber-100 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            {{ number_format($storeRating, 1) }}
                                        </span>
                                    @endif
                                </div>
                                <h2 class="text-2xl md:text-3xl font-bold tracking-tight mb-1 truncate text-slate-900">{{ Auth::user()->name }}</h2>
                                <p class="text-slate-400 text-sm font-medium">
                                    {{ Auth::user()->hasRole('seller') 
                                        ? 'Kelola produk, pantau penjualan, dan raih cuan hari ini bolo!' 
                                        : 'Cek kiriman terbaru dan selesaikan tugas antar paket bolo!' }}
                                </p>
                            </div>

                            {{-- CTA Buttons --}}
                            <div class="flex gap-3 shrink-0">
                                @if(Auth::user()->hasRole('seller'))
                                    <a href="{{ route('item-shop.create') }}" class="px-5 py-3 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200 active:scale-95">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                            Produk Baru
                                        </span>
                                    </a>
                                @endif
                                <a href="{{ route('chat.index') }}" class="px-6 py-3.5 bg-slate-50 text-slate-600 rounded-2xl text-xs font-bold hover:bg-slate-100 transition-all border border-slate-200 active:scale-95">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        Chat
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Vault Balance Card --}}
                <div class="col-span-1 lg:col-span-2 relative overflow-hidden bg-white p-8 rounded-2xl border border-slate-100 shadow-sm group transition-all duration-500 hover:-translate-y-1 hover:shadow-lg h-full">
                    <div class="absolute -right-16 -top-16 w-56 h-56 bg-emerald-50/60 rounded-full blur-[80px] group-hover:bg-emerald-50 transition-colors duration-700"></div>
                    
                    <div class="relative z-10 h-full flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-8">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center shadow-sm border border-emerald-100 transform -rotate-6 group-hover:rotate-0 transition-transform duration-500">
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    </div>
                                    <span class="text-xs font-bold text-slate-400">Vault Balance</span>
                                </div>
                                <div class="px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-full">
                                    <span class="text-[11px] font-bold text-emerald-600">Platinum Account</span>
                                </div>
                            </div>
                            
                            <h2 class="text-4xl font-bold text-slate-900 tracking-tight mb-4">
                                <span class="text-xl font-normal text-slate-400 mr-1">Rp</span>{{ number_format(Auth::user()->balance ?? 0, 0, ',', '.') }}
                            </h2>
                            
                            <p class="text-slate-400 text-[11px] font-medium leading-relaxed max-w-[80%]">
                                "{{ Auth::user()->hasRole('courier') ? 'Hasil keringatmu narik paket bolo, cairkan sekarang.' : 'Pendapatan bersih hasil jualanmu yang siap ditarik.' }}"
                            </p>
                        </div>

                        <div class="mt-8 flex items-center gap-4">
                            <a href="{{ route('admin.withdrawals.index') }}" class="flex-1 bg-emerald-600 text-white py-3.5 rounded-xl text-xs font-bold transition-all hover:bg-emerald-700 flex items-center justify-center gap-3 shadow-lg shadow-emerald-200 active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                Tarik Saldo
                            </a>
                        </div>
                    </div>
                </div>

                @if(Auth::user()->hasRole('seller'))
                <x-stat-card title="Perlu Proses" value="{{ $pendingOrders }}" 
                    growth="Orders" icon="box" color="emerald" />
                
                <x-stat-card title="Barang Habis" value="{{ $outOfStockItems }}" 
                    growth="Stock Alert" icon="shield" color="emerald" />
                @else
                <x-stat-card title="Perlu Kirim" value="{{ $pendingDeliveries }}" 
                    growth="Deliveries" icon="box" color="emerald" />
                
                <x-stat-card title="Tugas Selesai" value="{{ $totalDeliveries }}" 
                    growth="Success" icon="users" color="emerald" />
                @endif

                @if(Auth::user()->hasRole('seller') && $sellerTotalItems == 0)
                <div class="col-span-1 lg:col-span-4 bg-white rounded-2xl p-8 relative overflow-hidden group border border-dashed border-slate-200 hover:border-emerald-300 transition-all mb-4">
                    <div class="absolute top-0 right-0 p-8 opacity-5 group-hover:opacity-10 transition-opacity">
                        <svg class="w-32 h-32 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                    </div>
                    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                        <div class="max-w-md text-center md:text-left">
                            <div class="inline-block px-4 py-1.5 bg-amber-50 text-amber-600 rounded-full text-[11px] font-bold mb-4 border border-amber-100">Empty Inventory</div>
                            <h2 class="text-2xl font-bold text-slate-900 mb-3 leading-tight">Mulai Jualan Sekarang!</h2>
                            <p class="text-slate-400 text-sm leading-relaxed">Etalase kamu masih kosong. Yuk pajang produkmu sekarang.</p>
                        </div>
                        <a href="{{ route('item-shop.create') }}" class="px-8 py-4 bg-emerald-600 text-white rounded-xl text-xs font-bold shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-95 transition-all">
                            Tambah Produk
                        </a>
                    </div>
                </div>
                @endif
                
                {{-- ===== SELLER/COURIER QUICK ACTION ROW ===== --}}
                <div class="col-span-1 lg:col-span-5 mt-2">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @if(Auth::user()->hasRole('seller'))
                        <a href="{{ route('item-shop.index') }}" class="flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-100 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300 group">
                            <div class="w-10 h-10 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <div class="flex-1">
                                <h5 class="text-xs font-semibold text-slate-900">Kelola Produk</h5>
                                <p class="text-xs text-slate-400">{{ $sellerTotalItems }} barang terdaftar</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        @endif
                        <a href="{{ Auth::user()->hasRole('courier') ? route('courier.deliveries.index') : route('admin.transactions.index') }}" class="flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-100 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300 group">
                            <div class="w-10 h-10 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            </div>
                            <div class="flex-1">
                                <h5 class="text-xs font-semibold text-slate-900">{{ Auth::user()->hasRole('courier') ? 'Tugas Pengantaran' : 'Pesanan Masuk' }}</h5>
                                <p class="text-xs text-slate-400">{{ Auth::user()->hasRole('courier') ? 'Cek paket yang harus diantar' : 'Kelola semua order masuk' }}</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-100 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300 group">
                            <div class="w-10 h-10 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div class="flex-1">
                                <h5 class="text-xs font-semibold text-slate-900">Edit Profil</h5>
                                <p class="text-xs text-slate-400">Kelola toko & akun</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="{{ route('home') }}" class="flex items-center gap-4 p-5 bg-emerald-50 rounded-2xl border border-emerald-100 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300 group">
                            <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white group-hover:scale-105 transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            </div>
                            <div class="flex-1">
                                <h5 class="text-xs font-semibold text-slate-900">Buka Marketplace</h5>
                                <p class="text-xs text-emerald-600">Lihat etalase pembeli</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            @else
                <!-- Bolo/User Premium Dashboard Cards -->
                <div class="col-span-1 lg:col-span-4 space-y-6" x-data>
                    {{-- Welcome Hero Card - Clean White --}}
                    <div class="relative overflow-hidden bg-white p-8 md:p-10 rounded-2xl border border-slate-100 shadow-sm">
                        {{-- Subtle decorative elements --}}
                        <div class="absolute -right-16 -top-16 w-56 h-56 bg-emerald-50/50 rounded-full blur-[80px]"></div>
                        <div class="absolute -left-8 -bottom-8 w-40 h-40 bg-slate-50 rounded-full blur-[60px]"></div>
                        
                        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center gap-6">
                            {{-- User Avatar --}}
                            <div class="relative shrink-0">
                                <div class="w-20 h-20 md:w-24 md:h-24 rounded-2xl overflow-hidden ring-4 ring-slate-50 shadow-md">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ asset(Auth::user()->avatar) }}" class="w-full h-full object-cover" alt="Avatar">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center">
                                            <span class="text-3xl font-bold text-white/90">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-7 h-7 bg-emerald-500 rounded-xl border-[3px] border-white flex items-center justify-center shadow-sm">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                </div>
                            </div>

                            {{-- User Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[11px] font-bold border border-emerald-100">Member Aktif</span>
                                    <span class="px-3 py-1 bg-slate-50 rounded-full text-[11px] font-bold text-slate-400 border border-slate-100">
                                        Bergabung {{ Auth::user()->created_at->translatedFormat('d M Y') }}
                                    </span>
                                </div>
                                <h2 class="text-2xl md:text-3xl font-bold tracking-tight mb-1 truncate text-slate-900">{{ Auth::user()->name }}</h2>
                                <p class="text-slate-400 text-sm font-medium">Pantau semua aktivitas belanjamu di sini bolo.</p>
                            </div>

                            {{-- CTA Buttons --}}
                            <div class="flex gap-3 shrink-0">
                                <a href="{{ route('shop.public') }}" class="px-6 py-3.5 bg-emerald-600 text-white rounded-2xl text-xs font-bold hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200 active:scale-95">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                        Belanja
                                    </span>
                                </a>
                                <a href="{{ route('chat.index') }}" class="px-6 py-3.5 bg-slate-50 text-slate-600 rounded-2xl text-xs font-bold hover:bg-slate-100 transition-all border border-slate-200 active:scale-95">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        Chat
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- 4-Column Quick Stats with colored top accent --}}
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Cart Card --}}
                        <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-slate-100/80 group transition-all duration-500 hover:shadow-emerald-500/10 hover:-translate-y-1 flex flex-col justify-between h-full">
                            <div class="absolute top-0 left-6 right-6 h-1 bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-b-full"></div>
                            <div class="absolute -right-3 -top-3 w-20 h-20 bg-emerald-50 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-11 h-11 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                                </div>
                                <h4 class="text-slate-400 text-[11px] font-semibold mb-0.5">Keranjang</h4>
                                <div class="text-2xl font-bold text-slate-900 tracking-tight" x-text="$store.cart.items.length > 0 ? $store.cart.items.length : '0'"></div>
                                <p class="text-[11px] text-slate-400 font-bold mt-1">produk siap checkout</p>
                            </div>
                        </div>

                        {{-- Orders Card --}}
                        <a href="{{ route('transactions.index') }}" class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-slate-100/80 group transition-all duration-500 hover:shadow-emerald-500/10 hover:-translate-y-1 block flex flex-col justify-between h-full">
                            <div class="absolute top-0 left-6 right-6 h-1 bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-b-full"></div>
                            <div class="absolute -right-3 -top-3 w-20 h-20 bg-emerald-50 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-11 h-11 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </div>
                                <h4 class="text-slate-400 text-[11px] font-semibold mb-0.5">Pesanan</h4>
                                <div class="text-2xl font-bold text-slate-900 tracking-tight">{{ $userTransactionsCount }}</div>
                                <p class="text-[11px] text-slate-400 font-bold mt-1">transaksi tercatat</p>
                            </div>
                        </a>

                        {{-- Wishlist Card --}}
                        <a href="{{ route('wishlist.index') }}" class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-slate-100/80 group transition-all duration-500 hover:shadow-emerald-500/10 hover:-translate-y-1 block flex flex-col justify-between h-full">
                            <div class="absolute top-0 left-6 right-6 h-1 bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-b-full"></div>
                            <div class="absolute -right-3 -top-3 w-20 h-20 bg-emerald-50 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-11 h-11 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </div>
                                <h4 class="text-slate-400 text-[11px] font-semibold mb-0.5">Wishlist</h4>
                                <div class="text-2xl font-bold text-slate-900 tracking-tight">{{ $userFavoritesCount }}</div>
                                <p class="text-[11px] text-slate-400 font-bold mt-1">produk disukai</p>
                            </div>
                        </a>

                        {{-- Reviews Card --}}
                        <a href="{{ route('reviews.index') }}" class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-slate-100/80 group transition-all duration-500 hover:shadow-emerald-500/10 hover:-translate-y-1 block flex flex-col justify-between h-full">
                            <div class="absolute top-0 left-6 right-6 h-1 bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-b-full"></div>
                            <div class="absolute -right-3 -top-3 w-20 h-20 bg-emerald-50 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-11 h-11 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </div>
                                <h4 class="text-slate-400 text-[11px] font-semibold mb-0.5">Ulasan</h4>
                                <div class="text-2xl font-bold text-slate-900 tracking-tight">{{ $userReviewsCount }}</div>
                                <p class="text-[11px] text-slate-400 font-bold mt-1">ulasan diberikan</p>
                            </div>
                        </a>
                    </div>

                    {{-- Quick Action Row - Premium --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <a href="{{ route('checkout.index') }}" class="flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-100 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300 group">
                            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                            <div class="flex-1">
                                <h5 class="text-xs font-semibold text-slate-900">Checkout</h5>
                                <p class="text-xs text-slate-400">Selesaikan pembayaran</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="{{ route('transactions.index') }}" class="flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-100 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300 group">
                            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                </div>
                            <div class="flex-1">
                                <h5 class="text-xs font-semibold text-slate-900">Lacak Pesanan</h5>
                                <p class="text-xs text-slate-400">Cek status paketmu</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-100 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300 group">
                            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                            <div class="flex-1">
                                <h5 class="text-xs font-semibold text-slate-900">Edit Profil</h5>
                                <p class="text-xs text-slate-400">Kelola akunmu</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="{{ route('shop.public') }}" class="flex items-center gap-4 p-5 bg-emerald-50 rounded-2xl border border-emerald-100 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300 group">
                            <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white group-hover:scale-105 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <div class="flex-1">
                                <h5 class="text-xs font-semibold text-slate-900">Jelajahi Produk</h5>
                                <p class="text-xs text-emerald-600">Temukan barang impianmu</p>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Right Sidebar - Bolo Activity --}}
                <div class="col-span-1 lg:col-span-1 space-y-6">
                    {{-- Address & Status Card --}}
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm group transition-all hover:shadow-lg">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 {{ $profileIncomplete ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100' }} rounded-xl flex items-center justify-center border shadow-sm transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Lokasi Pengiriman</span>
                        </div>

                        <div class="space-y-4">
                            @if($profileIncomplete)
                                <div class="p-3 bg-amber-50 rounded-xl border border-amber-100 italic">
                                    <p class="text-[10px] font-bold text-amber-700 leading-relaxed">
                                        Waduh bolo! Alamat atau titik maps kamu belum lengkap. Nanti kurir bingung nganternya.
                                    </p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="block w-full py-3 bg-amber-500 text-white text-center rounded-xl text-[10px] font-bold hover:bg-amber-600 transition-all active:scale-95 shadow-lg shadow-amber-100 uppercase tracking-widest">
                                    Lengkapi Maps
                                </a>
                            @else
                                <div class="space-y-2">
                                    <p class="text-[11px] font-bold text-slate-600 truncate">{{ Auth::user()->alamat }}</p>
                                    <div class="flex items-center gap-1">
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                        <span class="text-[10px] font-bold text-emerald-600">Maps Terpasang</span>
                                    </div>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="block w-full py-3 bg-slate-50 text-slate-600 text-center rounded-xl text-[10px] font-bold hover:bg-slate-100 transition-all border border-slate-200 uppercase tracking-widest">
                                    Ubah Alamat
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Help & Support Bolo --}}
                    <div class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-xl shadow-slate-200 group border border-slate-100">
                        <div class="absolute -right-8 -top-8 w-24 h-24 bg-slate-50 rounded-full blur-2xl group-hover:bg-slate-100 transition-all"></div>
                        <div class="relative z-10">
                            <h4 class="text-slate-900 font-bold text-sm mb-4">Butuh Bantuan, Bolo?</h4>
                            <div class="space-y-3">
                                @php $admin = \App\Models\User::role('admin')->first(); @endphp
                                <a href="{{ $admin ? route('chat.start', ['seller' => $admin->id]) : route('chat.index') }}" class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200 hover:bg-slate-100 transition-all">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    </div>
                                    <div class="text-[10px] font-bold text-slate-700">Tanya Admin</div>
                                </a>
                                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                                    <div class="w-8 h-8 rounded-lg bg-blue-500/20 text-blue-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div class="text-[10px] font-bold text-slate-700 italic">Panduan Belanja</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Analytics & Recent Activity --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('seller') || Auth::user()->hasRole('courier'))
                {{-- Role-Aware Activity Monitor --}}
                <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)]">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h4 class="font-bold text-slate-900 text-lg">
                                @if(Auth::user()->hasRole('admin')) System Live Monitor 
                                @elseif(Auth::user()->hasRole('seller')) Penjualan Terbaru 
                                @else Tugas Antar Terbaru @endif
                            </h4>
                            <p class="text-xs font-bold text-slate-400 mt-1">
                                @if(Auth::user()->hasRole('admin')) Pantau kesehatan transaksi platform bolo 
                                @elseif(Auth::user()->hasRole('seller')) Pantau pesanan masuk ke tokomu bolo 
                                @else Pantau paket yang harus kamu kirim bolo @endif
                            </p>
                        </div>
                        <span class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl text-[11px] font-semibold border border-emerald-100">Live Status</span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-3">
                            <thead>
                                <tr class="text-slate-400 text-xs font-semibold">
                                    <th class="px-4 py-2">Invoice</th>
                                    <th class="px-4 py-2">Status</th>
                                    <th class="px-4 py-2">Nominal</th>
                                    <th class="px-4 py-2 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salesTransactions as $tx)
                                <tr class="bg-slate-50/50 hover:bg-white transition-all group">
                                    <td class="px-4 py-4 rounded-l-2xl border-y border-l border-transparent group-hover:border-slate-100">
                                        <span class="text-xs font-bold text-slate-900 uppercase">#{{ strtoupper(substr($tx->invoice_number ?? $tx->id, -8)) }}</span>
                                    </td>
                                    <td class="px-4 py-4 border-y border-transparent group-hover:border-slate-100">
                                        @php
                                            $stColor = match($tx->status) {
                                                'paid', 'success' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                'shipped' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                default => 'bg-slate-50 text-slate-500 border-slate-200'
                                            };
                                        @endphp
                                        <span class="px-3 py-1 border {{ $stColor }} text-[10px] font-bold rounded-lg uppercase transition-all group-hover:scale-105 inline-block">{{ str_replace('_', ' ', $tx->status) }}</span>
                                    </td>
                                    <td class="px-4 py-4 border-y border-transparent group-hover:border-slate-100">
                                        <span class="text-xs font-semibold text-emerald-600">RP {{ number_format($tx->total_price, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-4 py-4 rounded-r-2xl border-y border-r border-transparent group-hover:border-slate-100 text-right">
                                        <a href="{{ Auth::user()->hasRole('courier') ? route('courier.deliveries.show', $tx->id) : route('admin.transactions.show', $tx->id) }}" class="p-2 bg-white text-slate-400 hover:text-emerald-600 hover:shadow-lg transition-all inline-block rounded-xl border border-slate-100 active:scale-95">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-10 text-xs font-bold text-slate-300 uppercase">Belum ada aktifitas hari ini</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('seller'))
                <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)]">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h4 class="font-bold text-slate-900 text-lg">Analis Penjualan</h4>
                            <p class="text-xs font-bold text-slate-400 mt-1">Garis pendapatan 7 hari terakhir</p>
                        </div>
                        <div class="flex gap-2">
                             <a href="{{ route('dashboard', ['export' => 'excel']) }}" class="text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-4 py-2 rounded-xl hover:bg-emerald-600 hover:text-white transition-all">Export Excel</a>
                        </div>
                    </div>
                    @if($revenueTimeline->sum('total') > 0)
                        <div id="revenueChart" class="min-h-[350px]"></div>
                    @else
                        <div class="min-h-[350px] flex flex-col items-center justify-center bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                             <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-slate-300 shadow-sm mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                             </div>
                             <p class="text-xs font-medium text-slate-400">Belum Ada Data Penjualan</p>
                        </div>
                    @endif
                </div>
                @endif

                <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] overflow-hidden transition-all hover:shadow-[0_20px_50px_rgba(0,0,0,0.04)]">
                    <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-white/50 backdrop-blur-md sticky top-0 z-10">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h4 class="font-bold text-slate-900 text-lg">Personal Order History</h4>
                        </div>
                        @if($recentTransactions->count() > 0)
                            <a href="{{ route('transactions.index') }}" class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-5 py-2.5 rounded-2xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm">Lihat Semua</a>
                        @endif
                    </div>
                    
                    @if($recentTransactions->count() > 0)
                    <div class="p-4 sm:p-8 space-y-4">
                        @foreach($recentTransactions as $tx)
                            <div class="group relative bg-slate-50/50 hover:bg-white p-5 rounded-2xl border border-transparent hover:border-slate-100 hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500 overflow-hidden">
                                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="relative">
                                            <div class="w-14 h-14 rounded-2xl bg-emerald-100 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform duration-500 overflow-hidden shadow-inner">
                                                @php $txUser = $tx->user; @endphp
                                                @if($txUser && $txUser->avatar)
                                                    <img src="{{ asset($txUser->avatar) }}" class="w-full h-full object-cover">
                                                @else
                                                    <svg class="w-7 h-7 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                @endif
                                            </div>
                                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-white rounded-lg shadow-sm flex items-center justify-center border border-slate-50">
                                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <h5 class="text-xs font-semibold text-slate-900">{{ $txUser->name ?? 'Guest User' }}</h5>
                                                <span class="text-xs font-bold px-2 py-0.5 bg-white border border-slate-100 text-slate-400 rounded-full shadow-sm">#{{ strtoupper(substr($tx->invoice_number ?? $tx->id, -8)) }}</span>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <span class="text-[11px] font-bold text-slate-400">{{ $tx->created_at->diffForHumans() }}</span>
                                                <span class="w-1.5 h-1.5 bg-slate-200 rounded-full"></span>
                                                <span class="text-[11px] font-semibold text-emerald-600">RP {{ number_format($tx->total_price, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        @php
                                            // Unified Status Logic Bolo!
                                            $clientStatusLabel = match($tx->status) {
                                                'pending', 'waiting_confirmation' => 'MENUNGGU KONFIRMASI',
                                                'paid', 'success' => 'PEMBAYARAN BERHASIL',
                                                'shipped' => 'SEDANG DIKIRIM',
                                                'delivered' => 'SAMPAI DI TUJUAN',
                                                'completed' => 'PESANAN SELESAI',
                                                'failed' => 'TRANSAKSI GAGAL',
                                                'canceled' => 'PESANAN DIBATALKAN',
                                                default => strtoupper(str_replace('_', ' ', $tx->status))
                                            };
                                            $clientStatusColor = match($tx->status) {
                                                'pending', 'waiting_confirmation' => 'text-amber-600 bg-amber-50 border-amber-100',
                                                'paid', 'success' => 'text-emerald-600 bg-emerald-50 border-emerald-100',
                                                'shipped', 'delivered' => 'text-blue-600 bg-blue-50 border-blue-100',
                                                'completed' => 'text-emerald-600 bg-emerald-50 border-emerald-100',
                                                'failed', 'canceled' => 'text-rose-600 bg-rose-50 border-rose-100',
                                                default => 'text-slate-500 bg-slate-50 border-slate-100'
                                            };
                                            $iconStatus = match($tx->status) {
                                                'paid', 'success' => '<svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM5 12a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/></svg>',
                                                'shipped', 'delivered' => '<svg class="w-2.5 h-2.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>',
                                                'completed' => '<svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>',
                                                default => ''
                                            };
                                        @endphp
                                        <div class="inline-flex items-center px-4 py-2 rounded-xl border {{ $clientStatusColor }} text-xs font-semibold uppercase tracking-[0.15em] shadow-sm transition-all duration-300 group-hover:scale-105">
                                            {!! $iconStatus !!}
                                            {{ $clientStatusLabel }}
                                        </div>
                                        <a href="{{ route('transactions.show', $tx->id) }}" class="w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-emerald-600 hover:border-emerald-100 hover:shadow-lg transition-all active:scale-95">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="absolute left-0 top-0 w-1.5 h-full bg-emerald-600 -translate-x-full group-hover:translate-x-0 transition-transform duration-500"></div>
                            </div>
                        @endforeach

                        <div class="mt-8 px-4">
                            {{ $recentTransactions->links() }}
                        </div>
                    </div>
                    @else
                    <div class="p-12 text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-6 text-slate-200">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <h5 class="text-sm font-bold text-slate-800 mb-2">Belum Ada Jejak Belanja</h5>
                        <p class="text-[11px] text-slate-400 font-medium max-w-[200px] mx-auto mb-8">Sepertinya kamu belum melakukan transaksi apapun bolo. Ayo cari barang impianmu!</p>
                        <a href="{{ route('shop.public') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-2xl text-xs font-semibold hover:bg-slate-900 transition-all shadow-lg shadow-emerald-100">
                            Mulai Belanja 
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <div class="space-y-8">
                {{-- ===== SELLER STORE PERFORMANCE CARD ===== --}}
                @if(Auth::user()->hasRole('seller'))
                <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 blur-3xl rounded-full -mr-16 -mt-16"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-6 border-b border-slate-50 pb-4">
                            <h5 class="font-bold text-slate-900 text-lg flex items-center gap-2">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                                Performa Toko
                            </h5>
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-xl text-xs font-semibold border border-emerald-100">Live</span>
                        </div>

                        {{-- Rating --}}
                        <div class="flex items-center justify-between p-4 bg-slate-50/50 rounded-2xl mb-3 group hover:bg-white hover:shadow-sm transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </div>
                                <div>
                                    <p class="text-[11px] font-medium text-slate-400">Rating Toko</p>
                                    <p class="text-lg font-bold text-slate-900 -mt-0.5">{{ number_format($storeRating, 1) }}<span class="text-xs text-slate-400 font-bold">/5.0</span></p>
                                </div>
                            </div>
                            <div class="flex gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-3.5 h-3.5 {{ $i <= round($storeRating) ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                        </div>

                        {{-- Total Revenue --}}
                        <div class="flex items-center justify-between p-4 bg-slate-50/50 rounded-2xl mb-3 group hover:bg-white hover:shadow-sm transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-[11px] font-medium text-slate-400">Total Pendapatan</p>
                                    <p class="text-sm font-semibold text-emerald-600 -mt-0.5">RP {{ number_format($totalEarnings, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Total Items --}}
                        <div class="flex items-center justify-between p-4 bg-slate-50/50 rounded-2xl mb-3 group hover:bg-white hover:shadow-sm transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                                <div>
                                    <p class="text-[11px] font-medium text-slate-400">Produk Aktif</p>
                                    <p class="text-lg font-bold text-slate-900 -mt-0.5">{{ $sellerTotalItems }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Total Reviews --}}
                        <div class="flex items-center justify-between p-4 bg-slate-50/50 rounded-2xl group hover:bg-white hover:shadow-sm transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-rose-50 rounded-xl flex items-center justify-center text-rose-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                </div>
                                <div>
                                    <p class="text-[11px] font-medium text-slate-400">Total Ulasan</p>
                                    <p class="text-lg font-bold text-slate-900 -mt-0.5">{{ $totalReviews }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)]">
                    <h5 class="font-bold text-slate-900 text-lg mb-6 border-b border-slate-50 pb-4"> Lagi Hot Banget Loh Rek</h5>
                    <div class="space-y-4">
                        @forelse($bestSellers->take(5) as $item)
                        <a href="{{ route('shop.show', $item->id) }}" class="flex items-center gap-4 group cursor-pointer hover:bg-slate-50 p-2 rounded-2xl transition-all">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden shrink-0 border border-white shadow-sm">
                                <img src="{{ Str::startsWith($item->gambar, 'image/') ? asset($item->gambar) : asset('storage/' . $item->gambar) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[11px] font-bold text-slate-800 truncate">{{ $item->nama_barang }}</p>
                                <p class="text-xs text-emerald-500 font-bold">{{ $item->total_terjual ?? 0 }} Terjual</p>
                            </div>
                        </a>
                        @empty
                        <div class="py-10 text-center">
                            <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <p class="text-xs font-medium text-slate-400">Toko Belum Buka Bolo</p>
                        </div>
                        @endforelse
                    </div>
                    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('seller'))
                    <a href="{{ route('item-shop.index') }}" class="mt-6 block text-center py-3 bg-slate-900 text-white rounded-2xl text-xs font-semibold shadow-lg hover:bg-emerald-600 transition-all">Kelola Barang</a>
                    @endif
                </div>

                @if(Auth::user()->hasRole('admin'))
                <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 blur-3xl rounded-full -mr-16 -mt-16"></div>
                    <div class="relative z-10">
                        <h4 class="font-bold text-slate-900 text-lg tracking-tight uppercase mb-6 flex items-center gap-2">
                             <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                             Broadcast CMS
                        </h4>
                        <form action="{{ route('notifications.broadcast') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="text" name="title" placeholder="Judul Pengumuman" required class="w-full bg-slate-50/50 border-slate-100 rounded-2xl text-xs font-bold text-slate-800 placeholder:text-slate-400 focus:ring-emerald-500 focus:border-emerald-500 py-3 px-5 transition-all">
                            <textarea name="message" rows="3" placeholder="Tulis pesan pengumuman untuk seluruh bolo..." required class="w-full bg-slate-50/50 border-slate-100 rounded-2xl text-[11px] font-medium text-slate-600 placeholder:text-slate-400 focus:ring-emerald-500 focus:border-emerald-500 py-4 px-5 transition-all"></textarea>
                            <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-slate-900 text-white rounded-2xl text-xs font-semibold shadow-lg shadow-emerald-100 transition-all flex items-center justify-center gap-3 group/btn">
                                Sebarkan Pengumuman
                                <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                @if($notifications->count() > 0 || (!Auth::user()->hasRole('seller') && !Auth::user()->hasRole('admin')))
                <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] relative overflow-hidden group transition-all duration-500 hover:shadow-emerald-500/10">
                    <div class="relative z-10 h-full flex flex-col">
                        <div class="flex items-center justify-between mb-8">
                            <h4 class="font-bold text-slate-900 text-lg tracking-tight uppercase">Pemberitahuan</h4>
                            <div class="flex items-center gap-3">
                                @if($notifications->count() > 0)
                                <button type="button" onclick="deleteAllNotifications()" class="text-[11px] font-bold text-rose-500 hover:text-rose-700 transition-colors">Hapus Semua</button>
                                @endif
                                <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600 shadow-sm border border-emerald-100/50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4 max-h-[300px] overflow-y-auto no-scrollbar pb-4" id="notifications-list">
                            @forelse($notifications as $n)
                                <div onclick="markNotificationRead({{ $n->id }}, this, event)" class="cursor-pointer p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:bg-white hover:shadow-lg transition-all group/item relative {{ $n->is_read ? 'opacity-50' : '' }}">
                                    {{-- Delete Record --}}
                                    <button onclick="deleteNotification({{ $n->id }}, this, event)" class="absolute top-4 right-4 opacity-0 group-hover/item:opacity-100 transition-all duration-200 p-1.5 bg-white border border-slate-200 text-slate-400 hover:text-rose-500 hover:border-rose-100 rounded-lg shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                    <p class="text-xs font-bold text-slate-900 mb-1 pr-8">{{ $n->title }}</p>
                                    <p class="text-[11px] text-slate-500 leading-relaxed font-bold">{{ $n->message }}</p>
                                    <p class="text-xs font-semibold text-emerald-600 mt-2 text-right">{{ $n->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                {{-- If no notifications and is Buyer (Bolo) --}}
                                @if(!Auth::user()->hasRole('seller') && !Auth::user()->hasRole('admin'))
                                <div class="text-center py-6">
                                    <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4 text-emerald-600 shadow-inner">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                    </div>
                                    <p class="text-[12px] font-bold text-slate-900 mb-2">Ingin Mulai Berjualan?</p>
                                    <p class="text-xs text-slate-500 font-bold mb-6 leading-relaxed">Buka tokomu sekarang dan raih cuan bareng MiniQ bolo!</p>
                                    <a href="{{ route('seller.create') }}" class="inline-block px-8 py-3 bg-emerald-600 text-white rounded-xl text-xs font-semibold hover:bg-slate-900 transition-all shadow-lg shadow-emerald-100">Daftar Seller</a>
                                </div>
                                @else
                                <p class="text-xs font-bold text-slate-300 text-center py-10">Semua aman terkendali!</p>
                                @endif
                            @endforelse
                        </div>
                    </div>
                </div>
                @endif
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
                colors: ['#10b981'],
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

        function simulateTopUp() {
            Swal.fire({
                title: '<span class="text-xl font-bold font-plus-jakarta">Top Up <span class="text-emerald-600">Saldo Bolo</span></span>',
                html: `
                    <div class="mt-4 space-y-4">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em]">Pilih Nominal</p>
                        <div class="grid grid-cols-2 gap-3">
                            <button onclick="confirmTopUp(50000)" class="p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl hover:border-emerald-500 hover:bg-emerald-50 transition-all group">
                                <p class="text-[10px] font-bold text-slate-400 group-hover:text-emerald-600">Rp</p>
                                <p class="text-lg font-bold text-slate-800 group-hover:text-emerald-600">50.000</p>
                            </button>
                            <button onclick="confirmTopUp(100000)" class="p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl hover:border-emerald-500 hover:bg-emerald-50 transition-all group">
                                <p class="text-[10px] font-bold text-slate-400 group-hover:text-emerald-600">Rp</p>
                                <p class="text-lg font-bold text-slate-800 group-hover:text-emerald-600">100.000</p>
                            </button>
                            <button onclick="confirmTopUp(250000)" class="p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl hover:border-emerald-500 hover:bg-emerald-50 transition-all group">
                                <p class="text-[10px] font-bold text-slate-400 group-hover:text-emerald-600">Rp</p>
                                <p class="text-lg font-bold text-slate-800 group-hover:text-emerald-600">250.000</p>
                            </button>
                            <button onclick="confirmTopUp(500000)" class="p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl hover:border-emerald-500 hover:bg-emerald-50 transition-all group">
                                <p class="text-[10px] font-bold text-slate-400 group-hover:text-emerald-600">Rp</p>
                                <p class="text-lg font-bold text-slate-800 group-hover:text-emerald-600">500.000</p>
                            </button>
                        </div>
                    </div>
                `,
                showConfirmButton: false,
                showCloseButton: true,
                customClass: {
                    popup: 'rounded-3xl border-none shadow-2xl',
                }
            });
        }

        function confirmTopUp(amount) {
            Swal.fire({
                title: 'Konfirmasi Top Up',
                text: `Kamu akan melakukan top up sebesar Rp ${new Intl.NumberFormat('id-ID').format(amount)}. Lanjutkan bolo?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'IYA, GAS BOLO!',
                confirmButtonColor: '#10b981',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-2xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Permintaan Terkirim!',
                        text: 'Saldo kamu akan segera masuk setelah dikonfirmasi sistem.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                        customClass: { popup: 'rounded-2xl' }
                    });
                }
            });
        }
    </script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @endpush
</x-admin-layout>

