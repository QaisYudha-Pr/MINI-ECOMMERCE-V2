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
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <h2 class="font-black text-xl text-gray-800 leading-tight uppercase tracking-widest pl-1">
                Account <span class="text-indigo-600">Settings</span>
            </h2>
        </div>
    </x-slot>

    <div class="pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                {{-- KIRI: PROFILE CARD & AVATAR --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white/80 backdrop-blur-md p-8 rounded-[2.5rem] shadow-2xl shadow-gray-200/50 border border-white text-center" data-aos="fade-up">
                        <div class="relative inline-block group">
                            <div class="w-44 h-44 rounded-full p-2 border-2 border-dashed border-indigo-200 group-hover:border-indigo-500 transition-all duration-500">
                                <div class="w-full h-full rounded-full overflow-hidden border-4 border-white shadow-2xl bg-gray-50">
                                    <img id="avatar-preview" 
                                         src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=4f46e5&color=fff&size=200' }}" 
                                         class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                </div>
                            </div>
                            
                            <label for="avatar-input" class="absolute bottom-2 right-2 w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center cursor-pointer shadow-xl hover:bg-indigo-700 hover:scale-110 active:scale-95 transition-all border-4 border-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" stroke-width="2"/><path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"/></svg>
                            </label>
                        </div>

                        <div class="mt-8">
                            <h3 class="text-2xl font-black text-gray-900 tracking-tight">{{ auth()->user()->name }}</h3>
                            <div class="inline-flex items-center px-4 py-1.5 bg-indigo-50 text-indigo-600 rounded-full text-[10px] font-black uppercase tracking-[0.2em] mt-3">
                                {{ auth()->user()->roles->first()->name ?? 'Member' }}
                            </div>
                        </div>

                        <div class="mt-10 pt-10 border-t border-gray-100 grid grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 rounded-[2rem]">
                                <span class="block text-2xl font-black text-gray-900 leading-none">12</span>
                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1 block">Orders</span>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-[2rem]">
                                <span class="block text-2xl font-black text-gray-900 leading-none">05</span>
                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1 block">Reviews</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KANAN: FORMS --}}
                <div class="lg:col-span-2 space-y-10">
                    <div class="bg-white/80 backdrop-blur-md p-8 sm:p-12 rounded-[3.5rem] shadow-2xl shadow-gray-200/50 border border-white" data-aos="fade-up" data-aos-delay="100">
                        <div class="max-w-3xl">
                            <div class="mb-12">
                                <h3 class="text-xl font-black text-gray-900 uppercase tracking-[0.2em]">Personal <span class="text-indigo-600">Information</span></h3>
                                <div class="w-16 h-1 bg-indigo-600 mt-2 rounded-full"></div>
                                <p class="text-sm text-gray-500 mt-4 font-medium italic">Update data diri dan alamat email anda.</p>
                            </div>
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur-md p-8 sm:p-12 rounded-[3.5rem] shadow-2xl shadow-gray-200/50 border border-white" data-aos="fade-up" data-aos-delay="200">
                        <div class="max-w-3xl">
                            <div class="mb-12">
                                <h3 class="text-xl font-black text-gray-900 uppercase tracking-[0.2em]">Secure <span class="text-indigo-600">Password</span></h3>
                                <div class="w-16 h-1 bg-indigo-600 mt-2 rounded-full"></div>
                                <p class="text-sm text-gray-500 mt-4 font-medium italic">Gunakan password yang kuat untuk keamanan akun.</p>
                            </div>
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur-md p-8 sm:p-12 rounded-[3.5rem] shadow-2xl shadow-gray-200/50 border border-white" data-aos="fade-up" data-aos-delay="300">
                        <div class="max-w-3xl">
                            <div class="mb-12">
                                <h3 class="text-xl font-black text-gray-900 uppercase tracking-[0.2em]">Danger <span class="text-red-600">Zone</span></h3>
                                <div class="w-16 h-1 bg-red-600 mt-2 rounded-full"></div>
                                <p class="text-xs text-gray-400 mt-4 font-bold uppercase tracking-wider">Sekali dihapus, data akun tidak dapat dikembalikan.</p>
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

    {{-- Modal Crop Avatar --}}
    <div x-data="{ open: false }" x-init="$watch('open', value => { if (!value) document.getElementById('avatar-input').value = '' })"
        @open-crop-modal.window="open = true"
        id="cropModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-gray-900/80 backdrop-blur-sm"
        :class="{ 'flex': open, 'hidden': !open }">
        
        <div class="bg-white rounded-[2.5rem] p-6 w-full max-w-lg shadow-2xl m-4 relative animate-fade-in-up">
            <h3 class="text-xl font-black text-gray-900 uppercase tracking-widest mb-4 text-center">Crop Your <span class="text-indigo-600">Avatar</span></h3>
            
            <div class="relative w-full h-[400px] bg-gray-100 rounded-2xl overflow-hidden mb-6 border-2 border-dashed border-gray-300">
                <img id="image-to-crop" class="max-w-full block" src="">
            </div>

            <div class="flex gap-4">
                <button @click="open = false" class="flex-1 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-gray-200 transition-all">
                    Cancel
                </button>
                <button id="btn-crop-upload" class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">
                    Crop & Upload
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
            const defaultLng = {{ auth()->user()->longitude ?? 112.4382 }};

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
                        <div class="absolute animate-ping w-8 h-8 bg-indigo-500/30 rounded-full"></div>
                        <div class="relative w-8 h-8 bg-indigo-600 rounded-xl flex items-center justify-center border-2 border-white shadow-lg">
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
                if (address && addressInput) addressInput.value = address;
            }

            async function reverseGeocode(lat, lng) {
                try {
                    const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`, {
                        headers: { 'User-Agent': 'MiniQ-Store-App' }
                    });
                    const data = await res.json();
                    if (data && data.display_name) {
                        addressInput.value = data.display_name;
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
                                        <p class="text-[11px] font-black text-gray-800 truncate">${item.display_name.split(',')[0]}</p>
                                        <p class="text-[9px] text-gray-400 font-bold truncate uppercase mt-0.5">${item.display_name.split(',').slice(1,3).join(',')}</p>
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
        const imageToCrop = document.getElementById('image-to-crop');
        const btnCropUpload = document.getElementById('btn-crop-upload');
        let cropper;

        // Trigger input file saat tombol diklik (sudah ada label yang handle ini, jadi listener change aja)
        avatarInput.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const file = files[0];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imageToCrop.src = e.target.result;
                    
                    // Buka Modal via Alpine event
                    window.dispatchEvent(new CustomEvent('open-crop-modal'));

                    // Destroy old cropper if exists
                    if (cropper) {
                        cropper.destroy();
                    }

                    // Init Cropper
                    setTimeout(() => {
                        cropper = new Cropper(imageToCrop, {
                            aspectRatio: 1,
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

        btnCropUpload.addEventListener('click', function() {
            if (!cropper) return;

            const canvas = cropper.getCroppedCanvas({
                width: 400,
                height: 400,
            });

            canvas.toBlob(function(blob) {
                const formData = new FormData();
                formData.append('avatar', blob, 'avatar.png'); // Kirim sebagai file
                formData.append('_method', 'PATCH'); // Spoofing PATCH
                formData.append('_token', '{{ csrf_token() }}');

                // Tampilkan loading di button
                btnCropUpload.innerText = 'UPLOADING...';
                btnCropUpload.disabled = true;

                fetch("{{ route('profile.avatar.update') }}", {
                    method: 'POST',
                    body: formData,
                })
                .then(response => {
                    if (response.ok) {
                        return response; // Bisa redirect/reload
                    }
                    throw new Error('Upload failed');
                })
                .then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Avatar Updated!',
                        text: 'Foto profil berhasil diperbarui, bolo.',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => window.location.reload());
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Gagal upload foto, coba lagi ya.', 'error');
                    btnCropUpload.innerText = 'CROP & UPLOAD';
                    btnCropUpload.disabled = false;
                });
            });
        });
    </script>
    @endpush
</x-admin-layout> 