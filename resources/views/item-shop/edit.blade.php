<x-app-layout>
    <div class="max-w-3xl mx-auto px-3 sm:px-6 mt-6 mb-16">
        {{-- HEADER --}}
        <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl font-black text-gray-900">Edit Produk</h1>
            <p class="text-gray-600 mt-2">Perbarui informasi dan detail produk Anda</p>
        </div>

        {{-- FORM CARD --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 border border-gray-100">
            <form action="{{ route('item-shop.update', $itemShop->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- NAMA BARANG --}}
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-900 mb-2">
                        Nama Produk <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                        value="{{ old('name', $itemShop->nama_barang) }}"
                        placeholder="Contoh: Laptop Gaming ASUS"
                        class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition @error('name') border-red-500 bg-red-50 @enderror"
                        required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- HARGA --}}
                <div>
                    <label for="price" class="block text-sm font-bold text-gray-900 mb-2">
                        Harga (Rp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-gray-500 font-semibold">Rp</span>
                        <input type="number" id="price" name="price"
                            value="{{ old('price', $itemShop->harga) }}"
                            placeholder="0"
                            min="0"
                            class="w-full px-4 py-3 pl-12 border rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition @error('price') border-red-500 bg-red-50 @enderror"
                            required>
                    </div>
                    @error('price')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- DESKRIPSI --}}
                <div>
                    <label for="description" class="block text-sm font-bold text-gray-900 mb-2">
                        Deskripsi Produk <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" name="description"
                        placeholder="Jelaskan detail produk Anda di sini..."
                        rows="5"
                        class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition resize-none @error('description') border-red-500 bg-red-50 @enderror"
                        required>{{ old('description', $itemShop->deskripsi) }}</textarea>
                    <p class="text-gray-500 text-xs mt-1">Minimal 10 karakter</p>
                    @error('description')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- GAMBAR --}}
                <div>
                    <label for="gambar" class="block text-sm font-bold text-gray-900 mb-3">
                        Gambar Produk
                    </label>

                    {{-- GAMBAR LAMA --}}
                    @if($itemShop->gambar)
                        <div class="mb-6 p-4 bg-gray-50 rounded-xl">
                            <p class="text-sm text-gray-600 mb-3 font-semibold">Gambar Saat Ini:</p>
                            <img src="{{ asset($itemShop->gambar) }}"
                                class="max-h-56 rounded-xl shadow-md">
                        </div>
                    @endif

                    {{-- UPLOAD GAMBAR BARU --}}
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-indigo-600 hover:bg-indigo-50 transition cursor-pointer"
                        onclick="document.getElementById('gambar').click()">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-gray-700 font-semibold">Klik atau drag gambar baru di sini</p>
                        <p class="text-gray-500 text-sm">PNG, JPG, GIF (Maksimal 2MB)</p>
                    </div>
                    <input type="file" id="gambar" name="gambar"
                        accept="image/*"
                        class="hidden"
                        onchange="previewImage(this)">
                    <div id="preview" class="mt-4 hidden">
                        <p class="text-sm text-gray-600 mb-2 font-semibold">Preview Gambar Baru:</p>
                        <img id="previewImg" src="" alt="Preview" class="max-h-64 rounded-xl shadow-md">
                        <button type="button" onclick="removePreview()" class="mt-3 inline-block px-4 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition text-sm font-semibold">
                            Hapus gambar baru
                        </button>
                    </div>
                    @error('gambar')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t">
                    <a href="{{ route('item-shop.index') }}"
                        class="flex-1 text-center px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 active:scale-95 transition-all font-semibold">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:shadow-lg active:scale-95 transition-all font-semibold flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('preview').classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removePreview() {
            document.getElementById('gambar').value = '';
            document.getElementById('preview').classList.add('hidden');
        }
    </script>
</x-app-layout>
