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
                    @if(isset($siteSettings['footer_social_ig']))
                    <a href="https://instagram.com/{{ $siteSettings['footer_social_ig'] }}" target="_blank" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                        <i class="fa-brands fa-instagram text-lg"></i>
                    </a>
                    @endif
                    @if(isset($siteSettings['footer_social_wa']))
                    <a href="https://wa.me/{{ $siteSettings['footer_social_wa'] }}" target="_blank" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                        <i class="fa-brands fa-whatsapp text-xl"></i>
                    </a>
                    @endif
                    @if(isset($siteSettings['footer_social_fb']))
                    <a href="{{ $siteSettings['footer_social_fb'] }}" target="_blank" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                        <i class="fa-brands fa-facebook text-lg"></i>
                    </a>
                    @endif
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
                {!! $siteSettings['footer_copyright'] ?? ('&copy; ' . date('Y') . ' ' . ($siteSettings['site_name'] ?? 'MiniQ-Store') . '. Mojokerto Digital Ecosystem.') !!}
            </p>
            @if(isset($siteSettings['footer_address']))
            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest hidden lg:block">
                {{ $siteSettings['footer_address'] }}
            </p>
            @endif
            <div class="flex items-center gap-6">
                <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_Kabupaten_Mojokerto.png" class="h-6 grayscale opacity-30" alt="Mojokerto Logo">
                <div class="text-[8px] font-black text-gray-300 uppercase tracking-widest leading-tight">
                    Powered by<br><span class="text-gray-400">Mojokerto Dev</span>
                </div>
            </div>
        </div>
    </div>
</footer>
