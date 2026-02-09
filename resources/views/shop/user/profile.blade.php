<x-admin-layout>
    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .custom-div-icon {
            background: none !important;
            border: none !important;
        }
        #seller-map {
            z-index: 10;
        }
    </style>
    @endpush

    <x-slot name="header">
        <div class="flex items-center gap-3" data-aos="fade-right">
            <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <h2 class="font-bold text-xl text-gray-800 leading-tight pl-1">
                Account <span class="text-emerald-600">Settings</span>
            </h2>
        </div>
    </x-slot>

    <div class="pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                {{-- KIRI: PROFILE CARD & AVATAR --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white/80 backdrop-blur-md p-8 rounded-2xl shadow-lg shadow-gray-200/50 border border-white text-center" data-aos="fade-up">
                        <div class="relative inline-block group">
                            <div class="w-44 h-44 rounded-full p-2 border-2 border-dashed border-emerald-200 group-hover:border-emerald-500 transition-all duration-500">
                                <div class="w-full h-full rounded-full overflow-hidden border-4 border-white shadow-lg bg-gray-50">
                                    <img id="avatar-preview" 
                                         src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=059669&color=fff&size=200' }}" 
                                         class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                </div>
                            </div>
                            
                            <label for="avatar-input" class="absolute bottom-2 right-2 w-12 h-12 bg-emerald-600 text-white rounded-2xl flex items-center justify-center cursor-pointer shadow-xl hover:bg-emerald-700 hover:scale-110 active:scale-95 transition-all border-4 border-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" stroke-width="2"/><path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"/></svg>
                            </label>
                        </div>

                        <div class="mt-8">
                            <h3 class="text-2xl font-bold text-gray-900 tracking-tight">{{ auth()->user()->name }}</h3>
                            <div class="inline-flex items-center px-4 py-1.5 bg-emerald-50 text-emerald-600 rounded-full text-xs font-semibold mt-3">
                                {{ auth()->user()->roles->first()->name ?? 'Member' }}
                            </div>
                        </div>

                        @hasanyrole('admin|seller')
                        <div class="mt-6 flex flex-col gap-3">
                            <button onclick="document.getElementById('banner-input').click()" class="w-full px-6 py-3 bg-slate-900 text-white rounded-2xl text-xs font-semibold hover:bg-black transition-all shadow-lg flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Change Shop Banner
                            </button>
                            <a href="{{ route('shop.public', ['seller_id' => auth()->id()]) }}" target="_blank" class="w-full px-6 py-3 bg-emerald-600 text-white rounded-2xl text-xs font-semibold hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Preview My Shop
                            </a>
                            <p class="text-[11px] text-gray-400 font-bold mt-1 text-center">* Preview bagaimana tokomu tampil bagi pembeli</p>
                        </div>
                        @endhasanyrole

                        <div class="mt-10 pt-10 border-t border-gray-100 grid grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 rounded-2xl">
                                <span class="block text-2xl font-bold text-gray-900 leading-none">{{ auth()->user()->transactions()->count() }}</span>
                                <span class="text-[11px] font-bold text-gray-400 mt-1 block">Orders</span>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-2xl">
                                <span class="block text-2xl font-bold text-gray-900 leading-none">{{ $followedSellers->count() }}</span>
                                <span class="text-[11px] font-bold text-gray-400 mt-1 block">Following</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KANAN: FORMS --}}
                <div class="lg:col-span-2 space-y-10">
                    <div class="bg-white/80 backdrop-blur-md p-8 sm:p-12 rounded-[3.5rem] shadow-lg shadow-gray-200/50 border border-white" data-aos="fade-up" data-aos-delay="100">
                        <div class="max-w-3xl">
                            <div class="mb-12">
                                <h3 class="text-xl font-bold text-gray-900">Toko <span class="text-emerald-600">Diikuti</span></h3>
                                <div class="w-16 h-1 bg-emerald-600 mt-2 rounded-full"></div>
                                <p class="text-sm text-gray-500 mt-4 font-medium">Daftar toko yang kamu ikuti untuk melihat update produk terbaru.</p>
                            </div>
                            
                            @if($followedSellers->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($followedSellers as $followed)
                                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-emerald-200 transition-all group">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-sm">
                                                    <img src="{{ $followed->avatar ? asset($followed->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($followed->nama_toko ?? $followed->name).'&background=059669&color=fff' }}" class="w-full h-full object-cover">
                                                </div>
                                                <div>
                                                    <h4 class="text-xs font-bold text-gray-900 line-clamp-1">{{ $followed->nama_toko ?? $followed->name }}</h4>
                                                    <p class="text-[11px] font-bold text-gray-400">{{ $followed->item_shops_count }} Produk</p>
                                                </div>
                                            </div>
                                            <a href="{{ route('shop.public', ['seller_id' => $followed->id]) }}" class="p-2.5 bg-white text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-10 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                                    <p class="text-xs font-bold text-gray-400">Kamu belum mengikuti toko manapun bolo.</p>
                                    <a href="{{ route('shop.public') }}" class="inline-block mt-4 text-xs font-semibold text-emerald-600 hover:underline">Cari Toko Favoritmu</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur-md p-8 sm:p-12 rounded-[3.5rem] shadow-lg shadow-gray-200/50 border border-white" data-aos="fade-up" data-aos-delay="200">
                        <div class="max-w-3xl">
                            <div class="mb-12">
                                <h3 class="text-xl font-bold text-gray-900">Personal <span class="text-emerald-600">Information</span></h3>
                                <div class="w-16 h-1 bg-emerald-600 mt-2 rounded-full"></div>
                                <p class="text-sm text-gray-500 mt-4 font-medium">Update data diri dan alamat email anda.</p>
                            </div>
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur-md p-8 sm:p-12 rounded-[3.5rem] shadow-lg shadow-gray-200/50 border border-white" data-aos="fade-up" data-aos-delay="200">
                        <div class="max-w-3xl">
                            <div class="mb-12">
                                <h3 class="text-xl font-bold text-gray-900">Secure <span class="text-emerald-600">Password</span></h3>
                                <div class="w-16 h-1 bg-emerald-600 mt-2 rounded-full"></div>
                                <p class="text-sm text-gray-500 mt-4 font-medium">Gunakan password yang kuat untuk keamanan akun.</p>
                            </div>
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur-md p-8 sm:p-12 rounded-[3.5rem] shadow-lg shadow-gray-200/50 border border-white" data-aos="fade-up" data-aos-delay="300">
                        <div class="max-w-3xl">
                            <div class="mb-12">
                                <h3 class="text-xl font-bold text-gray-900">Danger <span class="text-red-600">Zone</span></h3>
                                <div class="w-16 h-1 bg-red-600 mt-2 rounded-full"></div>
                                <p class="text-xs text-gray-400 mt-4 font-bold">Sekali dihapus, data akun tidak dapat dikembalikan.</p>
                            </div>
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
            </div>
        </div>
    </div>

    {{-- Modal Crop --}}
    <div x-data="{ 
            open: false, 
            title: 'Avatar',
            mode: 'avatar', // avatar | banner
            init() {
                window.addEventListener('open-crop-modal', (e) => {
                    this.title = e.detail.title || 'Avatar';
                    this.mode = e.detail.mode || 'avatar';
                    this.open = true;
                });
            }
        }" 
        x-init="init()"
        @keydown.escape.window="open = false"
        id="cropModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-gray-900/80 backdrop-blur-sm"
        :class="{ 'flex': open, 'hidden': !open }" x-cloak>
        
        <div class="bg-white rounded-2xl p-6 w-full max-w-2xl shadow-lg m-4 relative animate-fade-in-up">
            <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">
                POTONG <span class="text-emerald-600" x-text="title">BOLO</span>
            </h3>
            
            <div class="relative w-full h-[300px] sm:h-[400px] bg-gray-100 rounded-2xl overflow-hidden mb-6 border-2 border-dashed border-gray-300">
                <img id="image-to-crop" class="max-w-full block" src="">
            </div>

            <div class="flex gap-4">
                <button @click="open = false" class="flex-1 py-4 bg-gray-100 text-gray-400 rounded-2xl font-semibold uppercase text-xs tracking-widest hover:bg-gray-200 transition-all border border-gray-100">
                    BATAL
                </button>
                <button id="btn-crop-upload" class="flex-1 py-4 bg-emerald-600 text-white rounded-2xl font-semibold uppercase text-xs tracking-widest hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100">
                    SIMPAN & UPDATE
                </button>
            </div>
        </div>
    </div>

    {{-- Form Hidden --}}
    <form id="avatar-form" action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
        @csrf
        @method('PATCH')
        <input type="file" name="avatar" id="avatar-input" accept="image/*">
    </form>

    <form id="banner-form" action="{{ route('profile.banner.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
        @csrf
        @method('PATCH')
        <input type="file" name="banner" id="banner-input" accept="image/*">
    </form>

    {{-- CropperJS CSS & JS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // --- SELLER MAP LOGIC (EXPERT) ---
        function initSellerMap() {
            const sellerMapEl = document.getElementById('seller-map');
            if (!sellerMapEl) return;

            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const addressInput = document.getElementById('alamat');
            const searchInput = document.getElementById('seller-map-search');
            const resultsBox = document.getElementById('seller-search-results');

            const defaultLat = {{ auth()->user()->latitude ?? -7.4726 }};
            const defaultLng = {{ auth()->user()->longitude ?? 112.4385 }};

            if (window.sellerMapInstance) {
                window.sellerMapInstance.remove();
            }

            const sellerMap = L.map('seller-map', {
                zoomControl: false,
                attributionControl: false
            }).setView([defaultLat, defaultLng], 15);

            window.sellerMapInstance = sellerMap;

            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(sellerMap);

            const sellerIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `
                    <div class="relative flex items-center justify-center">
                        <div class="absolute animate-ping w-8 h-8 bg-emerald-500/30 rounded-full"></div>
                        <div class="relative w-8 h-8 bg-emerald-600 rounded-xl flex items-center justify-center border-2 border-white shadow-lg">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                    </div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 16]
            });

            let marker = L.marker([defaultLat, defaultLng], {
                draggable: true,
                icon: sellerIcon
            }).addTo(sellerMap);

            function updateLocationStore(lat, lng, address = null) {
                if (latInput) latInput.value = parseFloat(lat).toFixed(6);
                if (lngInput) lngInput.value = parseFloat(lng).toFixed(6);
                // Don't overwrite address if it's already filled and the new one is null
                if (address && addressInput) {
                    if (!addressInput.value || confirm('Gunakan alamat dari hasil pencarian ini?')) {
                        addressInput.value = address;
                    }
                }
            }

            async function reverseGeocode(lat, lng) {
                try {
                    const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`, {
                        headers: { 'User-Agent': 'MiniQ-Store-App' }
                    });
                    const data = await res.json();
                    if (data && data.display_name && addressInput) {
                        const suggestionEl = document.getElementById('osm-suggestion');
                        if (suggestionEl) {
                            suggestionEl.innerHTML = `
                                <div class="mt-2 p-2 bg-emerald-50 rounded-lg border border-emerald-100 flex items-start gap-2 animate-in fade-in slide-in-from-top-1">
                                    <svg class="w-4 h-4 text-emerald-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <div class="flex-1">
                                        <p class="text-xs text-emerald-700 font-bold leading-tight">Saran Alamat dari Peta:</p>
                                        <p class="text-xs text-emerald-600 line-clamp-1">${data.display_name}</p>
                                        <button type="button" onclick="document.getElementById('alamat').value = '${data.display_name.replace(/'/g, "\\'")}'; this.parentElement.parentElement.remove();" 
                                                class="mt-1 text-xs bg-emerald-600 text-white px-2 py-0.5 rounded font-bold hover:bg-emerald-700 transition-colors uppercase">
                                            Gunakan Alamat Ini
                                        </button>
                                    </div>
                                </div>
                            `;
                        }
                    }
                } catch(err) {}
            }

            marker.on('dragend', async function() {
                const pos = marker.getLatLng();
                updateLocationStore(pos.lat, pos.lng);
                await reverseGeocode(pos.lat, pos.lng);
            });

            sellerMap.on('click', async function(e) {
                marker.setLatLng(e.latlng);
                sellerMap.panTo(e.latlng);
                updateLocationStore(e.latlng.lat, e.latlng.lng);
                await reverseGeocode(e.latlng.lat, e.latlng.lng);
            });

            window.selectSellerLocation = async (lat, lon, name = null) => {
                const pos = [parseFloat(lat), parseFloat(lon)];
                marker.setLatLng(pos);
                sellerMap.flyTo(pos, 16);
                updateLocationStore(pos[0], pos[1], name);
                
                if (!name || name === 'Lokasimu Saat Ini') {
                    await reverseGeocode(pos[0], pos[1]);
                }

                if (resultsBox) resultsBox.classList.add('hidden');
                if (searchInput) searchInput.value = '';
            };

            let searchTimer;
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimer);
                    const query = this.value;
                    if (query.length < 3) return resultsBox.classList.add('hidden');

                    searchTimer = setTimeout(async () => {
                        resultsBox.innerHTML = '<div class="px-4 py-3 text-xs text-gray-400 animate-pulse text-center font-bold">Mencari...</div>';
                        resultsBox.classList.remove('hidden');

                        try {
                            const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query + ' Mojokerto')}&limit=5&addressdetails=1`, {
                                headers: { 'User-Agent': 'MiniQ-Store-App' }
                            });
                            const data = await res.json();

                            if (data.length > 0) {
                                resultsBox.innerHTML = data.map(item => `
                                    <div class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-50 last:border-0 transition-colors" 
                                         onclick="window.selectSellerLocation(${item.lat}, ${item.lon}, '${item.display_name.replace(/'/g, "\\'")}')">
                                        <p class="text-[11px] font-bold text-gray-800 truncate">${item.display_name.split(',')[0]}</p>
                                        <p class="text-[11px] text-gray-400 font-bold truncate uppercase mt-0.5">${item.display_name.split(',').slice(1,3).join(',')}</p>
                                    </div>
                                `).join('');
                            } else {
                                resultsBox.innerHTML = '<div class="px-4 py-3 text-xs text-orange-500 font-bold text-center">Lokasi tidak ketemu bolo.</div>';
                            }
                        } catch(e) { resultsBox.classList.add('hidden'); }
                    }, 500);
                } );
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            initSellerMap();
            
            // Fix Leaflet container size issues on load
            setTimeout(() => {
                if (window.sellerMapInstance) {
                    window.sellerMapInstance.invalidateSize();
                }
            }, 500);
            
            @if(session('status') === 'profile-updated')
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Profil kamu sudah terupdate bolo.',
                    showConfirmButton: false,
                    timer: 1500,
                    toast: true,
                    position: 'top-end'
                });
            @endif
        });

        const avatarInput = document.getElementById('avatar-input');
        const bannerInput = document.getElementById('banner-input');
        const imageToCrop = document.getElementById('image-to-crop');
        const btnCropUpload = document.getElementById('btn-crop-upload');
        let cropper;
        let currentMode = 'avatar'; // default

        const handleImageSelect = (input, mode) => {
            input.addEventListener('change', function(e) {
                const files = e.target.files;
                if (files && files.length > 0) {
                    currentMode = mode;
                    const file = files[0];
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        imageToCrop.src = e.target.result;
                        
                        // Buka Modal via Alpine event
                        window.dispatchEvent(new CustomEvent('open-crop-modal', {
                            detail: { 
                                title: mode === 'avatar' ? 'Avatar' : 'Shop Banner',
                                mode: mode 
                            }
                        }));

                        // Destroy old cropper if exists
                        if (cropper) cropper.destroy();

                        // Init Cropper
                        setTimeout(() => {
                            cropper = new Cropper(imageToCrop, {
                                aspectRatio: mode === 'avatar' ? 1 : (16 / 6), // Banner lebih lebar
                                viewMode: 1,
                                dragMode: 'move',
                                autoCropArea: 1,
                                background: false,
                            });
                        }, 200);
                    };
                    reader.readAsDataURL(file);
                }
            });
        };

        handleImageSelect(avatarInput, 'avatar');
        handleImageSelect(bannerInput, 'banner');

        btnCropUpload.addEventListener('click', function() {
            if (!cropper) return;

            const config = currentMode === 'avatar' 
                ? { width: 400, height: 400 } 
                : { width: 1200, height: 450 }; // Banner resolution

            const canvas = cropper.getCroppedCanvas(config);

            canvas.toBlob(function(blob) {
                const formData = new FormData();
                const fieldName = currentMode === 'avatar' ? 'avatar' : 'banner';
                const uploadUrl = currentMode === 'avatar' 
                    ? "{{ route('profile.avatar.update') }}" 
                    : "{{ route('profile.banner.update') }}";

                formData.append(fieldName, blob, `${fieldName}.png`);
                formData.append('_method', 'PATCH');
                formData.append('_token', '{{ csrf_token() }}');

                btnCropUpload.innerText = 'PROSES...';
                btnCropUpload.disabled = true;

                fetch(uploadUrl, {
                    method: 'POST',
                    body: formData,
                })
                .then(response => {
                    if (response.ok) return response;
                    throw new Error('Upload failed');
                })
                .then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: `${currentMode.charAt(0).toUpperCase() + currentMode.slice(1)} berhasil diperbarui bolo!`,
                        timer: 1500,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    }).then(() => window.location.reload());
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Gagal upload, coba lagi ya.', 'error');
                    btnCropUpload.innerText = 'SIMPAN & UPDATE';
                    btnCropUpload.disabled = false;
                });
            });
        });
    </script>
    @endpush
</x-admin-layout> 

