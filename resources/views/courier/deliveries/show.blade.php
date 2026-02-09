<x-courier-layout>
    {{-- CSS Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #delivery-map { z-index: 1; border: 2px solid #F1F5F9; }
    </style>

    <div class="max-w-4xl mx-auto py-10 px-4">
        
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-600 font-bold text-sm shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3 text-rose-600 font-bold text-sm shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Top Navigation --}}
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('courier.deliveries.index') }}" class="inline-flex items-center gap-2 text-emerald-600 font-bold text-xs hover:gap-3 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                Tugas Antar
            </a>
            <div class="px-4 py-1.5 bg-blue-50 text-blue-600 border border-blue-100 rounded-xl text-[10px] font-black uppercase tracking-tight">
                {{ $transaction->status === 'shipped' ? 'Sedang Diantar' : 'Selesai' }}
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Left: Locations --}}
            <div class="md:col-span-2 space-y-6">
                {{-- Address Card --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 leading-none">Rute Pengiriman</h2>
                            <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Pickup di toko & antar ke pembeli bolo!</p>
                        </div>
                    </div>

                    <div id="delivery-map" class="w-full h-80 rounded-2xl mb-6 shadow-inner overflow-hidden border-2 border-slate-50"></div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Pickup Info --}}
                        <div class="p-5 bg-emerald-50/50 rounded-2xl border border-emerald-100 relative overflow-hidden group">
                            <div class="absolute -right-4 -top-4 text-emerald-100/50 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-store text-6xl"></i>
                            </div>
                            <div class="relative">
                                <span class="px-2 py-0.5 bg-emerald-600 text-white text-[8px] font-bold rounded uppercase mb-2 inline-block">1. Titik Jemput (Toko)</span>
                                <p class="text-sm font-black text-slate-900 truncate">{{ $transaction->seller->nama_toko ?? $transaction->seller->name }}</p>
                                <p class="text-[10px] font-bold text-slate-500 mt-1 line-clamp-2">{{ $transaction->seller->alamat }}</p>
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $transaction->seller->latitude }},{{ $transaction->seller->longitude }}" 
                                    target="_blank" class="mt-3 inline-flex items-center gap-1.5 text-[10px] font-bold text-emerald-600 hover:underline">
                                    Navigasi Pickup <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                            </div>
                        </div>

                        {{-- Final Destination --}}
                        <div class="p-5 bg-orange-50/50 rounded-2xl border border-orange-100 relative overflow-hidden group">
                            <div class="absolute -right-4 -top-4 text-orange-100/50 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-house-user text-6xl"></i>
                            </div>
                            <div class="relative">
                                <span class="px-2 py-0.5 bg-orange-600 text-white text-[8px] font-bold rounded uppercase mb-2 inline-block">2. Titik Antar (Tujuan)</span>
                                <p class="text-sm font-black text-slate-900 truncate">{{ $transaction->user->name }}</p>
                                <p class="text-[10px] font-bold text-slate-500 mt-1 line-clamp-2">{{ $transaction->alamat }}</p>
                                @php
                                    $dLat = $transaction->lat ?: ($transaction->user->latitude ?? null);
                                    $dLng = $transaction->lng ?: ($transaction->user->longitude ?? null);
                                    $destCoord = ($dLat && $dLng) ? "{$dLat},{$dLng}" : urlencode($transaction->alamat);
                                @endphp
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $destCoord }}" 
                                    target="_blank" class="mt-3 inline-flex items-center gap-1.5 text-[10px] font-bold text-orange-600 hover:underline">
                                    Navigasi Antar <i class="fa-solid fa-location-arrow"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    @if($transaction->catatan_alamat)
                        <div class="mt-4 p-5 bg-amber-50 rounded-2xl border border-amber-100">
                            <p class="text-[11px] font-bold text-amber-600 uppercase tracking-tighter mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-comment-dots"></i> Patokan / Catatan Pembeli
                            </p>
                            <p class="text-sm font-bold text-amber-900 italic">"{{ $transaction->catatan_alamat }}"</p>
                        </div>
                    @endif

                    <div class="flex gap-4 mt-6">
                        @if($transaction->user->phone)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $transaction->user->phone) }}" 
                            target="_blank"
                            class="flex-1 flex items-center justify-center gap-2 py-4 bg-emerald-500 text-white rounded-2xl font-bold text-xs hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-100">
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                            Hubungi Pembeli
                        </a>
                        @endif
                        <a href="tel:{{ $transaction->user->phone }}" 
                            class="flex-1 flex items-center justify-center gap-2 py-4 bg-white border-2 border-slate-100 text-slate-700 rounded-2xl font-bold text-xs hover:bg-slate-50 transition-all">
                            <i class="fa-solid fa-phone"></i>
                            Telepon
                        </a>
                    </div>
                </div>
            </div>

            {{-- Right: Proof & Complete --}}
            <div class="space-y-6">
                {{-- Payment Details --}}
                <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-xl">
                    <h3 class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-6">Informasi Pesanan</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-400 font-bold">Metode</span>
                            <span class="text-xs font-black uppercase tracking-tight">{{ $transaction->payment_method }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-400 font-bold">Tagihan</span>
                            <span class="text-lg font-black text-emerald-400 tracking-tighter">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Action Panel --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                    @if($transaction->status === 'shipped')
                        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-6">Selesaikan Tugas</h3>
                        <form action="{{ route('courier.deliveries.complete', $transaction->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase ml-1 block mb-3">Foto Bukti Pengiriman</label>
                                <div class="relative group">
                                    <input type="file" name="delivery_proof" required accept="image/*" class="hidden" id="proof-upload" onchange="previewImage(this)">
                                    <label for="proof-upload" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-slate-200 rounded-2xl cursor-pointer hover:border-emerald-500 hover:bg-emerald-50 transition-all duration-300 group">
                                        <div id="upload-placeholder" class="flex flex-col items-center">
                                            <svg class="w-8 h-8 text-slate-300 group-hover:text-emerald-500 mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <span class="text-[10px] font-bold text-slate-400 group-hover:text-emerald-600 transition-colors uppercase">Klik untuk Foto</span>
                                        </div>
                                        <img id="upload-preview" class="hidden w-full h-full object-cover rounded-2xl">
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg hover:bg-emerald-600 transition-all active:scale-95">
                                Konfirmasi & Selesai
                            </button>
                        </form>
                    @else
                        <div class="text-center py-6">
                            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mx-auto mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <h4 class="text-sm font-black text-slate-900 uppercase">Tugas Selesai</h4>
                            @if($transaction->delivery_proof)
                                <img src="{{ asset($transaction->delivery_proof) }}" class="mt-4 w-full rounded-2xl border border-slate-100 shadow-sm">
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Seller (Pickup)
            var sLat = {{ $transaction->seller->latitude ?? 'null' }};
            var sLng = {{ $transaction->seller->longitude ?? 'null' }};
            // Buyer (Dropoff)
            var bLat = {{ $transaction->lat ?? 'null' }};
            var bLng = {{ $transaction->lng ?? 'null' }};
            
            var map = L.map('delivery-map').setView([sLat || -7.4726, sLng || 112.4385], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            var bounds = [];

            // Add Seller Marker
            if (sLat && sLng) {
                var sellerMarker = L.marker([sLat, sLng], {
                    icon: L.divIcon({
                        className: 'custom-icon',
                        html: '<div class="w-8 h-8 bg-emerald-600 rounded-lg border-2 border-white shadow-lg flex items-center justify-center text-white"><i class="fa-solid fa-store text-xs"></i></div>',
                        iconSize: [32, 32],
                        iconAnchor: [16, 16]
                    })
                }).addTo(map).bindPopup("<b>Lokasi Jemput (Toko)</b><br>{{ $transaction->seller->nama_toko }}");
                bounds.push([sLat, sLng]);
            }

            // Add Buyer Marker
            if (bLat && bLng) {
                var buyerMarker = L.marker([bLat, bLng], {
                    icon: L.divIcon({
                        className: 'custom-icon',
                        html: '<div class="w-8 h-8 bg-orange-600 rounded-lg border-2 border-white shadow-lg flex items-center justify-center text-white"><i class="fa-solid fa-house-user text-xs"></i></div>',
                        iconSize: [32, 32],
                        iconAnchor: [16, 16]
                    })
                }).addTo(map).bindPopup("<b>Lokasi Antar (Pembeli)</b><br>{{ $transaction->user->name }}");
                bounds.push([bLat, bLng]);

                if (sLat && sLng) {
                    // Draw line between pickup and dropoff
                    L.polyline([[sLat, sLng], [bLat, bLng]], {color: '#10b981', weight: 3, dashArray: '5, 10'}).addTo(map);
                }
            } else {
                // Fallback for buyer if no lat/lng bolo
                var address = "{{ $transaction->alamat }}";
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            var nLat = data[0].lat;
                            var nLon = data[0].lon;
                            L.marker([nLat, nLon]).addTo(map).bindPopup("<b>Lokasi Antar (Estimasi)</b>");
                            bounds.push([nLat, nLon]);
                            map.fitBounds(bounds, {padding: [50, 50]});
                        }
                    });
            }

            if (bounds.length > 0) {
                map.fitBounds(bounds, {padding: [50, 50]});
            }
        });

        function previewImage(input) {
            const preview = document.getElementById('upload-preview');
            const placeholder = document.getElementById('upload-placeholder');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @endpush
</x-admin-layout>