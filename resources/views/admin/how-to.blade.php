<x-admin-layout>
    <div class="space-y-8">
        {{-- Header Section --}}
        <div class="bg-emerald-600 rounded-2xl p-12 text-white relative overflow-hidden shadow-lg shadow-emerald-100">
            <div class="relative z-10 max-w-2xl">
                <span class="inline-block px-4 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-xs font-semibold mb-6">Pusat Bantuan</span>
                <h1 class="text-4xl lg:text-5xl font-bold tracking-tighter leading-none mb-6">Bagaimana Cara Kerja <br>MiniQ-Store? 🛒</h1>
                <p class="text-emerald-100 text-sm font-medium leading-relaxed">Panduan lengkap untuk mulai belanja, jualan, dan mengelola tokomu dengan mudah dan aman.</p>
            </div>
            <div class="absolute right-[-5%] top-[-10%] w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        {{-- Main Navigation Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- User Guide --}}
            <div class="bg-white p-10 rounded-2xl border border-slate-100 shadow-xl shadow-slate-200/50 group hover:border-emerald-500 transition-all duration-500">
                <div class="w-16 h-16 bg-emerald-50 rounded-[1.8rem] flex items-center justify-center text-emerald-600 mb-8 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-6 tracking-tight uppercase">Untuk Pembeli (User)</h3>
                <ul class="space-y-6">
                    <li class="flex gap-4">
                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-600 text-white text-xs font-bold flex items-center justify-center mt-1">1</span>
                        <div>
                            <p class="font-bold text-slate-800 text-sm">Cari Barang</p>
                            <p class="text-slate-500 text-xs mt-1 leading-relaxed">Pilih barang yang kamu suka dari berbagai kategori di <span class="text-emerald-600 font-bold">Jelajah Toko</span>.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-600 text-white text-xs font-bold flex items-center justify-center mt-1">2</span>
                        <div>
                            <p class="font-bold text-slate-800 text-sm">Checkout & Bayar</p>
                            <p class="text-slate-500 text-xs mt-1 leading-relaxed">Masukkan ke keranjang, isi alamat, dan pilih metode pembayaran (QRIS, VA, atau Transfer).</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-600 text-white text-xs font-bold flex items-center justify-center mt-1">3</span>
                        <div>
                            <p class="font-bold text-slate-800 text-sm">Konfirmasi Terima</p>
                            <p class="text-slate-500 text-xs mt-1 leading-relaxed">Jangan lupa klik <span class="text-green-500 font-bold">Pesanan Diterima</span> jika barang sudah sampai agar saldo diteruskan ke penjual.</p>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Seller Guide --}}
            <div class="bg-white p-10 rounded-2xl border border-slate-100 shadow-xl shadow-slate-200/50 group hover:border-emerald-500 transition-all duration-500">
                <div class="w-16 h-16 bg-emerald-50 rounded-[1.8rem] flex items-center justify-center text-emerald-600 mb-8 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-6 tracking-tight uppercase">Untuk Penjual (Seller)</h3>
                <ul class="space-y-6">
                    <li class="flex gap-4">
                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-600 text-white text-xs font-bold flex items-center justify-center mt-1">1</span>
                        <div>
                            <p class="font-bold text-slate-800 text-sm">Upload Produk</p>
                            <p class="text-slate-500 text-xs mt-1 leading-relaxed">Gunakan foto asli dan deskripsi lengkap agar pembeli lebih percaya.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-600 text-white text-xs font-bold flex items-center justify-center mt-1">2</span>
                        <div>
                            <p class="font-bold text-slate-800 text-sm">Kelola Pesanan</p>
                            <p class="text-slate-500 text-xs mt-1 leading-relaxed">Pantau tab <span class="text-emerald-600 font-bold">Orders Management</span> dan update status pengiriman ke pembeli.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-600 text-white text-xs font-bold flex items-center justify-center mt-1">3</span>
                        <div>
                            <p class="font-bold text-slate-800 text-sm">Tarik Saldo</p>
                            <p class="text-slate-500 text-xs mt-1 leading-relaxed">Hasil penjualan akan masuk ke <span class="text-emerald-500 font-bold">Dompet MiniQ</span>. Kamu bisa tarik ke rekening bank/E-Wallet mu.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        {{-- FAQ Section --}}
        <div class="bg-slate-900 rounded-2xl p-12 text-white">
            <h2 class="text-3xl font-semibold mb-12 text-center underline decoration-emerald-500 underline-offset-8">Tanya Jawab (FAQ)</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                <div class="p-6 bg-white/5 rounded-2xl border border-white/10 hover:bg-white/10 transition-colors">
                    <p class="font-bold text-emerald-400 text-xs mb-2 font-bold">Q: Berapa lama Saldo masuk?</p>
                    <p class="text-emerald-100/70 text-sm">Saldo akan masuk ke dompet penjual setelah pembeli melakukan konfirmasi "Pesanan Diterima" atau 3 hari setelah status Shipped.</p>
                </div>
                <div class="p-6 bg-white/5 rounded-2xl border border-white/10 hover:bg-white/10 transition-colors">
                    <p class="font-bold text-emerald-400 text-xs mb-2 font-bold">Q: Apakah ada biaya admin?</p>
                    <p class="text-emerald-100/70 text-sm">Setiap transaksi dikenakan biaya admin flat Rp 2.500 untuk pemeliharaan sistem MiniQ-Store.</p>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

