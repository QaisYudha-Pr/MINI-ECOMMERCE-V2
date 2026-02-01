<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <div class="max-w-5xl mx-auto px-4 py-12">
        <div class="mb-10">
            <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">Edit <span class="text-indigo-600">Product</span></h1>
            <p class="text-gray-500 font-medium italic">Update your item's identity and stock level.</p>
        </div>

        <div class="bg-white rounded-[3rem] shadow-2xl shadow-gray-200/50 p-8 sm:p-12 border border-gray-50">
            <form action="{{ route('item-shop.update', $itemShop->id) }}" method="POST" class="space-y-10">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">

                    <div class="space-y-6">
                        <div class="group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Product Name</label>
                            <input type="text" name="nama_barang" value="{{ old('nama_barang', $itemShop->nama_barang) }}" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white transition-all font-bold text-gray-700" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="group">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Category</label>
                                <input type="text" name="kategori" list="category-list" value="{{ old('kategori', $itemShop->kategori) }}" placeholder="Ketik atau pilih..." class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white transition-all font-bold text-gray-700" required>
                                <datalist id="category-list">
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}">
                                    @endforeach
                                    <option value="Fashion">
                                    <option value="Electronics">
                                    <option value="Food & Drink">
                                </datalist>
                            </div>
                            <div class="group">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Weight (Gram)</label>
                                <input type="number" name="berat" value="{{ old('berat', $itemShop->berat) }}" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white transition-all font-black text-gray-700" required>
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Stock Level</label>
                            <input type="number" name="stok" value="{{ old('stok', $itemShop->stok) }}" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white transition-all font-black text-gray-700" required>
                        </div>

                        <div class="group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Price (IDR)</label>
                            <input type="number" name="harga" value="{{ old('harga', $itemShop->harga) }}" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white transition-all font-black text-indigo-600" required>
                        </div>

                        <div class="group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Description</label>
                            <textarea name="deskripsi" rows="4" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:bg-white transition-all font-medium text-gray-600 resize-none" required>{{ old('deskripsi', $itemShop->deskripsi) }}</textarea>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 text-center">Visual Visual</label>
                        <div id="drop-zone" class="relative group/img overflow-hidden rounded-[2.5rem] bg-gray-50 border-2 border-dashed border-gray-200 min-h-[400px] flex items-center justify-center cursor-pointer">
                            <img id="previewImg" src="{{ old('gambar', asset($itemShop->gambar)) }}" class="max-h-[380px] w-full object-cover">
                            <div id="overlay-text" class="absolute inset-0 bg-indigo-900/20 opacity-0 group-hover/img:opacity-100 transition-all flex items-center justify-center backdrop-blur-[2px]">
                                <span class="text-[10px] font-black uppercase tracking-widest bg-white text-gray-900 px-4 py-2 rounded-full shadow-xl">Update Image</span>
                            </div>
                        </div>
                        <input type="file" id="gambar_input" accept="image/*" class="hidden">
                        <input type="hidden" id="cropped_image_data" name="gambar">
                    </div>
                </div>

                <div class="pt-10 border-t border-gray-50 flex gap-4">
                    <a href="{{ route('item-shop.index') }}" class="flex-1 py-6 text-center text-gray-400 font-black uppercase tracking-[0.3em] text-[10px] italic">Discard Changes</a>
                    <button type="submit" class="flex-[2] py-6 bg-indigo-600 text-white rounded-[2rem] font-black uppercase tracking-[0.3em] text-xs hover:bg-gray-900 transition-all shadow-xl shadow-indigo-100">Update Premium Listing</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODERN CROP MODAL --}}
    <div id="cropModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-gray-900/80 backdrop-blur-sm p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-2xl overflow-hidden shadow-2xl relative animate-fade-in-up">
            <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black text-gray-900 tracking-tight uppercase">UPDATE <span class="text-indigo-600">PRODUCT IMAGE</span></h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Sempurnakan tampilan produkmu bolo</p>
                </div>
                <button type="button" onclick="cancelCrop()" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-8">
                <div class="max-h-[50vh] overflow-hidden rounded-2xl bg-gray-50 border-2 border-dashed border-gray-200">
                    <img id="imageToCrop" src="" class="max-w-full block">
                </div>
            </div>
            <div class="p-8 bg-gray-50/50 border-t border-gray-50 flex gap-4">
                <button type="button" onclick="cancelCrop()" class="flex-1 py-4 bg-white text-gray-500 rounded-2xl font-black uppercase text-xs tracking-widest border border-gray-100">Batal/Reset</button>
                <button type="button" onclick="saveCrop()" class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg shadow-indigo-100">Terapkan Perubahan</button>
            </div>
        </div>
    </div>

    <script>
        let cropper;
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('gambar_input');
        const previewImg = document.getElementById('previewImg');
        const imageToCrop = document.getElementById('imageToCrop');
        const cropModal = document.getElementById('cropModal');
        const croppedInput = document.getElementById('cropped_image_data');

        dropZone.onclick = () => fileInput.click();
        
        fileInput.onchange = e => {
            const files = e.target.files;
            if (files && files.length > 0) {
                const reader = new FileReader();
                reader.onload = e => {
                    imageToCrop.src = e.target.result;
                    cropModal.classList.remove('hidden');
                    cropModal.classList.add('flex');
                    
                    if (cropper) cropper.destroy();
                    
                    setTimeout(() => {
                        cropper = new Cropper(imageToCrop, {
                            aspectRatio: 1,
                            viewMode: 1,
                            dragMode: 'move',
                            autoCropArea: 1,
                        });
                    }, 200);
                };
                reader.readAsDataURL(files[0]);
            }
        };

        function saveCrop() {
            const canvas = cropper.getCroppedCanvas({ width: 800, height: 800 });
            const base64 = canvas.toDataURL('image/jpeg', 0.9);
            previewImg.src = base64;
            croppedInput.value = base64;
            cancelCrop();
        }

        function cancelCrop() {
            cropModal.classList.add('hidden');
            cropModal.classList.remove('flex');
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            fileInput.value = '';
        }
    </script>
</x-app-layout>