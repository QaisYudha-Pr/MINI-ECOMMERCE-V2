<x-app-layout>
    {{-- CSS & JS for Cropper.js --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <div class="py-10">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden flex flex-col md:flex-row border border-slate-100">
                <!-- Left Side: Hero/Image -->
                <div class="w-full md:w-1/2 bg-slate-900 p-12 text-white flex flex-col justify-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-emerald-600/20 to-transparent"></div>
                    <div class="relative z-10">
                        <h2 class="text-3xl font-bold mb-4 tracking-tight">Become a Seller</h2>
                        <p class="text-slate-300 text-sm leading-relaxed mb-8">
                            Join our exclusive network of creators and merchants. Unlock the ability to list products, manage inventory, and grow your business with our premium tools.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-center gap-3 text-sm font-medium">
                                <span class="bg-emerald-500/20 p-1 rounded-full text-emerald-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                Unlimited Product Listings
                            </li>
                            <li class="flex items-center gap-3 text-sm font-medium">
                                <span class="bg-emerald-500/20 p-1 rounded-full text-emerald-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                Advanced Analytics
                            </li>
                            <li class="flex items-center gap-3 text-sm font-medium">
                                <span class="bg-emerald-500/20 p-1 rounded-full text-emerald-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                Secure Payments
                            </li>
                        </ul>
                    </div>
                    <!-- Decorative Circles -->
                    <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-emerald-600 rounded-full blur-3xl opacity-20"></div>
                </div>

                <!-- Right Side: Action -->
                <div class="w-full md:w-1/2 p-12 flex flex-col justify-center bg-white" x-data="{ agreed: false }">
                    <div class="mb-8">
                        <span class="text-xs font-bold text-emerald-600">Get Started</span>
                        <h3 class="text-2xl font-bold text-slate-900 mt-2">Activate Seller Account</h3>
                    </div>

                    <form action="{{ route('seller.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-6">
                            <label for="nama_toko" class="block text-sm font-semibold text-slate-700 mb-2">Shop Name (Nama Toko)</label>
                            <input type="text" name="nama_toko" id="nama_toko" required
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none text-slate-900 placeholder-slate-400"
                                placeholder="e.g., Qais Digital Store">
                            @error('nama_toko')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="phone" class="block text-sm font-semibold text-slate-700 mb-2">WhatsApp Number (Nomor WA)</label>
                            <input type="text" name="phone" id="phone" required
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none text-slate-900 placeholder-slate-400"
                                placeholder="e.g., 081234567890">
                            <p class="text-xs text-gray-400 mt-1">Nomor ini akan digunakan pembeli untuk menghubungi Anda.</p>
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6" x-data="{ 
                            showCropper: false, 
                            cropper: null,
                            imageUrl: null,
                            init() {
                                this.$watch('imageUrl', value => {
                                    if (value && !this.cropper) {
                                        this.$nextTick(() => {
                                            const image = document.getElementById('cropper-image');
                                            this.cropper = new Cropper(image, {
                                                aspectRatio: 3 / 2,
                                                viewMode: 2,
                                            });
                                        });
                                    }
                                });
                            },
                            handleFile(e) {
                                const file = e.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = (event) => {
                                        this.imageUrl = event.target.result;
                                        this.showCropper = true;
                                        if (this.cropper) {
                                            this.cropper.replace(this.imageUrl);
                                        }
                                    };
                                    reader.readAsDataURL(file);
                                }
                            },
                            crop() {
                                const canvas = this.cropper.getCroppedCanvas();
                                canvas.toBlob((blob) => {
                                    const file = new File([blob], 'cropped_ktp.jpg', { type: 'image/jpeg' });
                                    const dataTransfer = new DataTransfer();
                                    dataTransfer.items.add(file);
                                    document.getElementById('seller_document').files = dataTransfer.files;
                                    
                                    // Update Preview
                                    const preview = document.getElementById('ktp-preview');
                                    preview.src = canvas.toDataURL();
                                    preview.classList.remove('hidden');
                                    document.getElementById('upload-placeholder').classList.add('hidden');
                                    this.showCropper = false;
                                }, 'image/jpeg');
                            }
                        }">
                            <label for="seller_document" class="block text-sm font-semibold text-slate-700 mb-2">Upload KTP / Dokumen Identitas</label>
                            
                            <div class="flex flex-col gap-4">
                                <div class="relative w-full h-40 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 overflow-hidden group">
                                    <img id="ktp-preview" class="w-full h-full object-cover hidden">
                                    <div id="upload-placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 group-hover:text-emerald-600 transition-colors">
                                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <p class="text-xs font-semibold">Pilih Foto KTP</p>
                                    </div>
                                    <input type="file" name="seller_document" id="seller_document" required @change="handleFile"
                                        class="absolute inset-0 opacity-0 cursor-pointer">
                                </div>
                                <p class="text-xs text-gray-500">Format: JPG, PNG, JPEG. Maksimal 2MB. (Akan di-crop otomatis ke 3:2)</p>
                            </div>

                            @error('seller_document')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror

                            {{-- Overlay Cropper --}}
                            <div x-show="showCropper" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
                                <div class="bg-white rounded-2xl overflow-hidden max-w-2xl w-full shadow-lg">
                                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                                        <h4 class="text-sm font-semibold text-gray-900">Crop Foto KTP</h4>
                                        <button type="button" @click="showCropper = false" class="text-gray-400 hover:text-red-500">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <div class="p-6 bg-gray-50">
                                        <div class="max-h-[60vh] overflow-hidden rounded-2xl">
                                            <img id="cropper-image" :src="imageUrl" class="max-w-full">
                                        </div>
                                    </div>
                                    <div class="p-6 flex gap-3">
                                        <button type="button" @click="showCropper = false" class="flex-1 py-4 rounded-xl text-xs font-semibold text-gray-400 hover:bg-gray-50 transition-all">Batal</button>
                                        <button type="button" @click="crop()" class="flex-1 py-4 bg-emerald-600 text-white rounded-xl text-xs font-semibold hover:bg-[#008f4d] transition-all shadow-lg shadow-emerald-100">Simpan Crop</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-emerald-50 p-6 rounded-2xl border border-emerald-100 mb-6">
                            <div class="flex items-start gap-4">
                                <div class="shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-slate-900">Validasi Diperlukan</h4>
                                    <p class="text-xs text-slate-500 mt-1">
                                        Data Anda akan divalidasi oleh tim admin kami dalam 1x24 jam untuk memastikan keamanan ekosistem MiniQ Store.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- PERJANJIAN SELLER --}}
                        <div class="mb-8 p-6 bg-slate-50 rounded-2xl border border-slate-200">
                            <h4 class="text-xs font-semibold text-slate-400 mb-4 text-center">Perjanjian Menjadi Seller</h4>
                            
                            <div class="space-y-4 max-h-48 overflow-y-auto pr-2 custom-scrollbar text-xs text-slate-600 leading-relaxed">
                                <p>1. <strong class="text-slate-900">Komisi Platform:</strong> Setiap transaksi yang berhasil akan dikenakan biaya layanan sebesar {{ $siteSettings['seller_commission_pct'] ?? '2.5' }}% untuk pemeliharaan sistem.</p>
                                <p>2. <strong class="text-slate-900">Produk Legal:</strong> Seller dilarang keras menjual produk yang melanggar hukum, barang bajakan, atau konten dewasa.</p>
                                <p>3. <strong class="text-slate-900">Pencairan Saldo:</strong> Penarikan saldo dapat dilakukan kapan saja setelah pesanan selesai dan akan diproses dalam maksimal 2 hari kerja.</p>
                                <p>4. <strong class="text-slate-900">Akurasi Data:</strong> Seller wajib memberikan informasi toko dan dokumen identitas yang valid untuk proses verifikasi.</p>
                                <p>5. <strong class="text-slate-900">Etika Berkomunikasi:</strong> Gunakan bahasa yang sopan dan profesional saat melayani calon pembeli.</p>
                            </div>

                            <div class="mt-6 pt-4 border-t border-slate-100">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" x-model="agreed" class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-slate-300 transition-all checked:bg-emerald-600 checked:border-emerald-600" />
                                        <span class="absolute text-white transition-opacity duration-200 peer-checked:opacity-100 opacity-0 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                        </span>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-500 group-hover:text-slate-700 transition-colors">Saya setuju dengan persyaratan di atas</span>
                                </label>
                            </div>
                        </div>

                        <button type="submit" 
                            :disabled="!agreed"
                            :class="!agreed ? 'opacity-50 grayscale cursor-not-allowed shadow-none' : 'hover:bg-[#008f4d] shadow-emerald-100'"
                            class="w-full bg-emerald-600 text-white font-semibold py-4 px-6 rounded-xl transition-all active:scale-95 flex items-center justify-center gap-2 text-xs">
                            <span>Kirim Pendaftaran</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

