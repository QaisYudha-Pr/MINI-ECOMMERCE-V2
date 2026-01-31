<x-courier-layout>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6">
            {{-- Header Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Tugas</p>
                        <h3 class="text-2xl font-black text-slate-900">{{ $deliveries->total() }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Perlu Dikirim</p>
                        <h3 class="text-2xl font-black text-amber-500">{{ $deliveries->where('status', 'shipped')->count() }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Selesai</p>
                        <h3 class="text-2xl font-black text-emerald-500">{{ $deliveries->where('status', 'completed')->count() }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                </div>
                <div class="bg-indigo-600 p-6 rounded-[2rem] shadow-xl shadow-indigo-100 flex items-center justify-between text-white">
                    <div>
                        <p class="text-[10px] font-black text-white/70 uppercase tracking-widest mb-1">Status Kurir</p>
                        <h3 class="text-xl font-black uppercase tracking-tight">Aktif Bolo</h3>
                    </div>
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-black text-slate-900 uppercase tracking-widest">
                    Tugas <span class="text-indigo-600">Pengiriman</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($deliveries as $delivery)
                    <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-xl shadow-slate-200/50 hover:shadow-indigo-100/50 transition-all duration-500 group">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Invoice</span>
                                <h3 class="text-lg font-black text-slate-900 group-hover:text-indigo-600 transition-colors">#{{ $delivery->invoice_number }}</h3>
                                <div class="mt-2 text-[10px] font-black uppercase tracking-widest px-3 py-1 bg-slate-100 text-slate-600 rounded-full inline-block">
                                    {{ $delivery->status }}
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Potensi Tip</span>
                                <p class="text-lg font-black text-indigo-600">Rp {{ number_format($delivery->total_price * 0.01, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="space-y-5 mb-8">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Penerima</span>
                                    <p class="text-sm font-black text-slate-900">{{ $delivery->user->name }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Alamat Tujuan</span>
                                    <p class="text-sm font-bold text-slate-900 leading-relaxed">{{ Str::limit($delivery->alamat, 60) }}</p>
                                    @if($delivery->user->latitude && $delivery->user->longitude)
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $delivery->user->latitude }},{{ $delivery->user->longitude }}" 
                                           target="_blank" class="mt-2 inline-flex items-center gap-2 text-indigo-600 text-[10px] font-black uppercase hover:text-indigo-700 transition-colors">
                                            <span>Buka Maps Bolo</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($delivery->status == 'shipped')
                            <div x-data="{ hasProof: false, preview: '' }">
                                <form id="complete-form-{{ $delivery->id }}" action="{{ route('courier.deliveries.complete', $delivery->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 font-sans">
                                    @csrf
                                    <div class="relative">
                                        <input type="file" name="delivery_proof" id="proof-{{ $delivery->id }}" class="hidden" accept="image/*" onchange="initCourierCrop(this, {{ $delivery->id }})">
                                        
                                        <template x-if="!hasProof">
                                            <button type="button" onclick="document.getElementById('proof-{{ $delivery->id }}').click()" 
                                                class="w-full py-4 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 transition-all flex items-center justify-center gap-3">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                <span>Ambil Foto Bukti</span>
                                            </button>
                                        </template>

                                        <template x-if="hasProof">
                                            <div class="relative w-full h-32 rounded-2xl overflow-hidden group/proof">
                                                <img :src="preview" class="w-full h-full object-cover">
                                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/proof:opacity-100 transition-opacity flex items-center justify-center">
                                                    <button type="button" @click="hasProof = false; preview = ''; document.getElementById('proof-{{ $delivery->id }}').value = ''" class="p-2 bg-red-500 text-white rounded-xl">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                        
                                        {{-- Hidden input for cropped base64 if needed, but let's use DataTransfer for files --}}
                                    </div>
                                    <button type="button" @click="confirmDelivery({{ $delivery->id }})"
                                        class="w-full py-5 bg-indigo-600 text-white rounded-2xl text-xs font-black uppercase tracking-[0.2em] hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100">
                                        Konfirmasi Sampai
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="space-y-4">
                                @if($delivery->delivery_proof)
                                    <div class="relative w-full h-40 rounded-[2rem] overflow-hidden">
                                        <img src="{{ asset($delivery->delivery_proof) }}" class="w-full h-full object-cover">
                                    </div>
                                @endif
                                <div class="w-full py-5 bg-slate-50 text-indigo-600 rounded-[2rem] text-[10px] font-black uppercase tracking-[0.2em] text-center border-2 border-dashed border-indigo-100">
                                    @if($delivery->status == 'delivered')
                                        Menunggu Konfirmasi User
                                    @else
                                          Tugas Selesai Bolo
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full py-24 bg-white rounded-[3rem] border-4 border-dashed border-slate-100 text-center">
                        <p class="text-slate-400 font-black uppercase tracking-[0.2em] text-sm">Belum ada tugas rebahan dulu bolo</p>
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
        <div class="bg-white rounded-[2.5rem] w-full max-w-lg overflow-hidden shadow-2xl relative animate-fade-in-up">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-black text-gray-900 tracking-tight uppercase">POTONG <span class="text-indigo-600">BUKTI FOTO</span></h3>
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
                <button type="button" onclick="closeCourierCrop()" class="flex-1 py-4 bg-white text-gray-500 rounded-2xl font-black uppercase text-[10px] tracking-widest border border-gray-100">BATAL</button>
                <button type="button" onclick="applyCourierCrop()" class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-indigo-100">POTONG BOLO</button>
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

                // Update UI visually
                const alpineEl = input.closest('[x-data]');
                if (alpineEl && alpineEl.__x) {
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

        function confirmDelivery(id) {
            const alpineData = document.getElementById('proof-' + id).closest('[x-data]').__x.$data;
            if (!alpineData.hasProof) {
                Swal.fire({ icon: 'warning', title: 'Belum Ada Foto Bolo' });
                return;
            }
            Swal.fire({
                title: 'Udah Sampe Bolo?',
                text: "Pastikan barangnya udah diterima yang punya ya!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'GAS SAMPAI!',
                customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('complete-form-' + id).submit();
                }
            });
        }
    </script>
    @endpush
</x-courier-layout>
