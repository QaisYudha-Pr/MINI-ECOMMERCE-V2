<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <x-slot name="header">
        <div class="flex items-center gap-3" data-aos="fade-right">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <h2 class="font-black text-xl text-gray-800 leading-tight uppercase tracking-widest">
                Edit <span class="text-indigo-600">Produk</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f8fafc]">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white/80 backdrop-blur-md rounded-[2.5rem] shadow-2xl shadow-gray-200/50 p-6 sm:p-10 border border-white" data-aos="fade-up">
                <form action="{{ route('item-shop.update', $itemShop->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        
                        {{-- KOLOM KIRI: FORM DATA --}}
                        <div class="space-y-6">
                            <div class="group">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1 transition-colors group-focus-within:text-indigo-600">Product Name</label>
                                <input type="text" name="nama_barang" value="{{ old('nama_barang', $itemShop->nama_barang) }}" class="w-full px-5 py-4 bg-gray-50 border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all outline-none font-bold text-gray-700" required>
                                @error('nama_barang')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>

                            <div class="group">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1 transition-colors group-focus-within:text-indigo-600">Price (IDR)</label>
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 font-black text-sm">Rp</span>
                                    <input type="number" name="harga" value="{{ old('harga', $itemShop->harga) }}" class="w-full px-5 py-4 pl-12 bg-gray-50 border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all outline-none font-black text-indigo-600" required>
                                </div>
                                @error('harga')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>

                            <div class="group">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1 transition-colors group-focus-within:text-indigo-600">Description</label>
                                <textarea name="deskripsi" rows="5" class="w-full px-5 py-4 bg-gray-50 border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all outline-none font-medium text-gray-600 resize-none" required>{{ old('deskripsi', $itemShop->deskripsi) }}</textarea>
                                @error('deskripsi')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        {{-- KOLOM KANAN: DRAG, DROP & CROP --}}
                        <div class="space-y-4">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1 text-center lg:text-left">Product Visuals</label>
                            
                            <div id="drop-zone" class="relative group/img overflow-hidden rounded-[2.5rem] bg-gray-50 border-2 border-dashed border-gray-200 p-2 min-h-[400px] flex items-center justify-center transition-all hover:border-indigo-400 hover:bg-indigo-50/30 cursor-pointer">
                                <div id="cropper-container" class="w-full h-full flex items-center justify-center overflow-hidden rounded-[2rem]">
                                    <img id="previewImg" src="{{ $itemShop->gambar ? asset($itemShop->gambar) : 'https://via.placeholder.com/500' }}" class="max-h-[380px] w-full object-cover shadow-sm">
                                </div>

                                {{-- Overlay --}}
                                <div id="overlay-text" class="absolute inset-0 bg-indigo-900/40 backdrop-blur-[2px] opacity-0 group-hover/img:opacity-100 transition-all duration-300 flex flex-col items-center justify-center text-white pointer-events-none">
                                    <div class="bg-white/20 p-4 rounded-full mb-3 shadow-xl">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </div>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Drop Image or Click to Edit</span>
                                </div>
                            </div>

                            {{-- Input Hidden --}}
                            <input type="file" id="gambar_input" accept="image/*" class="hidden">
                            <input type="hidden" id="cropped_image_data" name="gambar">

                            {{-- Crop Controls --}}
                            <div id="crop-actions" class="hidden grid grid-cols-2 gap-3">
                                <button type="button" onclick="saveCrop()" class="py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all">Save Crop</button>
                                <button type="button" onclick="cancelCrop()" class="py-4 bg-white text-gray-400 border border-gray-100 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-50 transition-all">Cancel</button>
                            </div>
                        </div>
                    </div>

                    {{-- BUTTONS --}}
                    <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-gray-50">
                        <a href="{{ route('item-shop.index') }}" class="flex-1 text-center px-8 py-5 text-gray-400 font-black uppercase tracking-widest text-[10px] hover:text-gray-600 transition-all italic">Discard Changes</a>
                        <button type="submit" class="flex-[2] px-8 py-5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-3xl shadow-xl shadow-indigo-200 hover:shadow-indigo-400 hover:-translate-y-1 transition-all font-black uppercase tracking-widest text-[10px]">Update Premium Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let cropper;
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('gambar_input');
        const image = document.getElementById('previewImg');
        const cropActions = document.getElementById('crop-actions');
        const croppedInput = document.getElementById('cropped_image_data');

        dropZone.onclick = () => !cropper && fileInput.click();

        // Drag and Drop Logic
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eName => {
            dropZone.addEventListener(eName, e => { e.preventDefault(); e.stopPropagation(); });
        });

        dropZone.addEventListener('drop', e => handleFiles(e.dataTransfer.files));
        fileInput.onchange = e => handleFiles(e.target.files);

        function handleFiles(files) {
            if (files.length > 0) {
                const reader = new FileReader();
                reader.onload = e => initCropper(e.target.result);
                reader.readAsDataURL(files[0]);
            }
        }

        function initCropper(src) {
            image.src = src;
            cropActions.classList.remove('hidden');
            document.getElementById('overlay-text').classList.add('hidden');
            if (cropper) cropper.destroy();
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 2,
                autoCropArea: 1
            });
        }

        function saveCrop() {
            const canvas = cropper.getCroppedCanvas({ width: 800, height: 800 });
            const dataUrl = canvas.toDataURL('image/jpeg');
            image.src = dataUrl;
            croppedInput.value = dataUrl; // Data Base64
            cropper.destroy();
            cropper = null;
            cropActions.classList.add('hidden');
            document.getElementById('overlay-text').classList.remove('hidden');
        }

        function cancelCrop() {
            if (cropper) cropper.destroy();
            window.location.reload();
        }
    </script>
</x-app-layout>