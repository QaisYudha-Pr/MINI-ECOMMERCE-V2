<footer class="bg-white border-t border-gray-100 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
            {{-- Brand Section --}}
            <div class="col-span-1 md:col-span-1 space-y-6">
                <x-application-logo />
                <p class="text-sm text-gray-500 leading-relaxed font-medium">
                    {{ $siteSettings['site_tagline'] ?? 'Solusi belanja produk lokal UMKM asli Mojokerto dengan teknologi modern.' }}
                </p>
                <div class="flex items-center gap-4">
                    <a href="#" class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-[#00AA5B] hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-[#00AA5B] hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Links Section --}}
            <div class="col-span-1 space-y-6">
                <h4 class="text-xs font-black uppercase tracking-[0.2em] text-gray-900">Belanja</h4>
                <ul class="space-y-4 text-sm font-bold text-gray-500">
                    <li><a href="/shop" class="hover:text-[#00AA5B] transition-colors">Semua Produk</a></li>
                    <li><a href="#" class="hover:text-[#00AA5B] transition-colors">Promo Spesial</a></li>
                    <li><a href="#" class="hover:text-[#00AA5B] transition-colors">Produk Terlaris</a></li>
                </ul>
            </div>

            <div class="col-span-1 space-y-6">
                <h4 class="text-xs font-black uppercase tracking-[0.2em] text-gray-900">Bantuan</h4>
                <ul class="space-y-4 text-sm font-bold text-gray-500">
                    <li><a href="#" class="hover:text-[#00AA5B] transition-colors">Cara Belanja</a></li>
                    <li><a href="#" class="hover:text-[#00AA5B] transition-colors">Pendaftaran Seller</a></li>
                    <li><a href="#" class="hover:text-[#00AA5B] transition-colors">Ketentuan Layanan</a></li>
                </ul>
            </div>

            <div class="col-span-1 space-y-6">
                <h4 class="text-xs font-black uppercase tracking-[0.2em] text-gray-900">Tentang Kami</h4>
                <ul class="space-y-4 text-sm font-bold text-gray-500">
                    <li><a href="{{ route('about') }}" class="hover:text-[#00AA5B] transition-colors">Tentang MiniQ</a></li>
                    <li><a href="#" class="hover:text-[#00AA5B] transition-colors">Hubungi Kami</a></li>
                    <li><a href="#" class="hover:text-[#00AA5B] transition-colors">Kebijakan Privasi</a></li>
                </ul>
            </div>
        </div>

        {{-- Bottom Footer --}}
        <div class="pt-8 border-t border-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400">
                &copy; {{ date('Y') }} {{ $siteSettings['site_name'] ?? 'MiniQ-Store' }}. Mojokerto Digital Ecosystem.
            </p>
            <div class="flex items-center gap-6">
                <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_Kabupaten_Mojokerto.png" class="h-6 grayscale opacity-30" alt="Mojokerto Logo">
                <div class="text-[8px] font-black text-gray-300 uppercase tracking-widest leading-tight">
                    Powered by<br><span class="text-gray-400">Mojokerto Dev</span>
                </div>
            </div>
        </div>
    </div>
</footer>
