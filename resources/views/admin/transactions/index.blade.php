<x-admin-layout>
    <div class="max-w-7xl mx-auto">
        {{-- Transitioning Header to Tokopedia Style --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10" data-aos="fade-right">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tighter uppercase">Pesanan <span class="text-emerald-500">Masuk</span></h2>
                    <p class="text-xs font-bold text-gray-400 mt-1">Kelola dan pantau pesanan yang masuk ke toko Anda</p>
                </div>

                {{-- Horizontal Status Chips (Tokopedia Style) --}}
                <div class="flex items-center gap-2 overflow-x-auto pb-4 md:pb-0 scrollbar-hide">
                    @php 
                        $currentStatus = request('status', 'Semua'); 
                        $statusOptions = [
                            'Semua' => 'Semua',
                            'Berlangsung' => 'pending',
                            'Dikirim' => 'shipped',
                            'Berhasil' => 'success',
                            'Selesai' => 'completed',
                        ];
                    @endphp
                    @foreach($statusOptions as $label => $slug)
                        <a href="{{ $label == 'Semua' ? route('admin.transactions.index') : route('admin.transactions.index', ['status' => $slug]) }}" 
                           class="whitespace-nowrap px-6 py-2.5 rounded-full text-xs font-semibold transition-all duration-300
                           {{ ($currentStatus == $slug || ($currentStatus == 'Semua' && $label == 'Semua')) 
                              ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-100' 
                              : 'bg-white text-gray-500 border border-gray-100 hover:border-emerald-200 hover:bg-emerald-50' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6">
                {{-- Search & Date Range --}}
                <form action="{{ route('admin.transactions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <div class="md:col-span-2 relative group">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor invoice atau nama produk..." class="w-full bg-white border-none rounded-2xl py-4 pl-14 pr-6 text-xs font-bold text-gray-600 shadow-sm focus:ring-4 focus:ring-emerald-500/10 transition-all">
                        <svg class="w-5 h-5 text-gray-300 absolute left-6 top-1/2 -translate-y-1/2 group-focus-within:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <div class="relative">
                        <select name="date_filter" class="w-full bg-white border-none rounded-2xl py-4 px-6 text-xs font-bold text-gray-600 shadow-sm focus:ring-4 focus:ring-emerald-500/10 appearance-none">
                            <option value="">Semua Tanggal</option>
                            <option value="30" {{ request('date_filter') == '30' ? 'selected' : '' }}>30 Hari Terakhir</option>
                            <option value="90" {{ request('date_filter') == '90' ? 'selected' : '' }}>90 Hari Terakhir</option>
                        </select>
                        <svg class="w-4 h-4 text-gray-300 absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    <button type="submit" class="hidden"></button>
                </form>

                @forelse($transactions as $trx)
                    @php
                        // Unified Status Logic Bolo!
                        $clientStatusLabel = match($trx->status) {
                            'pending', 'waiting_confirmation' => 'MENUNGGU KONFIRMASI',
                            'paid', 'success' => 'PEMBAYARAN BERHASIL',
                            'shipped' => 'SEDANG DIKIRIM',
                            'delivered' => 'SAMPAI DI TUJUAN',
                            'completed' => 'PESANAN SELESAI',
                            'failed' => 'TRANSAKSI GAGAL',
                            'canceled' => 'PESANAN DIBATALKAN',
                            default => strtoupper(str_replace('_', ' ', $trx->status))
                        };
                        $clientStatusColor = match($trx->status) {
                            'pending', 'waiting_confirmation' => 'text-amber-600 bg-amber-50 border-amber-100',
                            'paid', 'success' => 'text-green-600 bg-green-50 border-green-100',
                            'shipped', 'delivered' => 'text-blue-600 bg-blue-50 border-blue-100',
                            'completed' => 'text-green-600 bg-green-50 border-green-100',
                            'failed', 'canceled' => 'text-rose-600 bg-rose-50 border-rose-100',
                            default => 'text-slate-500 bg-slate-50 border-slate-100'
                        };
                        
                        $items = $trx->items_details;
                        $firstItem = collect($items)->first();
                        $otherCount = count($items) - 1;
                        $buyer = $trx->user;
                        
                        // Check if shipping is 3rd party or local
                        $isThirdParty = in_array(strtoupper($trx->courier_name), ['JNE', 'SICEPAT', 'J&T', 'POS', 'TIKI', 'ANTARAJA']);
                        $isLocal = !$isThirdParty || strtoupper($trx->courier_name) == 'LOCAL';
                        
                        // Filter couriers based on transaction's courier_service_id
                        $matchedCouriers = $couriers->where('courier_agency_id', $trx->courier_service_id);
                    @endphp

                    <div x-data="{ open: false }" 
                         class="bg-white rounded-2xl p-6 md:p-8 shadow-sm hover:shadow-xl hover:shadow-emerald-500/5 border border-transparent hover:border-emerald-100 transition-all group relative"
                         :class="open ? 'z-[100]' : 'z-10'">
                        {{-- Top Metadata --}}
                        <div class="flex flex-wrap items-center justify-between gap-4 mb-6 pb-6 border-b border-gray-50">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500 shadow-inner">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                </div>
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                    <span class="text-xs font-bold text-gray-900 uppercase">Pesanan</span>
                                    <span class="text-xs font-bold text-gray-400">{{ $trx->created_at->translatedFormat('d M Y') }} � {{ $trx->created_at->format('H:i') }} WIB</span>
                                    <span class="px-3 py-1 rounded-lg {{ $clientStatusColor }} text-[11px] font-semibold border border-current/10">
                                        {{ $clientStatusLabel }}
                                    </span>
                                    <span class="text-xs font-bold text-gray-300">{{ $trx->invoice_number }}</span>
                                </div>
                            </div>
                            
                            {{-- Buyer Badge --}}
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 rounded-full border border-gray-100">
                                <span class="text-[11px] font-medium text-gray-400">Pembeli:</span>
                                <span class="text-xs font-bold text-gray-800 tracking-tight uppercase">{{ $buyer->name ?? 'User Bolo' }}</span>
                            </div>
                        </div>

                        {{-- Product Content --}}
                        <div class="flex flex-col md:flex-row gap-8 items-start md:items-center">
                            <div class="flex-1 flex gap-6">
                                <div class="w-16 h-16 md:w-20 md:h-20 bg-slate-100 rounded-2xl border border-white shadow-inner overflow-hidden shrink-0 group-hover:scale-110 transition-transform duration-500">
                                    <img src="{{ asset($firstItem['gambar'] ?? 'image/default-product.jpg') }}" class="w-full h-full object-cover">
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm md:text-base font-bold text-gray-900 line-clamp-1 group-hover:text-emerald-500 transition-colors">
                                        {{ $firstItem['nama_barang'] ?? ($firstItem['name'] ?? 'Produk Bolo') }}
                                    </h3>
                                    <p class="text-xs font-bold text-gray-400 mt-1">
                                        {{ $firstItem['quantity'] ?? 1 }} Barang x Rp{{ number_format($firstItem['harga'] ?? 0, 0, ',', '.') }}
                                    </p>
                                    @if($otherCount > 0)
                                        <p class="text-[11px] font-semibold text-emerald-600 mt-3 inline-block bg-emerald-50 px-2 py-1 rounded-md border border-emerald-100/50">
                                            +{{ $otherCount }} Produk Lainnya
                                        </p>
                                    @endif

                                    {{-- User Shipping Choice --}}
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <div class="px-3 py-1 bg-slate-50 border border-slate-100 rounded-lg flex items-center gap-2">
                                            <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V14a1 1 0 01-1 1h-1m-1 1a1 1 0 102 0v-5m-7 0h.01"></path></svg>
                                            <span class="text-[11px] font-bold text-gray-600">METODE: <span class="text-emerald-600 font-bold">{{ $trx->courier_name ?? 'LOCAL' }}</span></span>
                                        </div>
                                        <div class="px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-lg flex items-center gap-2 shadow-sm">
                                            <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span class="text-[11px] font-bold text-emerald-700">{{ $trx->courier_service ?? 'STANDARD' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Price & Total Column --}}
                            <div class="flex flex-col items-end md:pl-10 md:border-l border-gray-50 min-w-[150px]">
                                <span class="text-[11px] font-medium text-gray-400 mb-1">Total Pendapatan</span>
                                <span class="text-xl font-bold text-gray-900 tracking-tighter">Rp{{ number_format($trx->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-8 pt-6 border-t border-gray-50 flex flex-wrap items-center justify-between gap-4">
                            <a href="{{ route('admin.transactions.show', $trx->id) }}" class="text-xs font-bold text-emerald-500 hover:text-emerald-700 flex items-center gap-2 transition-all">
                                <span>Lihat Detail Pesanan</span>
                                <svg class="w-3 h-3 translate-y-[-1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </a>

                            <div class="flex items-center gap-3">
                                @if($trx->status === 'waiting_confirmation')
                                    <form action="{{ route('admin.transactions.confirm-cod', $trx->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-6 py-3 bg-orange-500 text-white rounded-xl text-xs font-semibold hover:bg-slate-900 transition-all shadow-lg flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            Konfirmasi Pesanan COD
                                        </button>
                                    </form>
                                @endif

                                @if(in_array($trx->status, ['success', 'paid']))
                                    {{-- Seller Action: Handle Shipping based on Choice --}}
                                    <div class="relative">
                                        <button @click="open = !open" 
                                                class="px-6 py-3 {{ $isThirdParty ? 'bg-emerald-600' : 'bg-slate-900' }} text-white rounded-xl text-xs font-semibold hover:scale-105 transition-all shadow-lg flex items-center gap-2">
                                            <span>Atur Pengiriman</span>
                                            <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                        
                                        <div x-show="open" 
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                             @click.away="open = false" 
                                             class="absolute right-0 top-full mt-3 w-80 bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-slate-50 p-6 z-[110]">
                                            
                                            @if($isThirdParty)
                                                {{-- Logic for Ekspredisi (JNE, etc) --}}
                                                <div class="mb-5 border-b border-gray-50 pb-4">
                                                    <h5 class="text-[11px] font-bold text-gray-900">Konfirmasi <span class="text-emerald-600">Ekspedisi</span></h5>
                                                    <p class="text-[11px] font-bold text-gray-400 mt-1">User memilih: {{ $trx->courier_name }} ({{ $trx->courier_service }})</p>
                                                </div>
                                                
                                                <form action="{{ route('admin.transactions.resi', $trx->id) }}" method="POST" class="space-y-4">
                                                    @csrf
                                                    <div class="space-y-2">
                                                        <label class="text-[11px] font-medium text-gray-400 ml-1">Input No. Resi {{ $trx->courier_name }}</label>
                                                        <input type="text" name="resi" placeholder="CONTOH: JNE123456789" required class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-xs font-semibold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 transition-all">
                                                    </div>
                                                    <button type="submit" class="w-full py-4 bg-emerald-600 text-white rounded-2xl text-xs font-semibold shadow-xl shadow-emerald-100 hover:bg-slate-900 transition-all">Update No. Resi</button>
                                                </form>

                                                <div class="mt-6 p-4 bg-amber-50 rounded-2xl border border-amber-100/50">
                                                    <p class="text-xs font-bold text-amber-700 uppercase leading-relaxed tracking-tight">
                                                        Silahkan drop paket ke agen <strong>{{ $trx->courier_name }}</strong> terdekat dan masukkan nomor resi yang didapat ke kolom di atas.
                                                    </p>
                                                </div>
                                            @else
                                                {{-- Logic for Local Delivery --}}
                                                <div class="mb-5 border-b border-gray-50 pb-4">
                                                    <h5 class="text-[11px] font-bold text-gray-900">Kurir <span class="text-emerald-600">Terpilih</span></h5>
                                                    <p class="text-[11px] font-bold text-gray-400 mt-1">Layanan: {{ $trx->courier_name }} {{ $trx->courier_service }}</p>
                                                </div>
                                                
                                                @if($matchedCouriers->count() > 0)
                                                    <form action="{{ route('admin.transactions.assign', $trx->id) }}" method="POST" class="space-y-4">
                                                        @csrf
                                                        <div class="relative">
                                                            <select name="courier_id" required class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-xs font-semibold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 transition-all appearance-none cursor-pointer hover:bg-slate-100">
                                                                <option value="">-- PILIH PERSONEL --</option>
                                                                @foreach($matchedCouriers as $courier)
                                                                    <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <svg class="w-4 h-4 text-gray-400 absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                                                        </div>
                                                        <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-2xl text-xs font-semibold hover:bg-emerald-600 transition-all">Mulai Pengantaran</button>
                                                    </form>
                                                @else
                                                    <div class="p-4 bg-red-50 rounded-2xl border border-red-100">
                                                        <p class="text-[11px] font-bold text-red-600 text-center">
                                                            MAAF BOLO, TIDAK ADA DRIVER AKTIF UNTUK LAYANAN INI.
                                                        </p>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if($trx->resi)
                                    <div class="px-4 py-3 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center gap-3">
                                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                        <span class="text-[11px] font-bold text-emerald-700 line-clamp-1">Resi: {{ $trx->resi }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Progress Bar Decor --}}
                        @if(in_array($trx->status, ['pending', 'paid', 'success', 'shipped']))
                        <div class="absolute bottom-0 left-0 w-full h-[2px] bg-gray-50 rounded-b-[2rem] overflow-hidden">
                            <div class="h-full bg-green-500 {{ $trx->status == 'shipped' ? 'w-2/3' : 'w-1/3' }} animate-pulse"></div>
                        </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-24 bg-white rounded-2xl border border-gray-100 shadow-sm">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 uppercase">Belum Ada Pesanan, Bolo!</h3>
                        <p class="text-xs text-gray-400 font-bold mt-2">Semua pesanan yang masuk ke tokomu akan muncul di sini.</p>
                        <a href="{{ route('dashboard') }}" class="inline-block mt-8 px-10 py-4 bg-emerald-600 text-white rounded-2xl text-xs font-semibold shadow-xl shadow-emerald-100 hover:-translate-y-1 transition-all">Kembali ke Dashboard</a>
                    </div>
                @endforelse

                <div class="pb-20">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
</x-admin-layout>

