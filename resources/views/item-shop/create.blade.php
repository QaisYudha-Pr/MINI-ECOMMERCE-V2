<x-app-layout>
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        .input-focus-effect:focus {
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .custom-file-upload:hover {
            background: linear-gradient(to bottom right, rgba(99, 102, 241, 0.05), rgba(168, 85, 247, 0.05));
        }
    </style>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 overflow-hidden">
        {{-- HEADER --}}
        <div class="mb-10 text-center sm:text-left" data-aos="fade-down" data-aos-duration="1000">
            <nav class="flex mb-4 justify-center sm:justify-start" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-xs font-bold uppercase tracking-widest text-gray-400">
                    <li><a href="{{ route('item-shop.index') }}" class="hover:text-indigo-600">Produk</a></li>
                    <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                    <li class="text-indigo-600">Tambah Baru</li>
                </ol>
            </nav>
            <h1 class="text-4xl font-black text-gray-900 tracking-tight">
                Tambah <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Produk Baru</span>
            </h1>
            <p class="text-gray-500 mt-2 font-medium">Lengkapi detail produk untuk mulai berjualan di MiniE-Store.</p>
        </div>

        {{-- FORM CARD --}}
        <div class="glass-card rounded-[2.5rem] shadow-2xl shadow-gray-200/50 overflow-hidden" data-aos="zoom-in-up" data-aos-duration="1000">
            <div class="p-8 sm:p-12">
                <form action="{{ route('item-shop.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Kiri: Detail Produk --}}
                        <div class="space-y-6">
                            {{-- NAMA BARANG --}}
                            <div class="group">
                                <label for="nama_barang" class="block text-sm font-black text-gray-700 uppercase tracking-widest mb-2 ml-1 transition-colors group-focus-within:text-indigo-600">
                                    Nama Produk <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nama_barang" name="nama_barang"
                                    value="{{ old('nama_barang') }}"
                                    placeholder="Laptop ROG Zephyrus..."
                                    class="w-full px-5 py-4 bg-gray-50 border-gray-200 rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition duration-300 input-focus-effect font-medium @error('nama_barang') border-red-500 bg-red-50 @enderror"
                                    required>
                                @error('nama_barang')
                                    <p class="text-red-500 text-xs mt-2 font-bold px-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- HARGA --}}
                            <div class="group">
                                <label for="harga" class="block text-sm font-black text-gray-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-indigo-600">
                                    Harga Jual <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                        <span class="text-gray-400 font-bold">Rp</span>
                                    </div>
                                    <input type="number" id="harga" name="harga"
                                        value="{{ old('harga') }}"
                                        placeholder="0"
                                        min="0"
                                        class="w-full px-5 py-4 pl-14 bg-gray-50 border-gray-200 rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition duration-300 input-focus-effect font-bold text-indigo-600 @error('harga') border-red-500 bg-red-50 @enderror"
                                        required>
                                </div>
                                @error('harga')
                                    <p class="text-red-500 text-xs mt-2 font-bold px-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Kanan: Upload Gambar --}}
                        <div class="space-y-6">
                            <label class="block text-sm font-black text-gray-700 uppercase tracking-widest mb-2 ml-1">Foto Produk</label>
                            
                            <div class="relative group h-full">
                                <div class="custom-file-upload border-4 border-dashed border-gray-100 rounded-[2rem] p-8 text-center transition-all duration-300 cursor-pointer h-full flex flex-col items-center justify-center group-hover:border-indigo-200"
                                    onclick="document.getElementById('gambar').click()">
                                    
                                    <div id="upload-placeholder" class="space-y-4">
                                        <div class="w-20 h-20 bg-indigo-50 rounded-3xl flex items-center justify-center mx-auto text-indigo-600 group-hover:scale-110 transition-transform duration-500 shadow-lg shadow-indigo-100">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-gray-700 font-black">Pilih Gambar Terbaik</p>
                                            <p class="text-gray-400 text-xs mt-1">PNG, JPG, WebP (Max. 2MB)</p>
                                        </div>
                                    </div>

                                    <div id="preview" class="hidden w-full h-full relative group">
                                        <img id="previewImg" src="" alt="Preview" class="w-full h-64 object-cover rounded-2xl shadow-xl">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl flex items-center justify-center backdrop-blur-sm">
                                            <button type="button" onclick="removePreview(event)" class="bg-white text-red-600 p-3 rounded-xl font-bold hover:bg-red-50 transition-all transform hover:scale-110">
                                                Ganti Gambar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <input type="file" id="gambar" name="gambar" accept="image/*" class="hidden" onchange="previewImage(this)">
                            </div>
                            @error('gambar')
                                <p class="text-red-500 text-xs mt-2 font-bold px-1 text-center">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- DESKRIPSI (FULL WIDTH) --}}
                    <div class="group">
                        <label for="deskripsi" class="block text-sm font-black text-gray-700 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-indigo-600">
                            Deskripsi Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea id="deskripsi" name="deskripsi"
                            placeholder="Tuliskan spesifikasi, keunggulan, dan kondisi produk..."
                            rows="4"
                            class="w-full px-5 py-4 bg-gray-50 border-gray-200 rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition duration-300 input-focus-effect font-medium resize-none @error('deskripsi') border-red-500 bg-red-50 @enderror"
                            required>{{ old('deskripsi') }}</textarea>
                        <div class="flex justify-between mt-2 px-1">
                            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Gunakan bahasa yang menarik pembeli</p>
                            @error('deskripsi')
                                <p class="text-red-500 text-xs font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- ACTION BUTTONS --}}
                    <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-gray-50">
                        <a href="{{ route('item-shop.index') }}"
                            class="flex-1 text-center px-8 py-4 border-2 border-gray-100 text-gray-500 rounded-2xl hover:bg-gray-50 hover:text-gray-700 active:scale-95 transition-all font-black uppercase tracking-widest text-xs">
                            Batal
                        </a>
                        <button type="submit"
                            class="flex-[2] px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl shadow-xl shadow-indigo-100 hover:shadow-indigo-200 active:scale-95 transition-all font-black uppercase tracking-widest text-xs flex items-center justify-center gap-3 group">
                            <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Simpan & Publikasikan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('upload-placeholder').classList.add('hidden');
                    document.getElementById('preview').classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removePreview(event) {
            event.stopPropagation(); // Mencegah trigger click pada parent div
            document.getElementById('gambar').value = '';
            document.getElementById('preview').classList.add('hidden');
            document.getElementById('upload-placeholder').classList.remove('hidden');
        }
    </script>
</x-app-layout>