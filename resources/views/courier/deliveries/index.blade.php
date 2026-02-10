<x-admin-layout>
    <div class="py-8 px-4">
        <div class="max-w-7xl mx-auto">
            {{-- Header Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mb-1">Total Tugas</p>
                        <h3 class="text-2xl font-black text-slate-900 leading-none">{{ $totalTugas }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mb-1">Perlu Dikirim</p>
                        <h3 class="text-2xl font-black text-amber-500 leading-none">{{ $perluDikirim }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mb-1">Status Kurir</p>
                        <h3 class="text-2xl font-black text-emerald-500 leading-none">Aktif Bolo</h3>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between mb-8 px-2">
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">
                    TUGAS <span class="text-blue-600">PENGIRIMAN</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($deliveries as $delivery)
                    <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-xl shadow-slate-200/50 hover:shadow-emerald-100/50 transition-all duration-500 group">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <span class="text-xs font-medium text-slate-400">Invoice</span>
                                <h3 class="text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">#{{ $delivery->invoice_number }}</h3>
                                <div class="mt-2 text-xs font-semibold px-3 py-1 
                                    @if(strtolower($delivery->status) == 'shipped') bg-amber-100 text-amber-600 
                                    @elseif(strtolower($delivery->status) == 'delivered') bg-blue-100 text-blue-600 
                                    @else bg-emerald-100 text-emerald-600 @endif 
                                    rounded-full inline-block uppercase tracking-tighter">
                                    @switch(strtolower($delivery->status))
                                        @case('shipped')
                                            Tugas Antar
                                            @break
                                        @case('delivered')
                                            Sudah Sampai
                                            @break
                                        @case('success')
                                        @case('completed')
                                            Selesai
                                            @break
                                        @default
                                            {{ $delivery->status }}
                                    @endswitch
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4 mb-6">
                            {{-- Pickup Info --}}
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 group-hover:bg-blue-50/50 group-hover:border-blue-100 transition-colors">
                                <span class="text-[9px] font-black text-blue-600 uppercase mb-1 block tracking-tighter">1. TITIK JEMPUT (TOKO)</span>
                                <p class="text-xs font-black text-slate-900 truncate">{{ $delivery->seller->nama_toko ?? $delivery->seller->name }}</p>
                                <p class="text-[9px] font-bold text-slate-500 mt-0.5 line-clamp-1 truncate">{{ $delivery->seller->alamat }}</p>
                                <div class="flex items-center gap-2 mt-2">
                                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $delivery->seller->latitude }},{{ $delivery->seller->longitude }}" 
                                        target="_blank" class="inline-flex items-center gap-1.5 text-[10px] font-black text-blue-600 hover:underline">
                                        NAVIGASI PICKUP <i class="fa-solid fa-location-arrow text-[8px]"></i>
                                    </a>
                                </div>
                            </div>
                            
                            {{-- Destination Info --}}
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 group-hover:bg-emerald-50/50 group-hover:border-emerald-100 transition-colors">
                                <span class="text-[9px] font-black text-emerald-600 uppercase mb-1 block tracking-tighter">2. TITIK ANTAR (TUJUAN)</span>
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <p class="text-xs font-black text-slate-900 truncate">{{ $delivery->user->name }}</p>
                                        <p class="text-[9px] font-bold text-slate-500 mt-0.5 line-clamp-1 truncate">{{ $delivery->alamat }}</p>
                                    </div>
                                    @if($delivery->user->phone)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $delivery->user->phone) }}" target="_blank" class="w-7 h-7 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all">
                                            <i class="fa-brands fa-whatsapp text-sm"></i>
                                        </a>
                                    @endif
                                </div>

                                @if($delivery->catatan_alamat)
                                    <div class="mt-2 p-2 bg-amber-50 rounded-xl border border-amber-100/50">
                                        <p class="text-[10px] font-bold text-amber-800 leading-tight">"{{ $delivery->catatan_alamat }}"</p>
                                    </div>
                                @endif

                                @php
                                    $dLat = $delivery->lat ?: ($delivery->user->latitude ?? null);
                                    $dLng = $delivery->lng ?: ($delivery->user->longitude ?? null);
                                    $destCoord = ($dLat && $dLng) ? "{$dLat},{$dLng}" : urlencode($delivery->alamat);
                                @endphp
                                <div class="flex items-center gap-3 mt-3">
                                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $destCoord }}" 
                                       target="_blank" 
                                       class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-600 text-white text-[10px] font-black uppercase rounded-lg shadow-sm shadow-emerald-100 hover:bg-emerald-700 transition-all">
                                        <i class="fa-solid fa-map-location-dot"></i> Lihat Rute
                                    </a>
                                    <a href="{{ route('courier.deliveries.show', $delivery->id) }}" 
                                       class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-100 text-slate-600 text-[10px] font-black uppercase rounded-lg hover:bg-slate-200 transition-all">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>

                        @if(strtolower($delivery->status) == 'shipped')
                            <div x-data="{ hasProof: false, preview: '' }">
                                <form id="complete-form-{{ $delivery->id }}" action="{{ route('courier.deliveries.complete', $delivery->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                    @csrf
                                    <div class="relative">
                                        <input type="file" name="delivery_proof" id="proof-{{ $delivery->id }}" class="hidden" accept="image/*" onchange="initCourierCrop(this, {{ $delivery->id }})">
                                        
                                        <template x-if="!hasProof">
                                            <button type="button" @click="$el.closest('div').querySelector('input').click()" 
                                                class="w-full py-4 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl text-[10px] font-black text-slate-400 group-hover:border-emerald-200 group-hover:bg-emerald-50/50 group-hover:text-emerald-600 transition-all flex items-center justify-center gap-2 uppercase tracking-tight">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                Ambil Foto Bukti
                                            </button>
                                        </template>

                                        <template x-if="hasProof">
                                            <div class="relative w-full h-32 rounded-2xl overflow-hidden group/proof border-4 border-emerald-500 shadow-lg shadow-emerald-100">
                                                <img :src="preview" class="w-full h-full object-cover">
                                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover/proof:opacity-100 transition-opacity flex items-center justify-center gap-3">
                                                    <button type="button" @click="hasProof = false; preview = ''; document.getElementById('proof-{{ $delivery->id }}').value = ''" class="p-3 bg-red-500 text-white rounded-xl shadow-xl active:scale-95 transition-all">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                    <button type="button" @click="confirmDelivery({{ $delivery->id }}, $el)"
                                        class="w-full py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-emerald-600 transition-all shadow-xl shadow-slate-200 active:scale-95">
                                        Konfirmasi Sampai
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="space-y-3">
                                @if($delivery->delivery_proof)
                                    <div class="relative w-full h-40 rounded-2xl overflow-hidden border-2 border-slate-100 group">
                                        <img src="{{ asset($delivery->delivery_proof) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-4">
                                            <span class="text-[8px] font-black text-white/80 uppercase tracking-widest">Bukti Pengiriman</span>
                                        </div>
                                    </div>
                                @endif
                                <div class="w-full py-4 bg-emerald-50 text-emerald-600 rounded-2xl text-[10px] font-black text-center border-2 border-dashed border-emerald-200 uppercase tracking-widest">
                                    @if($delivery->status == 'delivered')
                                        Menunggu User
                                    @else
                                          Tugas Selesai
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full py-24 bg-white rounded-3xl border-4 border-dashed border-slate-100 text-center animate-pulse">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                             <i class="fa-solid fa-couch text-slate-200 text-3xl"></i>
                        </div>
                        <p class="text-slate-400 font-black text-sm uppercase tracking-widest">Belum ada tugas rebahan dulu bolo</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $deliveries->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL CROP COURIER --}}
    <div id="courierCropModal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-lg relative animate-fade-in-up">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900 tracking-tight uppercase">POTONG <span class="text-emerald-600">BUKTI FOTO</span></h3>
                <button type="button" onclick="closeCourierCrop()" class="text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6">
                <div class="max-h-[50vh] overflow-hidden rounded-2xl border-2 border-dashed border-gray-200">
                    <img id="courierImageToCrop" src="" class="max-w-full block">
                </div>
            </div>
            <div class="p-6 bg-gray-50/50 border-t border-gray-50 flex gap-4">
                <button type="button" onclick="closeCourierCrop()" class="flex-1 py-4 bg-white text-gray-500 rounded-2xl font-semibold uppercase text-xs tracking-widest border border-gray-100">BATAL</button>
                <button type="button" onclick="applyCourierCrop()" class="flex-1 py-4 bg-emerald-600 text-white rounded-2xl font-semibold uppercase text-xs tracking-widest shadow-lg shadow-emerald-100">POTONG BOLO</button>
            </div>
        </div>
    </div>

    @push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        let courierCropper = null;
        let currentDeliveryId = null;

        function initCourierCrop(input, id) {
            if (input.files && input.files[0]) {
                currentDeliveryId = id;
                const reader = new FileReader();
                reader.onload = function(e) {
                    const image = document.getElementById('courierImageToCrop');
                    image.src = e.target.result;
                    
                    document.getElementById('courierCropModal').classList.remove('hidden');
                    document.getElementById('courierCropModal').classList.add('flex');
                    
                    if (courierCropper) courierCropper.destroy();
                    
                    setTimeout(() => {
                        courierCropper = new Cropper(image, {
                            aspectRatio: 1,
                            viewMode: 1,
                            dragMode: 'move',
                            autoCropArea: 0.8,
                            background: true,
                            responsive: true,
                        });
                    }, 500); 
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function closeCourierCrop() {
            document.getElementById('courierCropModal').classList.add('hidden');
            document.getElementById('courierCropModal').classList.remove('flex');
            if (courierCropper) {
                courierCropper.destroy();
                courierCropper = null;
            }
        }

        function applyCourierCrop() {
            if (!courierCropper) return;

            const canvas = courierCropper.getCroppedCanvas({ width: 600, height: 600 });
            canvas.toBlob(blob => {
                const file = new File([blob], 'delivery_proof.jpg', { type: 'image/jpeg' });
                const container = new DataTransfer();
                container.items.add(file);
                
                const input = document.getElementById('proof-' + currentDeliveryId);
                input.files = container.files;

                // Update UI visually using Alpine.js API v3
                const alpineEl = input.closest('[x-data]');
                if (window.Alpine) {
                    const data = Alpine.$data(alpineEl);
                    data.hasProof = true;
                    data.preview = canvas.toDataURL('image/jpeg');
                } else if (alpineEl && alpineEl.__x) {
                    // Fallback for v2
                    alpineEl.__x.$data.hasProof = true;
                    alpineEl.__x.$data.preview = canvas.toDataURL('image/jpeg');
                }

                closeCourierCrop();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Mantap Bolo!',
                    text: 'Foto bukti sudah siap.',
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }, 'image/jpeg');
        }

        function confirmDelivery(id, btnEl) {
            const alpineData = Alpine ? Alpine.$data(btnEl.closest('[x-data]')) : btnEl.closest('[x-data]').__x.$data;
            if (!alpineData.hasProof) {
                Swal.fire({ 
                    icon: 'warning', 
                    title: 'BUKTI MANA BOLO?', 
                    text: 'Ambil foto bukti dulu ya sebelum konfirmasi!',
                    confirmButtonColor: '#10b981'
                });
                return;
            }
            Swal.fire({
                title: 'UDAH SAMPE BOLO?',
                text: "Pastikan barangnya sudah diterima pembeli ya!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'IYA, GAS!',
                cancelButtonText: 'BELUM',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#f43f5e',
                customClass: { popup: 'rounded-3xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('complete-form-' + id).submit();
                }
            });
        }
    </script>
    @endpush
</x-admin-layout>

