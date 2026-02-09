<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <div class="max-w-5xl mx-auto px-4 py-12">
        {{-- Header --}}
        <div class="mb-10 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tighter uppercase">Add <span class="text-emerald-600">New Item</span></h1>
                <p class="text-gray-500 font-medium">Create a premium listing for your store.</p>
            </div>
            <a href="{{ route('item-shop.index') }}" class="text-xs font-semibold text-gray-400 hover:text-emerald-600 transition-colors">← Back to List</a>
        </div>

        <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 p-8 sm:p-12 border border-gray-50">
            <form action="{{ route('item-shop.store') }}" method="POST" id="mainForm" class="space-y-10">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                    
                    {{-- KIRI: DATA PRODUK --}}
                    <div class="space-y-6">
                        <div class="group">
                            <label class="block text-xs font-medium text-gray-400 mb-2 ml-1">Product Name</label>
                            <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" placeholder="Ex: Aero Light Sneakers" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:bg-white transition-all font-bold text-gray-700" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="group">
                                <label class="block text-xs font-medium text-gray-400 mb-2 ml-1">Category</label>
                                <select name="kategori" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:bg-white transition-all font-bold text-gray-700 appearance-none" required>
                                    <option value="fashion">Fashion</option>
                                    <option value="electronics">Electronics</option>
                                    <option value="food">Food & Drink</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="group">
                                <label class="block text-xs font-medium text-gray-400 mb-2 ml-1">Initial Stock <span class="text-red-500">*</span></label>
                                <input type="number" name="stok" value="{{ old('stok', 0) }}" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:bg-white transition-all font-bold text-gray-700" required>
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-xs font-medium text-gray-400 mb-2 ml-1">Price (IDR)</label>
                            <div class="relative">
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">Rp</span>
                                <input type="number" name="harga" value="{{ old('harga') }}" class="w-full px-6 py-4 pl-14 bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:bg-white transition-all font-semibold text-emerald-600" required>
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-xs font-medium text-gray-400 mb-2 ml-1">Description</label>
                            <textarea name="deskripsi" rows="4" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:bg-white transition-all font-medium text-gray-600 resize-none" required>{{ old('deskripsi') }}</textarea>
                        </div>
                    </div>

                    {{-- KANAN: CROPPER --}}
                    <div class="space-y-4">
                        <label class="block text-xs font-medium text-gray-400 mb-2 text-center">Visual Identity</label>
                        <div id="drop-zone" class="relative group/img overflow-hidden rounded-2xl bg-gray-50 border-2 border-dashed border-gray-200 min-h-[400px] flex items-center justify-center transition-all hover:border-emerald-400 cursor-pointer">
                            <img id="previewImg" src="https://via.placeholder.com/500?text=Click+to+Upload" class="max-h-[380px] w-full object-cover">
                            
                            <div id="overlay-text" class="absolute inset-0 bg-emerald-900/20 opacity-0 group-hover/img:opacity-100 transition-all flex flex-col items-center justify-center text-white pointer-events-none backdrop-blur-[2px]">
                                <span class="text-xs font-semibold bg-white text-gray-900 px-4 py-2 rounded-full shadow-xl">Change Image</span>
                            </div>
                        </div>

                        <input type="file" id="gambar_input" accept="image/*" class="hidden">
                        <input type="hidden" id="cropped_image_data" name="gambar">

                        <div id="crop-actions" class="hidden grid grid-cols-2 gap-3">
                            <button type="button" onclick="saveCrop()" class="py-4 bg-emerald-600 text-white rounded-2xl font-bold text-xs shadow-lg hover:bg-emerald-700 transition-all">Apply Crop</button>
                            <button type="button" onclick="cancelCrop()" class="py-4 bg-gray-100 text-gray-400 rounded-2xl font-bold text-xs hover:bg-gray-200 transition-all">Cancel</button>
                        </div>
                    </div>
                </div>

                <div class="pt-10 border-t border-gray-50">
                    <button type="submit" class="w-full py-6 bg-gray-900 text-white rounded-2xl font-semibold text-xs hover:bg-emerald-600 hover:-translate-y-1 transition-all shadow-xl shadow-gray-200">Publish Product</button>
                </div>
            </form>
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
        fileInput.onchange = e => handleFiles(e.target.files);

        function handleFiles(files) {
            if (files.length > 0) {
                const reader = new FileReader();
                reader.onload = e => {
                    image.src = e.target.result;
                    if (cropper) cropper.destroy();
                    cropActions.classList.remove('hidden');
                    document.getElementById('overlay-text').classList.add('hidden');
                    cropper = new Cropper(image, { aspectRatio: 1, viewMode: 2 });
                };
                reader.readAsDataURL(files[0]);
            }
        }

        function saveCrop() {
            const canvas = cropper.getCroppedCanvas({ width: 800, height: 800 });
            image.src = canvas.toDataURL('image/jpeg');
            croppedInput.value = image.src;
            cropper.destroy();
            cropper = null;
            cropActions.classList.add('hidden');
            document.getElementById('overlay-text').classList.remove('hidden');
        }

        function cancelCrop() { location.reload(); }
    </script>
</x-app-layout>

