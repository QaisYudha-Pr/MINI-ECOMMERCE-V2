<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden flex flex-col md:flex-row">
                <!-- Left Side: Hero/Image -->
                <div class="w-full md:w-1/2 bg-slate-900 p-12 text-white flex flex-col justify-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-indigo-600/20 to-transparent"></div>
                    <div class="relative z-10">
                        <h2 class="text-3xl font-bold mb-4 tracking-tight">Become a Seller</h2>
                        <p class="text-slate-300 text-sm leading-relaxed mb-8">
                            Join our exclusive network of creators and merchants. Unlock the ability to list products, manage inventory, and grow your business with our premium tools.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-center gap-3 text-sm font-medium">
                                <span class="bg-indigo-500/20 p-1 rounded-full text-indigo-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                Unlimited Product Listings
                            </li>
                            <li class="flex items-center gap-3 text-sm font-medium">
                                <span class="bg-indigo-500/20 p-1 rounded-full text-indigo-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                Advanced Analytics
                            </li>
                            <li class="flex items-center gap-3 text-sm font-medium">
                                <span class="bg-indigo-500/20 p-1 rounded-full text-indigo-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                Secure Payments
                            </li>
                        </ul>
                    </div>
                    <!-- Decorative Circles -->
                    <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-indigo-600 rounded-full blur-3xl opacity-20"></div>
                </div>

                <!-- Right Side: Action -->
                <div class="w-full md:w-1/2 p-12 flex flex-col justify-center bg-white" x-data="{ agreed: false }">
                    <div class="mb-8">
                        <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Siap Jualan Bolo?</span>
                        <h3 class="text-2xl font-black text-slate-900 mt-2 uppercase tracking-tight">Aktivasi Akun Seller</h3>
                    </div>

                    <form action="{{ route('seller.store') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="nama_toko" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Nama Toko Kerenmu</label>
                            <input type="text" name="nama_toko" id="nama_toko" required
                                class="w-full px-5 py-4 rounded-2xl bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-600/20 transition-all outline-none text-slate-900 font-bold placeholder-slate-300"
                                placeholder="Contoh: Maju Jaya Digital">
                            @error('nama_toko')
                                <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-indigo-50/50 p-6 rounded-3xl border border-indigo-100/50 mb-8">
                            <h4 class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em] mb-3">Syarat & Kebijakan Bolo</h4>
                            <div class="max-h-40 overflow-y-auto pr-2 mb-4 space-y-3 no-scrollbar">
                                <div class="flex gap-3">
                                    <div class="w-5 h-5 bg-white rounded-lg flex items-center justify-center text-indigo-600 shrink-0 shadow-sm border border-indigo-50">
                                        <span class="text-[10px] font-black">1</span>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-500 leading-relaxed uppercase">Biaya Layanan Platform sebesar Rp2.500 per transaksi sukses.</p>
                                </div>
                                <div class="flex gap-3">
                                    <div class="w-5 h-5 bg-white rounded-lg flex items-center justify-center text-indigo-600 shrink-0 shadow-sm border border-indigo-50">
                                        <span class="text-[10px] font-black">2</span>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-500 leading-relaxed uppercase">Komisi Seller sebesar 5% akan dipotong otomatis dari total harga produk saat pesanan selesai.</p>
                                </div>
                                <div class="flex gap-3">
                                    <div class="w-5 h-5 bg-white rounded-lg flex items-center justify-center text-indigo-600 shrink-0 shadow-sm border border-indigo-50">
                                        <span class="text-[10px] font-black">3</span>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-500 leading-relaxed uppercase">Seller wajib menjaga kualitas barang dan kecepatan pengiriman.</p>
                                </div>
                                <div class="flex gap-3">
                                    <div class="w-5 h-5 bg-white rounded-lg flex items-center justify-center text-indigo-600 shrink-0 shadow-sm border border-indigo-50">
                                        <span class="text-[10px] font-black">4</span>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-500 leading-relaxed uppercase">Pencairan saldo (withdraw) diproses maksimal 1x24 jam hari kerja.</p>
                                </div>
                            </div>
                            
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative flex items-center">
                                    <input type="checkbox" x-model="agreed" class="w-5 h-5 appearance-none bg-white border-2 border-indigo-100 rounded-lg checked:bg-indigo-600 checked:border-indigo-600 transition-all cursor-pointer">
                                    <svg class="w-3.5 h-3.5 text-white absolute left-0.5 top-0.5 pointer-events-none opacity-0 transition-opacity" :class="agreed ? 'opacity-100' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest group-hover:text-indigo-600 transition-colors">SAYA SETUJU & SIAP JUALAN BOLO</span>
                            </label>
                        </div>

                        <button type="submit" 
                            :disabled="!agreed"
                            :class="!agreed ? 'opacity-50 grayscale cursor-not-allowed' : 'hover:bg-indigo-700 shadow-indigo-100'"
                            class="w-full bg-indigo-600 text-white font-black py-5 px-6 rounded-2xl transition-all shadow-xl uppercase text-xs tracking-[0.2em] active:scale-95 flex items-center justify-center gap-3">
                            <span>GAS JADI SELLER</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
