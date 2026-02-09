<x-app-layout>
    {{-- CSS Leaflet & Custom Styling --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        #leaflet-map { z-index: 1; border: 2px solid #F1F5F9; }
        .qty-btn { @apply w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 hover:bg-emerald-50 hover:border-emerald-200 transition-all text-gray-600 font-bold; }
        
        /* Google Autocomplete Styling */
        .pac-container {
            border-radius: 1.5rem;
            border: none;
            margin-top: 10px;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            font-family: inherit;
            padding: 10px 0;
            z-index: 9999 !important;
        }
        .pac-item {
            padding: 12px 20px;
            cursor: pointer;
            border-top: 1px solid #f8fafc;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .pac-item:first-child { border-top: none; }
        .pac-item:hover { background-color: #f1f5f9; }
        .pac-icon { display: none; } /* Sembunyikan ikon jam default Google */
        .pac-item-query {
            font-size: 14px;
            font-weight: 800;
            color: #1e293b;
        }
        .pac-matched { color: #10b981; }
    </style>

    {{-- Midtrans Snap.js --}}
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <div class="bg-[#F8FAFC] min-h-screen pb-20">
        <div class="max-w-7xl mx-auto pt-10 px-4 sm:px-6 lg:px-8">
            
            {{-- Header Checkout dengan Progress --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-600 text-xs font-semibold rounded-full">Langkah 2 dari 3</span>
                        <h1 class="text-4xl font-bold text-gray-900 tracking-tighter uppercase">Checkout</h1>
                    </div>
                    <p class="text-xs font-bold text-gray-400">Selesaikan detail pengiriman & pembayaran bolo</p>
                </div>
                
                {{-- Stepper --}}
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2 opacity-40">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold">1</div>
                        <span class="text-xs font-semibold hidden sm:block">Keranjang</span>
                    </div>
                    <div class="w-8 h-px bg-gray-200"></div>
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-full bg-emerald-600 flex items-center justify-center text-white text-xs font-bold shadow-lg shadow-emerald-200">2</div>
                        <span class="text-xs font-semibold text-emerald-600">Checkout</span>
                    </div>
                    <div class="w-8 h-px bg-gray-200"></div>
                    <div class="flex items-center gap-2 opacity-40">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold">3</div>
                        <span class="text-xs font-semibold hidden sm:block">Selesai</span>
                    </div>
                </div>
            </div>

            <form id="checkout-form" class="grid lg:grid-cols-12 gap-10 items-start">
                @csrf
                <input type="hidden" name="lat" id="lat">
                <input type="hidden" name="lng" id="lng">

                {{-- LEFT COLUMN: Lokasi & Produk --}}
                <div class="lg:col-span-8 space-y-8">
                    
                    {{-- 1. Lokasi Section --}}
                    <div class="bg-white rounded-2xl p-10 shadow-xl shadow-slate-200/50 border border-white relative overflow-hidden">
                        {{-- Background Accent --}}
                        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50/50 rounded-bl-[100px] -z-0"></div>
                        
                        <div class="relative z-10">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-10">
                                <div class="flex items-center gap-5">
                                    <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-emerald-100 rotate-3">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-bold text-gray-900 tracking-tight uppercase">Lokasi Pengiriman</h2>
                                        <p class="text-xs font-bold text-emerald-500 mt-1">Titik peta & Area pengiriman</p>
                                    </div>
                                </div>
                                
                                @if(auth()->user()->alamat)
                                <button type="button" 
                                    onclick="useSavedAddress('{{ auth()->user()->alamat }}')"
                                    class="text-xs font-semibold uppercase tracking-[0.15em] text-emerald-600 bg-white border-2 border-emerald-50 px-6 py-3 rounded-2xl hover:bg-emerald-600 hover:text-white hover:border-emerald-600 hover:scale-105 transition-all duration-300 shadow-sm active:scale-95">
                                    Pakai Alamat Profil
                                </button>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 items-stretch">
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none z-20">
                                        <div class="w-12 h-12 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200 group-focus-within:scale-105 transition-all duration-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        </div>
                                    </div>
                                    <input type="text" id="map-search" 
                                        class="w-full h-full bg-white border-2 border-slate-100 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/10 rounded-2xl pl-20 pr-8 py-6 text-sm transition-all outline-none font-bold placeholder:text-slate-400 shadow-xl shadow-slate-200/20 leading-none" 
                                        placeholder="Cari gedung, jalan, atau patokan...">
                                    
                                    {{-- Dropdown Hasil Pencarian (Glassmorphism Style) --}}
                                    <div id="search-results" class="absolute z-[100] w-full mt-4 bg-white/80 backdrop-blur-2xl rounded-2xl shadow-lg shadow-emerald-200/50 border border-white/50 hidden overflow-hidden animate-in fade-in slide-in-from-top-4 duration-300">
                                        {{-- Hasil akan muncul di sini --}}
                                    </div>
                                </div>

                                {{-- Biteship Area Search --}}
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none z-20">
                                        <div class="w-12 h-12 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200 group-focus-within:scale-105 transition-all duration-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        </div>
                                    </div>
                                    <input type="text" id="area-search" 
                                        class="w-full h-full bg-white border-2 border-slate-100 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/10 rounded-2xl pl-20 pr-8 py-6 text-sm transition-all outline-none font-bold placeholder:text-slate-400 shadow-xl shadow-slate-200/20 leading-none" 
                                        placeholder="Konfirmasi Kecamatan/Kota..." autocomplete="off">
                                    <input type="hidden" name="destination_area_id" id="destination_area_id">
                                    
                                    {{-- Area Results Dropdown --}}
                                    <div id="area-results" class="absolute z-[100] w-full mt-2 bg-white rounded-2xl shadow-lg border border-slate-100 hidden max-h-60 overflow-y-auto">
                                        {{-- Results dynamic --}}
                                    </div>
                                </div>
                            </div>

                            <div id="leaflet-map" class="h-[350px] rounded-2xl mb-8 overflow-hidden shadow-inner border-4 border-slate-50 relative group">
                                <div class="absolute bottom-6 left-6 z-[10] pointer-events-none transition-opacity duration-300 group-hover:opacity-0 hidden sm:block">
                                    <div class="bg-white/90 backdrop-blur-sm px-4 py-2 rounded-xl shadow-lg border border-white">
                                        <p class="text-[11px] font-bold text-slate-800">Interactive Map Ready</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-6">
                                {{-- 1. Alamat Hasil Peta (INFO ONLY) --}}
                                <div class="p-6 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl relative group transition-all">
                                    <div class="absolute -top-3 left-6 flex items-center justify-center">
                                        <span class="bg-slate-400 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-lg">Lokasi Terpilih (Otomatis)</span>
                                    </div>
                                    <div class="flex items-start gap-4 mt-2">
                                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-300 shrink-0 shadow-sm border border-slate-100">
                                            <svg class="w-5 h-5 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </div>
                                        <div class="flex-1">
                                            <textarea name="alamat" id="alamat" rows="2" readonly
                                                class="w-full bg-transparent border-none p-0 text-[11px] font-bold text-slate-500 leading-relaxed focus:ring-0 cursor-default"
                                                placeholder="Geser pin pada peta untuk menentukan lokasi..."></textarea>
                                            
                                            <div id="osm-suggestion-checkout" class="mt-2"></div>
                                        </div>
                                    </div>

                                    {{-- Google Maps Helper --}}
                                    <div class="mt-4 pt-4 border-t border-slate-200/50 flex flex-wrap items-center gap-3">
                                        <button type="button" onclick="window.open('https://www.google.com/maps', '_blank')" 
                                            class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1.5 hover:underline">
                                            <i class="fa-brands fa-google"></i> Buka Google Maps
                                        </button>
                                        <span class="text-slate-300">|</span>
                                        <button type="button" onclick="pasteCoordinates()" 
                                            class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1.5 hover:underline">
                                            <i class="fa-solid fa-paste"></i> Paste Koordinat
                                        </button>
                                        <button type="button" onclick="showCoordTutorial()" 
                                            class="w-5 h-5 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all">
                                            <i class="fa-solid fa-question text-xs"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- 2. Detail Patokan (THE MAIN INPUT) --}}
                                <div class="relative group">
                                    <div class="absolute -top-3 left-6 flex items-center justify-center z-10">
                                        <span class="bg-emerald-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-lg">Detail & Patokan (Wajib Isi)</span>
                                    </div>
                                    <textarea name="catatan_alamat" id="catatan_alamat" rows="3" 
                                        class="w-full bg-white border-4 border-emerald-100/50 rounded-2xl px-8 py-8 text-sm focus:ring-8 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all placeholder:text-slate-300 font-bold leading-relaxed shadow-xl shadow-emerald-100/20"
                                        placeholder="Tuliskan detail rumah bolo, misal: Depan masjid Al-Ikhlas, Gerbang biru, Belakang laundry..."></textarea>
                                    
                                    <div class="absolute top-8 right-8 text-emerald-200 group-focus-within:text-emerald-400 transition-colors">
                                        <i class="fa-solid fa-map-pin text-3xl"></i>
                                    </div>

                                    <div class="absolute bottom-4 right-8">
                                        <div class="flex items-center gap-2 px-3 py-1 bg-emerald-50 rounded-full border border-emerald-100">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                            <span class="text-xs font-semibold text-emerald-600">Kurir butuh ini bolo</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Daftar Produk Section --}}
                    <div class="bg-white rounded-2xl p-10 shadow-xl shadow-slate-200/50 border border-white overflow-hidden">
                        <div class="flex items-center justify-between mb-10">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 bg-orange-500 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-orange-100 -rotate-3">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight uppercase">Rincian Paket</h2>
                                    <p class="text-xs font-bold text-orange-500 mt-1">Total produk yang akan dikirim</p>
                                </div>
                            </div>
                            <div class="hidden sm:flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-xl border border-slate-100">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                                <span class="text-xs font-bold text-slate-500">Stok Tersedia</span>
                            </div>
                        </div>

                        <div id="product-list" class="space-y-6">
                            {{-- Items will be rendered here --}}
                        </div>
                    </div>

                    {{-- 3. Kurir & Pengiriman Section --}}
                    <div class="bg-white rounded-2xl p-10 shadow-xl shadow-slate-200/50 border border-white">
                        <div class="flex items-center gap-5 mb-10">
                            <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-emerald-100 rotate-3">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 tracking-tight uppercase">Pilih Layanan</h2>
                                <p class="text-xs font-bold text-emerald-500 mt-1">Metode pengiriman yang tersedia</p>
                            </div>
                        </div>

                        <div class="space-y-8">
                            {{-- Courier List --}}
                            <div id="courier-container" class="space-y-4 opacity-50 pointer-events-none">
                                <p class="text-xs font-medium text-slate-400 text-center py-4 border-2 border-dashed border-slate-100 rounded-2xl">
                                    Pilih area lokasi dulu bolo
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Payment & Summary --}}
                <div class="lg:col-span-4 space-y-8 lg:sticky lg:top-10">
                    
                    {{-- 3. Payment Method --}}
                    <div class="bg-white rounded-2xl p-10 shadow-xl shadow-slate-200/50 border border-white">
                        <div class="flex items-center gap-4 mb-10">
                            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">Pembayaran</h2>
                        </div>

                        <div class="space-y-4">
                            <label class="relative cursor-pointer group block">
                                <input type="radio" name="payment_method" value="midtrans" class="peer hidden" checked>
                                <div class="p-6 rounded-2xl border-2 border-slate-50 peer-checked:border-emerald-600 peer-checked:bg-emerald-50/50 transition-all duration-300 flex items-center justify-between group-hover:bg-slate-50">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-100 group-hover:scale-110 transition-transform">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800">Smart Pay (Otomatis)</p>
                                            <p class="text-xs font-bold text-slate-400 mt-0.5">Transfer, QRIS, & VA</p>
                                        </div>
                                    </div>
                                    <div class="hidden peer-checked:block">
                                        <span class="text-xs font-bold bg-emerald-600 text-white px-2 py-0.5 rounded-full">Recommended</span>
                                    </div>
                                </div>
                            </label>

                            <label class="relative cursor-pointer group block">
                                <input type="radio" name="payment_method" value="cod" class="peer hidden">
                                <div class="p-6 rounded-2xl border-2 border-slate-50 peer-checked:border-orange-600 peer-checked:bg-orange-50/50 transition-all duration-300 flex items-center gap-4 group-hover:bg-slate-50">
                                    <div class="w-10 h-10 rounded-full bg-orange-600 flex items-center justify-center text-white shadow-lg shadow-orange-100 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Bayar Ditempat (COD)</p>
                                        <p class="text-xs font-bold text-slate-400 mt-0.5">Bayar cash ke kurir bolo</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- 4. Ringkasan Belanja --}}
                    <div class="bg-gray-900 rounded-2xl p-10 text-white shadow-lg relative overflow-hidden">
                        {{-- Decorative Elements --}}
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-500/10 rounded-full blur-3xl"></div>
                        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-emerald-500/10 rounded-full blur-3xl"></div>

                        <div class="relative">
                            <h2 class="text-2xl font-bold tracking-tighter mb-8 uppercase flex items-center gap-3">
                                <span class="w-8 h-1 bg-emerald-500 rounded-full"></span>
                                Ringkasan
                            </h2>

                            <div class="space-y-6">
                                <div class="flex justify-between items-center group">
                                    <span class="text-xs font-bold text-slate-400">Subtotal Barang</span>
                                    <span id="summary-subtotal" class="font-bold text-sm">Rp0</span>
                                </div>
                                
                                <div class="flex justify-between items-center group">
                                    <span class="text-xs font-bold text-slate-400">Biaya Admin</span>
                                    <span class="font-bold text-sm">Rp{{ number_format($settings['admin_fee'] ?? 2000, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between items-center group">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-400">Total Berat</span>
                                        <p class="text-[11px] text-slate-500 font-bold" id="weight-breakdown">0 Barang x 0kg</p>
                                    </div>
                                    <span id="total-weight-display" class="font-bold text-sm text-emerald-400 tracking-tight">0 Kg</span>
                                </div>

                                <div class="pt-6 border-t border-white/5 space-y-3">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-emerald-400">Biaya Pengiriman</span>
                                            <span id="dist-label" class="text-xs bg-emerald-500/20 px-2 py-0.5 rounded-full text-emerald-300 font-bold">0 KM</span>
                                        </div>
                                        <div class="flex flex-col items-end">
                                            <span id="summary-ongkir" class="font-bold text-sm text-emerald-400 tracking-tight">Rp0</span>
                                            <span id="summary-subsidy" class="hidden text-[11px] font-bold text-emerald-400 mt-1 animate-bounce">Potongan Rp0 Applied!</span>
                                        </div>
                                    </div>
                                    
                                    {{-- Info tambahan --}}
                                    <div class="bg-white/5 rounded-2xl p-4 space-y-2 border border-white/5">
                                        <p class="text-[11px] text-slate-400 font-bold leading-relaxed">
                                            Biaya pengiriman dihitung otomatis berdasarkan jarak dan berat paket bolo.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="my-10 pt-8 border-t border-white/10 relative">
                                <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-gray-900 px-4">
                                    <svg class="w-6 h-6 text-white/20" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/></svg>
                                </div>
                                <div class="flex justify-between items-end">
                                    <div>
                                        <p class="text-xs font-bold text-emerald-400 mb-2">Total Tagihan</p>
                                        <p id="total-price-display" class="text-4xl font-bold tracking-tighter text-white">Rp0</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs font-bold text-slate-500 leading-tight">Termasuk Pajak &<br>Biaya Transaksi</p>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" id="btn-pay" 
                                class="group relative w-full bg-emerald-600 hover:bg-emerald-500 py-6 rounded-2xl font-bold text-xs uppercase tracking-[0.25em] shadow-lg shadow-emerald-500/20 transition-all active:scale-95 overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/10 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></div>
                                <span class="relative flex items-center justify-center gap-3">
                                    Konfirmasi & Bayar
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </span>
                            </button>
                            
                            <p class="mt-6 text-center text-[11px] font-bold text-slate-500 uppercase tracking-[0.1em]">
                                Transaksi Enkripsi SSL 256-bit Aman Bolo
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Script Section --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 1. DATA INITIALIZATION
        let checkoutItems = JSON.parse(localStorage.getItem('checkout_items') || '[]');
        // Pastikan setiap item punya property quantity minimal 1
        checkoutItems = checkoutItems.map(item => ({ ...item, quantity: item.quantity || 1 }));
        let globalShippingFee = 0;
        let globalSubsidy = 0;

        // Dynamic Config from Admin
        const config = {
            shipping_base_fee: {{ $settings['shipping_base_fee'] ?? 5000 }},
            shipping_per_km: {{ $settings['shipping_per_km'] ?? 2000 }},
            shipping_per_kg: {{ $settings['shipping_per_kg'] ?? 1000 }},
            admin_fee: {{ $settings['admin_fee'] ?? 2000 }},
            free_shipping_max_dist: {{ $settings['free_shipping_max_dist'] ?? 20 }}
        };

        const defaultSellerCoord = { lat: -7.4726, lng: 112.4385 };
        const coordTolerance = 0.00001;
        const sellerCoordCache = {};

        function isMissingSellerCoord(lat, lng) {
            if (lat === null || lng === null || lat === undefined || lng === undefined) return true;
            const latNum = parseFloat(lat);
            const lngNum = parseFloat(lng);
            if (Number.isNaN(latNum) || Number.isNaN(lngNum)) return true;
            return Math.abs(latNum - defaultSellerCoord.lat) < coordTolerance && Math.abs(lngNum - defaultSellerCoord.lng) < coordTolerance;
        }

        async function hydrateSellerCoordinates() {
            const idsToFetch = [];
            const inspected = new Set();

            checkoutItems.forEach(item => {
                const sellerId = item.user_id || item.seller_id || item.shop_id;
                if (!sellerId || inspected.has(sellerId)) return;

                const lat = item.seller_lat ?? item.shop_lat;
                const lng = item.seller_lng ?? item.shop_lng;

                if (isMissingSellerCoord(lat, lng)) {
                    idsToFetch.push(sellerId);
                    inspected.add(sellerId);
                }
            });

            if (idsToFetch.length === 0) {
                return false;
            }

            try {
                const response = await fetch(`{{ route('shipping.seller-coordinates') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ seller_ids: idsToFetch })
                });

                if (!response.ok) {
                    return false;
                }

                const payload = await response.json();
                payload.forEach(data => {
                    sellerCoordCache[data.id] = data;
                });

                let mutated = false;

                checkoutItems = checkoutItems.map(item => {
                    const sellerId = item.user_id || item.seller_id || item.shop_id;
                    if (sellerId && sellerCoordCache[sellerId]) {
                        const data = sellerCoordCache[sellerId];
                        if (isMissingSellerCoord(item.seller_lat ?? item.shop_lat, item.seller_lng ?? item.shop_lng) && data.lat && data.lng) {
                            item.seller_lat = data.lat;
                            item.seller_lng = data.lng;
                            mutated = true;
                        }

                        if (!item.seller_name && data.name) {
                            item.seller_name = data.name;
                            mutated = true;
                        }

                        if (!item.lokasi && data.alamat) {
                            item.lokasi = data.alamat;
                            mutated = true;
                        }
                    }
                    return item;
                });

                if (mutated) {
                    localStorage.setItem('checkout_items', JSON.stringify(checkoutItems));
                }

                return mutated;
            } catch (error) {
                console.error('Gagal sinkron koordinat seller', error);
                return false;
            }
        }

        // 2. RENDER SUMMARY & ITEMS
        function renderCheckout() {
            if (checkoutItems.length === 0) return window.location.href = '/';
            
            let subtotal = 0;
            let totalBerat = 0;
            let totalQty = 0;
            const productContainer = document.getElementById('product-list');
            
            productContainer.innerHTML = checkoutItems.map((item, index) => {
                const itemTotal = item.harga * item.quantity;
                const gram = item.berat || 1000;
                const itemBerat = gram * item.quantity;
                subtotal += itemTotal;
                totalBerat += itemBerat;
                totalQty += parseInt(item.quantity);
                
                return `
                <div class="group flex flex-col sm:flex-row items-center gap-8 p-6 rounded-2xl border-2 border-slate-50 hover:border-emerald-100 hover:bg-emerald-50/30 transition-all duration-500">
                    <div class="relative shrink-0">
                        <img src="${item.gambar}" class="w-32 h-32 rounded-2xl object-cover shadow-lg shadow-slate-200 group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute -top-2 -right-2 bg-emerald-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-4 border-white shadow-lg">
                            ${item.quantity}
                        </div>
                    </div>
                    
                    <div class="flex-1 text-center sm:text-left overflow-hidden">
                        <div class="flex items-center justify-center sm:justify-start gap-2 mb-2">
                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-600 text-xs font-semibold rounded-md">${item.kategori || 'Produk'}</span>
                            <span class="text-[11px] font-bold text-slate-300">${item.seller_name || 'Official Store'}</span>
                        </div>
                        <h3 class="font-bold text-slate-800 text-lg mb-2 truncate group-hover:text-emerald-600 transition-colors">${item.nama_barang}</h3>
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-x-4 gap-y-1 text-xs font-bold text-slate-400">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                                Rp${item.harga.toLocaleString('id-ID')}
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                ${gram}g / pcs
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-row sm:flex-col items-center gap-6">
                        <div class="flex items-center gap-1 bg-slate-100 p-1.5 rounded-[1.5rem] border-2 border-slate-200/50 shadow-inner">
                            <button type="button" onclick="window.updateQty(${index}, -1)" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white hover:bg-red-500 hover:text-white hover:scale-105 transition-all duration-300 font-bold text-lg shadow-sm">-</button>
                            <span class="font-bold text-slate-800 min-w-[40px] text-center text-lg">${item.quantity}</span>
                            <button type="button" onclick="window.updateQty(${index}, 1)" class="w-10 h-10 flex items-center justify-center rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 hover:scale-105 transition-all duration-300 font-bold text-lg shadow-md shadow-emerald-100">+</button>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-[11px] font-medium text-slate-400 mb-1">Subtotal Item</p>
                            <p class="font-bold text-slate-900 text-xl tracking-tighter">Rp${itemTotal.toLocaleString('id-ID')}</p>
                        </div>
                    </div>
                </div>`;
            }).join('');

            const grandTotal = subtotal + config.admin_fee + globalShippingFee;
            document.getElementById('summary-subtotal').innerText = `Rp${subtotal.toLocaleString('id-ID')}`;
            document.getElementById('summary-ongkir').innerText = `Rp${globalShippingFee.toLocaleString('id-ID')}`;
            
            // Handle Subsidy Display
            const subsidyEl = document.getElementById('summary-subsidy');
            if (globalSubsidy > 0) {
                subsidyEl.innerText = globalSubsidy >= 99999 ? 'Gratis Ongkir Bolo!' : `Potongan Rp${globalSubsidy.toLocaleString('id-ID')} Applied!`;
                subsidyEl.classList.remove('hidden');
            } else {
                subsidyEl.classList.add('hidden');
            }

            document.getElementById('total-price-display').innerText = `Rp${grandTotal.toLocaleString('id-ID')}`;
            
            // Render weight badge in summary
            const weightDisplay = document.getElementById('total-weight-display');
            if (weightDisplay) {
                weightDisplay.innerText = `${(totalBerat/1000).toFixed(1)} Kg`;
            }
            
            const weightBreakdown = document.getElementById('weight-breakdown');
            if (weightBreakdown) {
                weightBreakdown.innerText = `${totalQty} Item x ${(totalBerat/1000/totalQty).toFixed(2)}kg (Avg)`;
            }

            // Update Info Pengiriman Text
            const infoText = document.getElementById('shipping-info-text');
            if (infoText && checkoutItems.length > 0) {
                // Re-calculate unique sellers for text
                const currentSellers = [];
                checkoutItems.forEach(item => {
                    const sId = String(item.user_id || item.seller_id || '0');
                    if (sId !== '0' && !currentSellers.find(s => s.id === sId)) {
                        currentSellers.push({
                            id: sId,
                            name: item.seller_name || 'Toko',
                            lokasi: item.lokasi || 'Mojokerto'
                        });
                    }
                });

                if (currentSellers.length > 1) {
                    infoText.innerHTML = `Dikirim dari <span class="text-emerald-600 font-bold">${currentSellers.length} Toko Berbeda</span>. Total ongkir dijumlahkan dari tiap lokasi pickup.`;
                } else if (currentSellers.length === 1) {
                    infoText.innerHTML = `Dikirim dari <span class="text-emerald-600 font-bold">${currentSellers[0].name}</span> di <span class="text-emerald-600 font-bold">${currentSellers[0].lokasi || 'Mojokerto'}</span>.`;
                } else {
                    infoText.innerHTML = `Dikirim dari <span class="text-emerald-600 font-bold">${checkoutItems[0].seller_name || 'Toko'}</span>.`;
                }
            }

            // Sync Map Markers
            if (typeof updateSellerMarkers === 'function') {
                updateSellerMarkers();
            }
        }

        // 3. QUANTITY LOGIC
        window.updateQty = (index, change) => {
            const item = checkoutItems[index];
            const newQty = parseInt(item.quantity) + change;

            if (newQty > item.stok) {
                return Swal.fire('Waduh!', `Stok cuma ada ${item.stok} bolo!`, 'warning');
            }
            if (newQty < 1) {
                return Swal.fire({
                    title: 'Hapus item?',
                    text: "Item ini akan dihapus dari checkout",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!'
                }).then((res) => {
                    if(res.isConfirmed) {
                        checkoutItems.splice(index, 1);
                        saveAndRefresh();
                    }
                });
            }

            checkoutItems[index].quantity = newQty;
            saveAndRefresh();
        };

        function saveAndRefresh() {
            localStorage.setItem('checkout_items', JSON.stringify(checkoutItems));
            
            // RE-RENDER IMMEDIATELY
            renderCheckout();

            // RE-CALCULATE ONGKIR IF LOCATION EXISTS
            const lat = document.getElementById('lat').value;
            const lng = document.getElementById('lng').value;
            if(lat && lng) {
                Ongkir(lat, lng);
            }
        }

        window.useSavedAddress = async (address) => {
            if(!address) return;
            
            const alamatArea = document.getElementById('alamat');
            alamatArea.value = address;
            
            // Animasi feedback
            alamatArea.classList.add('ring-4', 'ring-emerald-500/20');
            setTimeout(() => alamatArea.classList.remove('ring-4', 'ring-emerald-500/20'), 1000);

            // Coba geocoding biar peta pindah (Free version)
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address + ' Mojokerto')}&limit=1`, {
                    headers: { 'User-Agent': 'MiniQ-Store-App' }
                });
                const data = await response.json();
                if (data.length > 0) {
                    const { lat, lon } = data[0];
                    selectLocation(lat, lon, address, false);

                    // OTOMATIS SYNC KE BITESHIP/AREA SEARCH
                    try {
                        const revRes = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`, {
                            headers: { 'User-Agent': 'MiniQ-Store-App' }
                        });
                        const revData = await revRes.json();
                        if (revData && revData.address) {
                            const addr = revData.address;
                            const keyword = addr.subdistrict || addr.village || addr.city_district || addr.city || addr.town;
                            if (keyword) {
                                document.getElementById('area-search').value = keyword;
                                performAreaSearch(keyword, true); 
                            }
                        }
                    } catch (e) { console.error("Reverse sync failed", e); }

                    Swal.fire({
                        icon: 'success',
                        title: 'Alamat Dipasang',
                        text: 'Peta & Area otomatis sinkron bolo!',
                        toast: true, position: 'top-end', showConfirmButton: false, timer: 3000
                    });
                }
            } catch (error) {
                console.log("Geocoding failed, manual mapping kept");
            }
        };

        {{-- 4. MAP SETUP (FOKUS MOJOKERTO) ---}}
        const mojokertoCoord = [-7.4726, 112.4385];
        
        let map;
        // Tentukan Titik Pengirim (Seller) - Multi Origin Support
        let sellerMarkers = [];
        let uniqueSellers = [];
        
        function updateSellerMarkers() {
            // Clear existing markers
            sellerMarkers.forEach(m => map.removeLayer(m));
            sellerMarkers = [];
            uniqueSellers = [];

            if (checkoutItems.length > 0) {
                checkoutItems.forEach(item => {
                    const sId = String(item.user_id || item.seller_id || item.shop_id || '0');
                    if (sId !== '0' && !uniqueSellers.find(s => s.id === sId)) {
                        uniqueSellers.push({
                            id: sId,
                            name: item.seller_name || item.shop_name || 'Official Store',
                            lat: parseFloat(item.seller_lat ?? item.shop_lat ?? defaultSellerCoord.lat),
                            lng: parseFloat(item.seller_lng ?? item.shop_lng ?? defaultSellerCoord.lng)
                        });
                    }
                });
            }

            // Custom Store Icon
            const storeIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `
                    <div class="relative flex items-center justify-center">
                        <div class="absolute animate-ping w-8 h-8 bg-emerald-500/30 rounded-full"></div>
                        <div class="relative w-6 h-6 bg-emerald-600 rounded-lg flex items-center justify-center border-2 border-white shadow-lg">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                    </div>`,
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });

            uniqueSellers.forEach((seller, idx) => {
                let lat = parseFloat(seller.lat);
                let lng = parseFloat(seller.lng);

                // Jika ada lebih dari satu seller di koordinat yang sama persis, geser sedikit agar terlihat
                const isOverlapping = uniqueSellers.slice(0, idx).some(s => Math.abs(s.lat - seller.lat) < coordTolerance && Math.abs(s.lng - seller.lng) < coordTolerance);
                if (isOverlapping) {
                    lat += (Math.random() - 0.5) * 0.0004; 
                    lng += (Math.random() - 0.5) * 0.0004;
                }

                let sMarker = L.marker([lat, lng], { icon: storeIcon }).addTo(map);
                sMarker.bindTooltip(`<div class="font-bold text-[11px] text-emerald-600 p-1">Toko: ${seller.name}</div>`, { 
                    permanent: true, 
                    direction: 'top', 
                    offset: [0, -10] 
                });
                sellerMarkers.push(sMarker);
            });
        }

        map = L.map('leaflet-map', { 
            zoomControl: false,
            attributionControl: false 
        }).setView(mojokertoCoord, 14);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(map);

        updateSellerMarkers();

        // Marker Pembeli (User)
        const userIcon = L.divIcon({
            className: 'custom-div-icon',
            html: `
                <div class="relative flex items-center justify-center">
                    <div class="w-10 h-10 bg-orange-600 border-4 border-white rounded-full flex items-center justify-center shadow-lg overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name=You&background=EA580C&color=fff" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -bottom-1 bg-orange-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">Lokasimu</div>
                </div>`,
            iconSize: [40, 40],
            iconAnchor: [20, 20]
        });

        let marker = L.marker(mojokertoCoord, {draggable: true, icon: userIcon}).addTo(map);

        // Initialize Coordinates & Ongkir
        document.getElementById('lat').value = mojokertoCoord[0];
        document.getElementById('lng').value = mojokertoCoord[1];
        setTimeout(() => Ongkir(mojokertoCoord[0], mojokertoCoord[1]), 500);

        // UI Friendly Delivery Radius (Pakai Seller Pertama sebagai referensi visual)
        if (uniqueSellers.length > 0) {
            L.circle([uniqueSellers[0].lat, uniqueSellers[0].lng], {
                color: '#10b981', weight: 1, dashArray: '5, 10', fillColor: '#10b981', fillOpacity: 0.05, radius: config.free_shipping_max_dist * 1000 
            }).addTo(map);
        }

        const locateBtn = L.control({position: 'topright'});
        locateBtn.onAdd = function() {
            let div = L.DomUtil.create('div', 'bg-white p-3 rounded-2xl shadow-xl cursor-pointer hover:scale-110 transition-all border border-slate-100');
            div.innerHTML = `<svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`;
            div.onclick = () => map.locate({setView: true, maxZoom: 16});
            return div;
        };
        locateBtn.addTo(map);

        map.on('locationfound', (e) => {
            marker.setLatLng(e.latlng);
            updateAddress(e.latlng.lat, e.latlng.lng);
        });

        // --- FREE SEARCH LOGIC (OSM/NOMINATIM) - NO BILLING REQUIRED ---
        const searchInput = document.getElementById('map-search');
        const alamatArea = document.getElementById('alamat');
        const resultsBox = document.getElementById('search-results');
        let searchTimer;

        async function performSearch(query) {
            if (query.length < 3) {
                resultsBox.classList.add('hidden');
                return;
            }

            // --- FITUR AUTO-DETECT KOORDINAT ---
            // Cek apakah user paste format koordinat (contoh: -7.54, 112.47)
            const coordRegex = /([-+]?\d{1,2}\.\d+),\s*([-+]?\d{1,3}\.\d+)/;
            const match = query.match(coordRegex);
            if (match) {
                const lat = match[1];
                const lon = match[2];
                selectLocation(lat, lon, "Lokasi dari Koordinat: " + query);
                resultsBox.classList.add('hidden');
                return;
            }
            
            resultsBox.innerHTML = '<div class="px-6 py-4 text-xs text-slate-400 animate-pulse">Mencari lokasi akurat...</div>';
            resultsBox.classList.remove('hidden');

            const viewbox = '112.15,-7.20,112.75,-7.80'; // Focus Mojokerto
            // Strategi: Coba cari alamat asli, kalau tidak ketemu coba pecah keywordnya
            const searchQueries = [
                query + ' Mojokerto',
                query.split(',')[0] + ' Mojokerto' // Ambil bagian depan saja (biasanya nama tempat/jalan)
            ];

            try {
                let data = [];
                for (let q of searchQueries) {
                    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}&limit=6&addressdetails=1&viewbox=${viewbox}`;
                    const res = await fetch(url, { headers: { 'Accept-Language': 'id', 'User-Agent': 'MiniQ-Store-App' } });
                    const result = await res.json();
                    if (result.length > 0) {
                        data = result;
                        break;
                    }
                }

                if (data.length > 0) {
                    resultsBox.innerHTML = data.map(item => {
                        const parts = item.display_name.split(',');
                        const mainTitle = parts[0];
                        const subTitle = parts.slice(1, 3).join(',');
                        
                        return `
                        <div class="px-6 py-4 hover:bg-emerald-50 cursor-pointer border-b border-slate-50 last:border-0 transition-colors" 
                             onclick="selectLocation(${item.lat}, ${item.lon}, '${item.display_name.replace(/'/g, "\\'")}')">
                            <div class="flex items-start gap-4">
                                <div class="mt-1 flex-shrink-0 w-8 h-8 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div class="flex-1 overflow-hidden">
                                    <p class="text-sm font-bold text-slate-800 truncate mb-0.5">${mainTitle}</p>
                                    <p class="text-xs font-bold text-slate-400 truncate">${subTitle}</p>
                                </div>
                            </div>
                        </div>`;
                    }).join('');
                } else {
                    resultsBox.innerHTML = '<div class="px-6 py-4 text-xs text-orange-500 font-bold text-center">Lokasi tidak ditemukan bolo. Coba kata kunci lain.</div>';
                }
            } catch (e) {
                resultsBox.classList.add('hidden');
            }
        }

        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => performSearch(e.target.value), 500);
        });

        // Close results on click outside
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
                resultsBox.classList.add('hidden');
            }
        });

        window.selectLocation = (lat, lon, name, updateTextarea = true) => {
            const pos = { lat: parseFloat(lat), lng: parseFloat(lon) };
            marker.setLatLng(pos);
            map.flyTo(pos, 16, { animate: true, duration: 1.5 });
            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lon;
            Ongkir(lat, lon);
            if (updateTextarea) {
                alamatArea.value = name;
                searchInput.value = '';
            }
            resultsBox.classList.add('hidden');
        };

        async function pasteCoordinates() {
            const text = await navigator.clipboard.readText();
            const coordRegex = /([-+]?\d{1,2}\.\d+),\s*([-+]?\d{1,3}\.\d+)/;
            const match = text.match(coordRegex);
            
            if (match) {
                const lat = match[1];
                const lon = match[2];
                selectLocation(lat, lon, "Lokasi dari Paste Koordinat (" + lat + ", " + lon + ")");
                Swal.fire({
                    icon: 'success',
                    title: 'Koordinat Terpasang!',
                    text: 'Peta langsung meluncur ke titik bolo.',
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 2000
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Bukan Koordinat',
                    text: 'Pastikan formatnya lat, lng ya bolo!',
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 2000
                });
            }
        }

        function showCoordTutorial() {
            Swal.fire({
                title: '<span class="text-xl font-semibold">Cara Ambil Koordinat</span>',
                html: `
                    <div class="text-left space-y-4 p-2">
                        <div class="flex gap-4">
                            <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex-shrink-0 flex items-center justify-center font-bold text-xs">1</div>
                            <p class="text-[11px] font-bold text-slate-600">Buka <b>Google Maps</b>, cari lokasimu atau tahan lama (long-press) pada titik yang kamu mau.</p>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex-shrink-0 flex items-center justify-center font-bold text-xs">2</div>
                            <p class="text-[11px] font-bold text-slate-600">Akan muncul angka koordinat (contoh: <b>-7.481, 112.443</b>). Klik angka tersebut untuk menyalin/copy.</p>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex-shrink-0 flex items-center justify-center font-bold text-xs">3</div>
                            <p class="text-[11px] font-bold text-slate-600">Balik ke sini, lalu klik tombol <span class="text-emerald-600 font-semibold text-[11px] px-2 py-0.5 bg-emerald-50 rounded border border-emerald-100">Paste Koordinat</span> bolo!</p>
                        </div>
                    </div>
                `,
                confirmButtonText: 'IYO PAHAM BOLO!',
                confirmButtonColor: '#10b981',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-8 py-3 text-xs font-semibold'
                }
            });
        }

        async function updateAddress(lat, lng) {
            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;
            Ongkir(lat, lng);
            
            try {
                const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`, {
                    headers: { 'User-Agent': 'MiniQ-Store-App' }
                });
                const data = await res.json();
                if (data && data.display_name) {
                    const suggestionEl = document.getElementById('osm-suggestion-checkout');
                    if (suggestionEl) {
                        suggestionEl.innerHTML = `
                            <div class="mt-4 p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-start gap-3 animate-in fade-in slide-in-from-top-2">
                                <div class="w-8 h-8 bg-emerald-600 rounded-xl flex items-center justify-center text-white shrink-0 shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-emerald-900 font-semibold mb-1">DETEKSI ALAMAT PETA:</p>
                                    <p class="text-[11px] text-emerald-700 font-medium leading-relaxed mb-2">"${data.display_name}"</p>
                                    <button type="button" onclick="document.getElementById('alamat').value = '${data.display_name.replace(/'/g, "\\'")}'; this.parentElement.parentElement.remove();" 
                                            class="px-4 py-2 bg-emerald-600 text-white text-[11px] font-semibold rounded-xl hover:bg-emerald-700 transition-all shadow-md">
                                        Gunakan Alamat Ini
                                    </button>
                                </div>
                            </div>
                        `;
                    }

                    // OTOMATIS SYNC KE BITESHIP (Tetap lakukan ini untuk kalkulasi ongkir)
                    const addr = data.address;
                    const keyword = addr.subdistrict || addr.village || addr.city_district || addr.city || addr.town;
                    if (keyword) {
                        areaSearch.value = keyword;
                        performAreaSearch(keyword, true); 
                    }
                }
            } catch(e) {
                console.error("Reverse geocoding failed");
            }
        }

        function Ongkir(lat, lng) {
            if (!lat || !lng) return;
            
            const pos = L.latLng(lat, lng);
            
            // Calculate sum of distances for all unique sellers
            let totalKm = 0;
            uniqueSellers.forEach(seller => {
                const dist = map.distance(pos, L.latLng(seller.lat, seller.lng));
                totalKm += dist / 1000;
            });
            
            // 1. Update Label Jarak
            const distLabel = document.getElementById('dist-label');
            if (distLabel) {
                distLabel.innerText = `${totalKm.toFixed(1)} km (total trip)`;
                distLabel.className = totalKm <= (5 * uniqueSellers.length) 
                    ? "text-xs bg-emerald-500/20 px-2 py-0.5 rounded-full text-emerald-400 font-bold border border-emerald-500/10 uppercase"
                    : "text-xs bg-emerald-500/20 px-2 py-0.5 rounded-full text-emerald-300 font-bold border border-emerald-500/10 uppercase";
            }

            // 2. Fetch Rates from Backend (Backend will handle multi-origin sum)
            fetchShippingRates(destinationAreaInput.value, lat, lng);
        }

        function renderCouriers(rates = []) {
            courierContainer.classList.remove('opacity-50', 'pointer-events-none');
            
            if (rates.length === 0) {
                courierContainer.innerHTML = `
                    <div class="p-6 rounded-2xl border-2 border-dashed border-slate-100 flex flex-col items-center justify-center text-center">
                        <p class="text-xs font-medium text-slate-400 mb-1">Kurir Tidak Tersedia</p>
                        <p class="text-[11px] font-bold text-slate-300 uppercase px-10">Pilih lokasi di peta atau cari area untuk memunculkan pilihan kurir.</p>
                    </div>`;
                return;
            }

            // Detect if any rate has a significant subsidy
            const hasSubsidy = rates.some(r => r.breakdown && r.breakdown.subsidy > 0);
            let promoHeader = '';
            
            if (hasSubsidy) {
                const maxS = Math.max(...rates.map(r => r.breakdown ? r.breakdown.subsidy || 0 : 0));
                promoHeader = `
                    <div class="mb-6 p-6 bg-emerald-50 border-2 border-emerald-100 rounded-2xl flex items-center gap-5 animate-in fade-in slide-in-from-top-4">
                        <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm border border-emerald-100 shrink-0">
                            <i class="fa-solid fa-bolt-lightning text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-emerald-900 mb-0.5">Promo Mantap Bolo detected!</p>
                            <p class="text-[11px] font-bold text-emerald-700/80 leading-tight uppercase">
                                ${maxS >= 99999 ? 'Lokasi kamu terpilih mendapatkan <b>Gratis Ongkir Total</b>!' : `Asik! Kamu dapat <b>subsidi ongkir Rp${maxS.toLocaleString('id-ID')}</b> untuk area ini.`}
                            </p>
                        </div>
                    </div>
                `;
            }

            let ratesHtml = rates.map((rate, index) => {
                // First non-disabled rate should be pre-selected
                const isChecked = !rate.disabled && rates.slice(0, index).every(r => r.disabled) ? 'checked' : '';
                const themeClass = rate.type === 'local' ? 'emerald' : 'slate';
                const badgeColor = rate.type === 'local' ? 'text-emerald-500' : 'text-emerald-500';
                const disabledAttr = rate.disabled ? 'disabled' : '';
                const opacityClass = rate.disabled ? 'opacity-50 grayscale' : '';
                
                return `
                    <label class="relative cursor-pointer group block ${opacityClass}">
                        <input type="radio" name="courier_option" value="${rate.price}" 
                            data-courier="${rate.courier_name}" 
                            data-service="${rate.service}" 
                            data-service-id="${rate.courier_service_id || ''}"
                            class="peer hidden" 
                            ${isChecked} ${disabledAttr}
                            onchange="setShippingFee(${rate.price}, ${rate.breakdown ? rate.breakdown.subsidy || 0 : 0})">
                        <div class="p-6 rounded-2xl border-2 border-slate-50 peer-checked:border-emerald-600 peer-checked:bg-emerald-50/50 transition-all duration-300 flex flex-col group-hover:bg-slate-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-white border-2 border-slate-100 flex items-center justify-center peer-checked:border-emerald-600 shadow-sm group-hover:scale-105 transition-transform overflow-hidden">
                                        ${rate.courier_name.toLowerCase().includes('jek') || rate.courier_name.toLowerCase().includes('grab') || rate.courier_name.toLowerCase().includes('kurir') 
                                            ? `<div class="w-full h-full bg-emerald-600 flex items-center justify-center text-white font-bold text-xs">${rate.courier_name.substring(0,2)}</div>`
                                            : `<div class="w-full h-full bg-slate-800 flex items-center justify-center text-white font-bold text-xs">${rate.courier_name.substring(0,2)}</div>`
                                        }
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="text-sm font-bold text-slate-800 uppercase">${rate.courier_name} - ${rate.service}</p>
                                            <span class="text-xs font-semibold uppercase px-2 py-0.5 bg-slate-100 rounded-full ${badgeColor}">${rate.type}</span>
                                        </div>
                                        <p class="text-xs font-bold text-slate-400">${rate.duration} ${rate.description ? '• ' + rate.description : ''}</p>
                                    </div>
                                </div>
                                <p class="text-sm font-semibold text-emerald-600">${rate.disabled ? 'Tidak Tersedia' : 'IDR ' + new Intl.NumberFormat('id-ID').format(rate.price)}</p>
                            </div>

                            ${rate.disabled ? `
                                <div class="mt-2 text-[11px] font-bold text-red-500">
                                    ${rate.reason}
                                </div>
                            ` : ''}

                            ${!rate.disabled && rate.type === 'local' && rate.breakdown ? `
                            <div x-data="{ showDetail: false }" class="mt-3">
                                <button type="button" @click.stop="showDetail = !showDetail" 
                                    class="text-xs font-semibold text-slate-400 hover:text-emerald-600 flex items-center gap-1 transition-colors">
                                    <span x-text="showDetail ? 'Sembunyikan Rincian' : 'Lihat Rincian Biaya'"></span>
                                    <svg class="w-2.5 h-2.5 transition-transform" :class="showDetail ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                
                                <div x-show="showDetail" x-cloak x-collapse class="mt-4 pt-4 border-t border-slate-100/50 grid grid-cols-2 sm:grid-cols-5 gap-4">
                                    <div class="flex flex-col">
                                        <span class="text-[7px] font-medium text-slate-400">Ongkir Dasar (Min)</span>
                                        <span class="text-[11px] font-bold text-slate-700">Rp${rate.breakdown.base_fee.toLocaleString('id-ID')}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[7px] font-medium text-slate-400">Jarak (${rate.breakdown.distance_km}km)</span>
                                        <span class="text-[11px] font-bold text-slate-700">Rp${rate.breakdown.distance_fee.toLocaleString('id-ID')}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[7px] font-medium text-slate-400">Berat (${rate.breakdown.weight_kg}kg)</span>
                                        <span class="text-[11px] font-bold text-slate-700">Rp${rate.breakdown.weight_fee.toLocaleString('id-ID')}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[7px] font-medium text-slate-400">Multiplier</span>
                                        <span class="text-[11px] font-semibold text-emerald-600">x${rate.breakdown.multiplier}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[7px] font-medium text-slate-400">Extra Kurir</span>
                                        <span class="text-[11px] font-semibold text-emerald-600">Rp${(rate.breakdown.service_extra || 0).toLocaleString('id-ID')}</span>
                                    </div>
                                </div>
                            </div>
                            ` : ''}
                        </div>
                    </label>
                `;
            }).join('');

            courierContainer.innerHTML = promoHeader + ratesHtml;
            
            // Auto calculate for the first one if checked
            const selectedOption = document.querySelector('input[name="courier_option"]:checked');
            if (selectedOption) {
                setShippingFee(parseFloat(selectedOption.value));
            }
        }

        marker.on('dragend', () => {
            let pos = marker.getLatLng();
            updateAddress(pos.lat, pos.lng);
            
            // Check distance to the primary/first seller
            const refCoord = uniqueSellers.length > 0 ? [uniqueSellers[0].lat, uniqueSellers[0].lng] : mojokertoCoord;
            if (map.distance(pos, L.latLng(refCoord[0], refCoord[1])) > 25000) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Luar Area Utama',
                    text: 'Lokasi kamu cukup jauh dari pengirim, akan ada biaya pengiriman tambahan ya bolo!',
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000
                });
            }
        });

        // Fungsi helper untuk menghapus item dari cart utama
        function clearPurchasedFromCart() {
            try {
                const mainCart = JSON.parse(localStorage.getItem('minie_cart') || '[]');
                
                // Gunakan filter yang lebih agresif (cek ID dan Nama sebagai fallback)
                const updatedCart = mainCart.filter(item => {
                    return !checkoutItems.some(co => 
                        String(co.id) === String(item.id) || 
                        (co.nama_barang === item.nama_barang && String(co.harga) === String(item.harga))
                    );
                });

                localStorage.setItem('minie_cart', JSON.stringify(updatedCart));
                
                // Dispatch event untuk update UI (navigasi, dll)
                window.dispatchEvent(new CustomEvent('cart-updated'));
                window.dispatchEvent(new CustomEvent('notify', { detail: 'Keranjang diperbarui' }));
            } catch (e) {
                console.error('Gagal update cart:', e);
            }
        }

        // --- BITESHIP INTEGRATION ---
        const areaSearch = document.getElementById('area-search');
        const areaResults = document.getElementById('area-results');
        const courierContainer = document.getElementById('courier-container');
        const destinationAreaInput = document.getElementById('destination_area_id');
        let areaSearchTimer;

        async function performAreaSearch(query, isAuto = false) {
            if (query.length < 3) {
                areaResults.classList.add('hidden');
                return;
            }

            // Only show loader if manual search
            if (!isAuto) {
                areaResults.innerHTML = '<div class="px-6 py-4 text-xs text-slate-400 animate-pulse">Mencari area...</div>';
                areaResults.classList.remove('hidden');
            }

            try {
                const res = await fetch(`{{ route('shipping.search-area') }}?q=${encodeURIComponent(query)}`);
                const data = await res.json();

                if (data.length > 0) {
                    areaResults.classList.remove('hidden');
                    areaResults.innerHTML = data.map(area => `
                        <div class="px-6 py-4 hover:bg-slate-50 cursor-pointer border-b border-slate-50 last:border-0" 
                             onclick="selectArea('${area.id}', '${area.name}')">
                            <p class="text-sm font-bold text-slate-800">${area.name}</p>
                            <p class="text-[11px] font-bold text-emerald-500">ID: ${area.id}</p>
                        </div>
                    `).join('');
                } else {
                    if (isAuto) {
                        areaResults.classList.add('hidden');
                    } else {
                        areaResults.classList.remove('hidden');
                        areaResults.innerHTML = `
                            <div class="px-6 py-8 text-center bg-slate-50/50">
                                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                                    <i class="fa-solid fa-map-location-dot text-slate-400"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-800">Layanan Nasional Tidak Tersedia</p>
                                <p class="text-[11px] font-bold text-slate-400 uppercase leading-tight px-10">Area pelosok/tidak tercover Biteship. Silahkan gunakan <span class="text-emerald-600">SADEWA Delivery</span> melalui PIN Peta.</p>
                            </div>`;
                    }
                }
            } catch (e) {
                console.error("Area search failed", e);
                areaResults.classList.add('hidden');
            }
        }

        window.selectArea = (id, name) => {
            areaSearch.value = name;
            destinationAreaInput.value = id;
            areaResults.classList.add('hidden');
            
            // Re-fetch with new area ID
            const pos = marker.getLatLng();
            fetchShippingRates(id, pos.lat, pos.lng);
        };

        async function fetchShippingRates(areaId = null, lat = null, lng = null) {
            // Keep current couriers visible but show loading overlay for extras
            const existingRates = courierContainer.innerHTML;
            if (!existingRates.includes('animate-spin')) {
                courierContainer.innerHTML = existingRates + '<div id="rates-loading" class="flex items-center justify-center p-4"><div class="w-6 h-6 border-2 border-emerald-600 border-t-transparent rounded-full animate-spin"></div></div>';
            }
            
            try {
                const res = await fetch(`{{ route('shipping.get-rates') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        destination_area_id: areaId,
                        lat: lat,
                        lng: lng,
                        items: checkoutItems.map(item => ({
                            name: item.nama_barang,
                            price: item.harga,
                            quantity: item.quantity,
                            weight: (parseFloat(item.berat) || 1), // In KG
                            seller_id: item.user_id // Kirim ID Penjual supaya jarak akurat
                        }))
                    })
                });

                const rates = await res.json();
                renderCouriers(rates);
            } catch (e) {
                console.error("Rates fetch failed", e);
                // Fallback or keep current if error
            }
        }

        window.setShippingFee = (fee, subsidy = 0) => {
            // override manual shipping logic
            globalShippingFee = fee;
            globalSubsidy = subsidy;
            
            // Re-render to update grand total
            renderCheckout();
        }

        areaSearch.addEventListener('input', (e) => {
            clearTimeout(areaSearchTimer);
            areaSearchTimer = setTimeout(() => performAreaSearch(e.target.value), 500);
        });

        // 5. SUBMIT HANDLER
        document.getElementById('checkout-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-pay');
            const alamat = document.getElementById('alamat').value;
            const catatan_alamat = document.getElementById('catatan_alamat').value;

            if(!alamat) return Swal.fire('Error', 'Tentukan lokasi dulu bolo!', 'warning');

            // --- VALIDASI PATOKAN (Wajib & Detail) ---
            if(!catatan_alamat || catatan_alamat.trim().length < 15) {
                return Swal.fire({
                    icon: 'warning',
                    iconColor: '#10b981',
                    title: '<span class="text-lg font-semibold">Patokan Kurang Detail!</span>',
                    html: '<p class="text-xs font-bold text-slate-500 leading-relaxed">Mohon tuliskan detail rumah atau patokan (misal: warna pagar, depan toko apa, dll) <b>minimal 15 karakter</b> biar kurir gak bingung nyari alamatmu bolo!</p>',
                    confirmButtonText: 'SIAP BOLO!',
                    confirmButtonColor: '#10b981',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-8 py-3 text-xs font-semibold'
                    }
                });
            }

            btn.disabled = true;
            btn.innerHTML = '<span class="animate-pulse">SINKRONISASI STOK...</span>';

            const selectedCourier = document.querySelector('input[name="courier_option"]:checked');

            const payload = {
                alamat: alamat,
                catatan_alamat: catatan_alamat,
                payment_method: document.querySelector('input[name="payment_method"]:checked').value,
                cart: checkoutItems,
                shipping_fee: globalShippingFee,
                destination_area_id: destinationAreaInput.value,
                courier_name: selectedCourier ? selectedCourier.getAttribute('data-courier') : null,
                courier_service: selectedCourier ? selectedCourier.getAttribute('data-service') : null,
                courier_service_id: selectedCourier ? selectedCourier.getAttribute('data-service-id') : null
            };

            try {
                const response = await fetch("{{ route('checkout.process') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(payload)
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || `Error ${response.status}: ${response.statusText}`);
                }

                const result = await response.json();

                if (result.success) {
                    // LANGSUNG HAPUS DARI CART BEGITU ORDER BERHASIL DIBUAT
                    clearPurchasedFromCart();

                    if (payload.payment_method === 'midtrans') {
                        window.snap.pay(result.snap_token, {
                            onSuccess: () => finishCheckout(),
                            onPending: () => finishCheckout(),
                            onError: () => Swal.fire('Gagal', 'Pembayaran gagal!', 'error')
                        });
                    } else {
                        finishCheckout();
                    }
                } else {
                    Swal.fire('Waduh!', result.message, 'error').then(() => window.location.reload());
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Error', err.message || 'Koneksi bermasalah bolo!', 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Konfirmasi Pesanan';
            }
        });

        function finishCheckout() {
            localStorage.removeItem('checkout_items');
            Swal.fire('Sukses!', 'Pesanan diproses bolo!', 'success').then(() => {
                window.location.href = '/transactions';
            });
        }

        // Initialize Swiper or other effects
        document.addEventListener('DOMContentLoaded', () => {
            // JALANKAN PERHITUNGAN AWAL
            if (marker) {
                const pos = marker.getLatLng();
                document.getElementById('lat').value = pos.lat;
                document.getElementById('lng').value = pos.lng;
                Ongkir(pos.lat, pos.lng);
            }
        });

        // Jalankan render pertama kali
        renderCheckout();
        hydrateSellerCoordinates().then((needsRefresh) => {
            if (needsRefresh) {
                renderCheckout();
            }
        });
    </script>
</x-app-layout>

