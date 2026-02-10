<x-admin-layout>
    {{-- CSS Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #delivery-map { z-index: 1; border: 2px solid #F1F5F9; }
    </style>

    <div class="max-w-5xl mx-auto py-8 px-4">
        
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-600 font-bold text-sm shadow-sm animate-bounce">
                <i class="fa-solid fa-circle-check text-lg"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- Top Navigation --}}
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('courier.deliveries.index') }}" class="inline-flex items-center gap-2 text-slate-500 font-black text-[10px] uppercase tracking-widest hover:text-blue-600 transition-all">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <div class="px-4 py-1.5 {{ $transaction->status === 'shipped' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100' }} border rounded-full text-[10px] font-black uppercase tracking-widest">
                {{ $transaction->status === 'shipped' ? 'Sedang Diantar' : 'Tugas Selesai' }}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Left: Locations --}}
            <div class="lg:col-span-8 space-y-6">
                {{-- Address Card --}}
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-100 ring-4 ring-blue-50">
                            <i class="fa-solid fa-route text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-900 leading-none tracking-tight">RUTE PENGIRIMAN</h2>
                            <p class="text-[10px] font-bold text-slate-400 uppercase mt-1 tracking-widest">Gunakan navigasi untuk rute akurat bolo!</p>
                        </div>
                    </div>

                    <div id="delivery-map" class="w-full h-96 rounded-3xl mb-8 shadow-inner overflow-hidden border-4 border-slate-50"></div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        {{-- Pickup Info --}}
                        <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 relative overflow-hidden group hover:bg-blue-50/50 hover:border-blue-100 transition-all duration-500">
                            <div class="absolute -right-6 -top-6 text-slate-100 group-hover:text-blue-100 group-hover:scale-125 transition-all duration-700">
                                <i class="fa-solid fa-store text-8xl opacity-20"></i>
                            </div>
                            <div class="relative">
                                <span class="px-3 py-1 bg-blue-600 text-white text-[9px] font-black rounded-lg uppercase mb-3 inline-block tracking-widest">1. TITIK JEMPUT</span>
                                <p class="text-base font-black text-slate-900 truncate">{{ $transaction->seller->nama_toko ?? $transaction->seller->name }}</p>
                                <p class="text-[11px] font-bold text-slate-500 mt-1 leading-relaxed">{{ $transaction->seller->alamat }}</p>
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $transaction->seller->latitude }},{{ $transaction->seller->longitude }}" 
                                    target="_blank" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-white text-blue-600 text-[10px] font-black uppercase rounded-xl border border-blue-100 shadow-sm hover:bg-blue-600 hover:text-white transition-all">
                                    Lihat Rute <i class="fa-solid fa-location-arrow"></i>
                                </a>
                            </div>
                        </div>

                        {{-- Final Destination --}}
                        <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 relative overflow-hidden group hover:bg-emerald-50/50 hover:border-emerald-100 transition-all duration-500">
                            <div class="absolute -right-6 -top-6 text-slate-100 group-hover:text-emerald-100 group-hover:scale-125 transition-all duration-700">
                                <i class="fa-solid fa-house-user text-8xl opacity-20"></i>
                            </div>
                            <div class="relative">
                                <span class="px-3 py-1 bg-emerald-600 text-white text-[9px] font-black rounded-lg uppercase mb-3 inline-block tracking-widest">2. TITIK ANTAR</span>
                                <p class="text-base font-black text-slate-900 truncate">{{ $transaction->user->name }}</p>
                                <p class="text-[11px] font-bold text-slate-500 mt-1 leading-relaxed">{{ $transaction->alamat }}</p>
                                @php
                                    $dLat = $transaction->lat ?: ($transaction->user->latitude ?? null);
                                    $dLng = $transaction->lng ?: ($transaction->user->longitude ?? null);
                                    $destCoord = ($dLat && $dLng) ? "{$dLat},{$dLng}" : urlencode($transaction->alamat);
                                @endphp
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $destCoord }}" 
                                    target="_blank" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-white text-emerald-600 text-[10px] font-black uppercase rounded-xl border border-emerald-100 shadow-sm hover:bg-emerald-600 hover:text-white transition-all">
                                    Lihat Rute <i class="fa-solid fa-map-location-dot"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    @if($transaction->catatan_alamat)
                        <div class="mt-6 p-6 bg-amber-50 rounded-3xl border border-amber-100 ring-4 ring-amber-50/50">
                            <p class="text-[10px] font-black text-amber-600 uppercase tracking-[0.2em] mb-2 flex items-center gap-2">
                                <i class="fa-solid fa-comment-dots text-lg"></i> Patokan Khusus
                            </p>
                            <p class="text-sm font-bold text-amber-900 italic leading-relaxed">"{{ $transaction->catatan_alamat }}"</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4 mt-8">
                        @if($transaction->user->phone)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $transaction->user->phone) }}" 
                            target="_blank"
                            class="flex items-center justify-center gap-2 py-5 bg-emerald-500 text-white rounded-[1.5rem] font-black text-[10px] uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-xl shadow-emerald-100 active:scale-95">
                            <i class="fa-brands fa-whatsapp text-lg"></i> Chat WA
                        </a>
                        @endif
                        <a href="tel:{{ $transaction->user->phone }}" 
                            class="flex items-center justify-center gap-2 py-5 bg-white border-2 border-slate-100 text-slate-700 rounded-[1.5rem] font-black text-[10px] uppercase tracking-widest hover:bg-slate-50 transition-all active:scale-95">
                            <i class="fa-solid fa-phone"></i> Hubungi
                        </a>
                    </div>
                </div>
            </div>

            {{-- Right: Action Panel --}}
            <div class="lg:col-span-4 space-y-6">
                {{-- Payment Info --}}
                <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 bg-emerald-500/10 rounded-full -mr-16 -mt-16 blur-3xl"></div>
                    <h3 class="text-[10px] font-black text-emerald-400 uppercase tracking-[0.3em] mb-8">INFO PESANAN</h3>
                    <div class="space-y-6">
                        <div class="flex justify-between items-center border-b border-white/5 pb-4">
                            <span class="text-[11px] text-slate-400 font-bold uppercase tracking-tight">Metode Bayar</span>
                            <span class="text-xs font-black uppercase tracking-widest">{{ $transaction->payment_method }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-white/5 pb-4">
                            <span class="text-[11px] text-slate-400 font-bold uppercase tracking-tight">Invoice</span>
                            <span class="text-xs font-black">#{{ $transaction->invoice_number }}</span>
                        </div>
                        <div>
                            <span class="text-[11px] text-slate-400 font-bold uppercase tracking-tight block mb-1">Total Tagihan</span>
                            <span class="text-2xl font-black text-white tracking-tighter">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Completion Form --}}
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-8">
                    @if($transaction->status === 'shipped')
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-8">KONFIRMASI SELESAI</h3>
                        <form action="{{ route('courier.deliveries.complete', $transaction->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest ml-1 block">Bukti Foto Penerima</label>
                                <div class="relative group">
                                    <input type="file" name="delivery_proof" required accept="image/*" class="hidden" id="proof-upload" onchange="previewImage(this)">
                                    <label for="proof-upload" class="flex flex-col items-center justify-center w-full h-56 border-4 border-dashed border-slate-100 rounded-[2rem] cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all duration-500 group overflow-hidden">
                                        <div id="upload-placeholder" class="flex flex-col items-center p-6 text-center">
                                            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 mb-4 group-hover:scale-110 transition-transform">
                                                <i class="fa-solid fa-camera text-2xl"></i>
                                            </div>
                                            <span class="text-[10px] font-black text-slate-400 group-hover:text-blue-600 transition-colors uppercase tracking-[0.2em]">AMBIL FOTO BUKTI</span>
                                            <p class="text-[9px] font-bold text-slate-300 mt-2 italic px-4">Pastikan wajah/barang terlihat jelas bolo!</p>
                                        </div>
                                        <img id="upload-preview" class="hidden w-full h-full object-cover">
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-6 bg-blue-600 text-white rounded-[1.5rem] font-black text-[11px] uppercase tracking-[0.3em] shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all active:scale-95 flex items-center justify-center gap-3">
                                <span>SIMPAN & SELESAI</span>
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </form>
                    @else
                        <div class="text-center py-6">
                            <div class="w-20 h-20 bg-emerald-50 rounded-[2rem] flex items-center justify-center text-emerald-600 mx-auto mb-6 shadow-xl shadow-emerald-50 border-4 border-white">
                                <i class="fa-solid fa-check-double text-2xl"></i>
                            </div>
                            <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest leading-none mb-1">MANTAP BOLO!</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Tugas Berhasil Diselesaikan</p>
                            @if($transaction->delivery_proof)
                                <div class="mt-8 rounded-3xl overflow-hidden border-4 border-slate-50 shadow-lg">
                                    <img src="{{ asset($transaction->delivery_proof) }}" class="w-full h-auto">
                                </div>
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
                        html: '<div class="w-8 h-8 bg-blue-600 rounded-lg border-2 border-white shadow-lg flex items-center justify-center text-white"><i class="fa-solid fa-store text-xs"></i></div>',
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
                        html: '<div class="w-8 h-8 bg-emerald-600 rounded-lg border-2 border-white shadow-lg flex items-center justify-center text-white"><i class="fa-solid fa-house-user text-xs"></i></div>',
                        iconSize: [32, 32],
                        iconAnchor: [16, 16]
                    })
                }).addTo(map).bindPopup("<b>Lokasi Antar (Pembeli)</b><br>{{ $transaction->user->name }}");
                bounds.push([bLat, bLng]);

                if (sLat && sLng) {
                    // Draw line between pickup and dropoff
                    L.polyline([[sLat, sLng], [bLat, bLng]], {color: '#3b82f6', weight: 4, opacity: 0.6, dashArray: '10, 15'}).addTo(map);
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