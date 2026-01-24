<x-app-layout>
    {{-- CSS Leaflet & Custom Styling --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        #leaflet-map { z-index: 1; border: 2px solid #F1F5F9; }
        .qty-btn { @apply w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 hover:bg-indigo-50 hover:border-indigo-200 transition-all text-gray-600 font-bold; }
        
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
        .pac-matched { color: #4f46e5; }
    </style>

    {{-- Midtrans Snap.js --}}
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <div class="bg-[#F8FAFC] min-h-screen pb-20">
        <div class="max-w-7xl mx-auto pt-10 px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="flex items-center gap-4 mb-10">
                <a href="/" class="p-3 bg-white rounded-2xl shadow-sm hover:shadow-md transition-all">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">Checkout</h1>
                    <p class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em]">Selesaikan pesananmu bolo</p>
                </div>
            </div>

            <form id="checkout-form" class="grid lg:grid-cols-12 gap-8 items-start">
                @csrf
                <input type="hidden" name="lat" id="lat">
                <input type="hidden" name="lng" id="lng">

                {{-- LEFT COLUMN: Lokasi & Produk --}}
                <div class="lg:col-span-8 space-y-8">
                    
                    {{-- 1. Lokasi Section --}}
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">Alamat Pengiriman</h2>
                            </div>
                            
                            @if(auth()->user()->alamat)
                            <button type="button" 
                                onclick="useSavedAddress('{{ auth()->user()->alamat }}')"
                                class="text-[10px] font-black uppercase tracking-widest text-indigo-600 bg-indigo-50 px-4 py-2 rounded-xl hover:bg-indigo-600 hover:text-white transition-all">
                                Pakai Alamat Tersimpan
                            </button>
                            @endif
                        </div>

                        @if(auth()->user()->alamat)
                        <div class="mb-6 p-4 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Alamat Kamu Saat Ini:</p>
                            <p class="text-sm font-bold text-slate-700 leading-relaxed">{{ auth()->user()->alamat }}</p>
                        </div>
                        @else
                        <div class="mb-6 p-4 bg-orange-50 border-2 border-dashed border-orange-200 rounded-2xl">
                            <p class="text-sm font-bold text-orange-700">Bolo, kamu belum simpan alamat di profil. Tentukan lokasi di bawah ya!</p>
                        </div>
                        @endif
                        
                        {{-- Search Input with Autocomplete --}}
                        <div class="relative mb-6 group">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text" id="map-search" 
                                class="w-full bg-slate-50 border-2 border-slate-50 focus:border-indigo-500 focus:bg-white rounded-[1.5rem] pl-14 pr-6 py-4 text-sm transition-all outline-none" 
                                placeholder="Cari nama jalan, toko, atau gedung di Mojokerto...">
                            
                            {{-- Dropdown Hasil Pencarian (Free Version) --}}
                            <div id="search-results" class="absolute z-[100] w-full mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 hidden overflow-hidden">
                                {{-- Hasil akan muncul di sini --}}
                            </div>
                        </div>

                        <div id="leaflet-map" class="h-[300px] rounded-[2rem] mb-6 overflow-hidden shadow-inner border-2 border-slate-50"></div>
                        
                        <div class="flex items-center gap-3 mb-6 p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100">
                            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest leading-none mb-1">Info Pengiriman</p>
                                <p class="text-xs font-bold text-slate-600 leading-tight" id="shipping-info-text">Barang dikirim dari toko terdekat.</p>
                            </div>
                        </div>

                        <div class="relative">
                            <textarea name="alamat" id="alamat" rows="3" 
                                class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm focus:ring-2 focus:ring-indigo-500 transition-all placeholder:text-gray-400"
                                placeholder="Detail alamat (No. Rumah, RT/RW, Patokan). Ketik di sini juga bisa menggerakkan peta..." required></textarea>
                        </div>
                    </div>

                    {{-- 2. Daftar Produk Section --}}
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">Pesanan Anda</h2>
                        </div>

                        <div id="product-list" class="space-y-6">
                            </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Payment & Summary --}}
                <div class="lg:col-span-4 space-y-8 lg:sticky lg:top-28">
                    
                    {{-- 3. Payment Method --}}
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
                        <h2 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-6">Metode Pembayaran</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="payment_method" value="midtrans" class="peer hidden" checked>
                                <div class="p-4 rounded-2xl border-2 border-gray-100 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition-all text-center">
                                    <span class="block text-[10px] font-black text-gray-400 peer-checked:text-indigo-600 uppercase">Transfer / VA</span>
                                    <span class="text-sm font-black text-gray-900">Midtrans</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="payment_method" value="cod" class="peer hidden">
                                <div class="p-4 rounded-2xl border-2 border-gray-100 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition-all text-center">
                                    <span class="block text-[10px] font-black text-gray-400 peer-checked:text-indigo-600 uppercase">Bayar Ditempat</span>
                                    <span class="text-sm font-black text-gray-900">COD</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- 4. Ringkasan Belanja --}}
                    <div class="bg-[#0F172A] rounded-[2.5rem] p-8 text-white shadow-2xl">
                        <h2 class="text-lg font-black tracking-tighter mb-6 uppercase">Ringkasan Pesanan</h2>
                        <div class="space-y-4 text-sm font-medium opacity-80">
                            <div class="flex justify-between">
                                <span>Subtotal Produk</span>
                                <span id="summary-subtotal">Rp0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Biaya Layanan</span>
                                <span>Rp2.500</span>
                            </div>
                            <div class="flex justify-between text-indigo-300">
                                <span>Total Berat</span>
                                <span id="total-weight-display">0 Kg</span>
                            </div>
                            <div class="flex justify-between text-indigo-400 font-bold group" id="ongkir-row">
                                <div class="flex items-center gap-2">
                                    <span>Biaya Pengiriman</span>
                                    <span id="dist-label" class="text-[9px] bg-indigo-500/20 px-2 py-0.5 rounded-full text-indigo-300">0km</span>
                                </div>
                                <span id="summary-ongkir">Rp0</span>
                            </div>
                        </div>
                        <div class="my-6 border-t border-white/10 pt-6 flex justify-between items-end">
                            <div>
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Total Pembayaran</p>
                                <p id="total-price-display" class="text-3xl font-black tracking-tighter">Rp0</p>
                            </div>
                        </div>
                        <button type="submit" id="btn-pay" 
                            class="w-full bg-indigo-600 hover:bg-indigo-700 py-5 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-indigo-900/20 transition-all active:scale-95">
                            Konfirmasi Pesanan
                        </button>
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

        // 2. RENDER SUMMARY & ITEMS
        function renderCheckout() {
            if (checkoutItems.length === 0) return window.location.href = '/';
            
            let subtotal = 0;
            let totalBerat = 0;
            const productContainer = document.getElementById('product-list');
            
            productContainer.innerHTML = checkoutItems.map((item, index) => {
                const itemTotal = item.harga * item.quantity;
                const itemBerat = (item.berat || 1000) * item.quantity;
                subtotal += itemTotal;
                totalBerat += itemBerat;
                
                return `
                <div class="flex flex-col sm:flex-row items-center gap-6 p-4 rounded-3xl border border-gray-50 hover:bg-gray-50 transition-all">
                    <img src="${item.gambar}" class="w-24 h-24 rounded-2xl object-cover shadow-sm">
                    <div class="flex-1 text-center sm:text-left">
                        <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mb-1">${item.seller_name || 'Official Store'}</p>
                        <h3 class="font-black text-gray-900 text-base mb-1 truncate">${item.nama_barang}</h3>
                        <p class="text-gray-400 font-bold text-[10px] uppercase">Harga: Rp${item.harga.toLocaleString('id-ID')} | Berat: ${item.berat || 1000}g</p>
                    </div>
                    <div class="flex items-center gap-4 bg-white px-4 py-2 rounded-2xl border border-gray-100 shadow-sm">
                        <button type="button" onclick="updateQty(${index}, -1)" class="qty-btn">-</button>
                        <span class="font-black text-gray-900 min-w-[20px] text-center">${item.quantity}</span>
                        <button type="button" onclick="updateQty(${index}, 1)" class="qty-btn">+</button>
                    </div>
                    <div class="text-right min-w-[120px]">
                        <p class="font-black text-gray-900">Rp${itemTotal.toLocaleString('id-ID')}</p>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">${(itemBerat/1000).toFixed(1)} Kg</p>
                    </div>
                </div>`;
            }).join('');

            const grandTotal = subtotal + 2500 + globalShippingFee;
            document.getElementById('summary-subtotal').innerText = `Rp${subtotal.toLocaleString('id-ID')}`;
            document.getElementById('summary-ongkir').innerText = `Rp${globalShippingFee.toLocaleString('id-ID')}`;
            document.getElementById('total-price-display').innerText = `Rp${grandTotal.toLocaleString('id-ID')}`;
            
            // Render weight badge in summary if needed
            const weightDisplay = document.getElementById('total-weight-display');
            if (weightDisplay) {
                weightDisplay.innerText = `${(totalBerat/1000).toFixed(1)} Kg`;
            }

            // Update Info Pengiriman Text
            const infoText = document.getElementById('shipping-info-text');
            if (infoText && checkoutItems.length > 0) {
                infoText.innerHTML = `Barang dikirim dari <span class="text-indigo-600 font-black">${checkoutItems[0].seller_name || 'Toko'}</span> di <span class="text-indigo-600 font-black">${checkoutItems[0].lokasi || 'Mojokerto'}</span>.`;
            }
        }

        // 3. QUANTITY LOGIC
        window.updateQty = (index, change) => {
            const item = checkoutItems[index];
            const newQty = item.quantity + change;

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
            
            // RE-CALCULATE ONGKIR
            const lat = document.getElementById('lat').value;
            const lng = document.getElementById('lng').value;
            if(lat && lng) {
                Ongkir(lat, lng);
            } else {
                renderCheckout();
            }
        }

        window.useSavedAddress = async (address) => {
            if(!address) return;
            
            const alamatArea = document.getElementById('alamat');
            alamatArea.value = address;
            
            // Animasi feedback
            alamatArea.classList.add('ring-4', 'ring-indigo-500/20');
            setTimeout(() => alamatArea.classList.remove('ring-4', 'ring-indigo-500/20'), 1000);

            // Coba geocoding biar peta pindah (Free version)
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address + ' Mojokerto')}&limit=1`, {
                    headers: { 'User-Agent': 'MiniQ-Store-App' }
                });
                const data = await response.json();
                if (data.length > 0) {
                    const { lat, lon } = data[0];
                    selectLocation(lat, lon, address, false);
                    Swal.fire({
                        icon: 'success',
                        title: 'Alamat Dipasang',
                        text: 'Peta otomatis bergeser ke lokasi simpananmu bolo!',
                        toast: true, position: 'top-end', showConfirmButton: false, timer: 3000
                    });
                }
            } catch (error) {
                console.log("Geocoding failed, manual mapping kept");
            }
        };

        {{-- 4. MAP SETUP (FOKUS MOJOKERTO) ---}}
        const mojokertoCoord = [-7.4726, 112.4382];
        
        // Tentukan Titik Pengirim (Seller)
        let originCoord = mojokertoCoord;
        if (checkoutItems.length > 0 && checkoutItems[0].seller_lat && checkoutItems[0].seller_lng) {
            originCoord = [parseFloat(checkoutItems[0].seller_lat), parseFloat(checkoutItems[0].seller_lng)];
        }

        let map = L.map('leaflet-map', { 
            zoomControl: false,
            attributionControl: false 
        }).setView(mojokertoCoord, 14);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(map);

        // Marker Toko (Origin) - CUSTOM ICON BIAR KEREN
        const storeIcon = L.divIcon({
            className: 'custom-div-icon',
            html: `
                <div class="relative flex items-center justify-center">
                    <div class="absolute animate-ping w-8 h-8 bg-indigo-500/30 rounded-full"></div>
                    <div class="relative w-6 h-6 bg-indigo-600 rounded-lg flex items-center justify-center border-2 border-white shadow-lg">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                </div>`,
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        let storeMarker = L.marker(originCoord, { icon: storeIcon }).addTo(map);
        storeMarker.bindTooltip(`<div class="font-black text-[9px] uppercase tracking-widest text-indigo-600 p-1">Toko: ${checkoutItems[0].seller_name || 'Official Store'}</div>`, { permanent: true, direction: 'top', offset: [0, -10] });

        // Marker Pembeli (User)
        const userIcon = L.divIcon({
            className: 'custom-div-icon',
            html: `
                <div class="relative flex items-center justify-center">
                    <div class="w-10 h-10 bg-orange-600 border-4 border-white rounded-full flex items-center justify-center shadow-2xl overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name=You&background=EA580C&color=fff" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -bottom-1 bg-orange-600 text-white text-[8px] font-black px-2 py-0.5 rounded-full uppercase tracking-tighter">Lokasimu</div>
                </div>`,
            iconSize: [40, 40],
            iconAnchor: [20, 20]
        });

        let marker = L.marker(mojokertoCoord, {draggable: true, icon: userIcon}).addTo(map);

        // UI Friendly Delivery Radius
        let deliveryArea = L.circle(originCoord, {
            color: '#4f46e5', weight: 1, dashArray: '5, 10', fillColor: '#4f46e5', fillOpacity: 0.05, radius: 5000 
        }).addTo(map);

        const locateBtn = L.control({position: 'topright'});
        locateBtn.onAdd = function() {
            let div = L.DomUtil.create('div', 'bg-white p-3 rounded-2xl shadow-xl cursor-pointer hover:scale-110 transition-all border border-slate-100');
            div.innerHTML = `<svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`;
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
                        <div class="px-6 py-4 hover:bg-indigo-50 cursor-pointer border-b border-slate-50 last:border-0 transition-colors" 
                             onclick="selectLocation(${item.lat}, ${item.lon}, '${item.display_name.replace(/'/g, "\\'")}')">
                            <div class="flex items-start gap-4">
                                <div class="mt-1 flex-shrink-0 w-8 h-8 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div class="flex-1 overflow-hidden">
                                    <p class="text-sm font-black text-slate-800 truncate mb-0.5">${mainTitle}</p>
                                    <p class="text-[10px] font-bold text-slate-400 truncate uppercase tracking-tight">${subTitle}</p>
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
                    alamatArea.value = data.display_name;
                }
            } catch(e) {
                console.error("Reverse geocoding failed");
            }
        }

        function Ongkir(lat, lng) {
            const pos = L.latLng(lat, lng);
            const dist = map.distance(pos, L.latLng(originCoord[0], originCoord[1])); 
            const km = dist / 1000;
            
            // Update UI agar lebih informatif
            const distLabel = document.getElementById('dist-label');
            distLabel.innerText = `${km.toFixed(1)} km dari toko`;
            distLabel.className = km <= 5 
                ? "text-[9px] bg-emerald-500/20 px-2 py-0.5 rounded-full text-emerald-400 font-bold"
                : "text-[9px] bg-indigo-500/20 px-2 py-0.5 rounded-full text-indigo-300 font-bold";

            // HITUNG TOTAL BERAT
            let totalWeightGrams = 0;
            checkoutItems.forEach(item => {
                totalWeightGrams += (item.berat || 1000) * item.quantity;
            });
            const totalWeightKg = Math.ceil(totalWeightGrams / 1000);

            // LOGIKA ONGKIR USER FRIENDLY
            let baseFee = 0;
            if (km <= 2) baseFee = 0; // Dekat banget, gratisin aja bolo
            else if (km <= 5) baseFee = 5000;
            else if (km <= 15) baseFee = 10000;
            else if (km <= 30) baseFee = 25000;
            else baseFee = 50000;

            globalShippingFee = baseFee * totalWeightKg;

            renderCheckout();
        }

        marker.on('dragend', () => {
            let pos = marker.getLatLng();
            updateAddress(pos.lat, pos.lng);
            
            if (map.distance(pos, L.latLng(originCoord[0], originCoord[1])) > 25000) {
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

        // 5. SUBMIT HANDLER
        document.getElementById('checkout-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-pay');
            const alamat = document.getElementById('alamat').value;

            if(!alamat) return Swal.fire('Error', 'Tentukan lokasi dulu bolo!', 'warning');

            btn.disabled = true;
            btn.innerHTML = '<span class="animate-pulse">SINKRONISASI STOK...</span>';

            const payload = {
                alamat: alamat,
                payment_method: document.querySelector('input[name="payment_method"]:checked').value,
                cart: checkoutItems,
                shipping_fee: globalShippingFee
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

        // Jalankan render pertama kali
        renderCheckout();
    </script>
</x-app-layout>