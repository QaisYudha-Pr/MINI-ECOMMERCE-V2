<x-admin-layout title="Settings CMS">
    <div class="py-12" x-data="{ tab: 'general' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row gap-8">
                {{-- Sidebar Tabs --}}
                <div class="w-full md:w-64 space-y-2">
                    <button @click="tab = 'general'" 
                        :class="tab === 'general' ? 'bg-[#00AA5B] text-white shadow-lg shadow-green-100 translate-x-2' : 'bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700'" 
                        class="w-full px-6 py-4 rounded-2xl font-black uppercase text-[10px] tracking-[0.2em] text-left transition-all duration-300 flex items-center justify-between group">
                        <span>Informasi Umum</span>
                        <svg :class="tab === 'general' ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'" class="w-4 h-4 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    </button>

                    <button @click="tab = 'home'" 
                        :class="tab === 'home' ? 'bg-[#00AA5B] text-white shadow-lg shadow-green-100 translate-x-2' : 'bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700'" 
                        class="w-full px-6 py-4 rounded-2xl font-black uppercase text-[10px] tracking-[0.2em] text-left transition-all duration-300 flex items-center justify-between group">
                        <span>Halaman Home</span>
                        <svg :class="tab === 'home' ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'" class="w-4 h-4 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    </button>

                    <button @click="tab = 'about'" 
                        :class="tab === 'about' ? 'bg-[#00AA5B] text-white shadow-lg shadow-green-100 translate-x-2' : 'bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700'" 
                        class="w-full px-6 py-4 rounded-2xl font-black uppercase text-[10px] tracking-[0.2em] text-left transition-all duration-300 flex items-center justify-between group">
                        <span>Halaman About</span>
                        <svg :class="tab === 'about' ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'" class="w-4 h-4 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    </button>

                    <button @click="tab = 'shipping'" 
                        :class="tab === 'shipping' ? 'bg-[#00AA5B] text-white shadow-lg shadow-green-100 translate-x-2' : 'bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700'" 
                        class="w-full px-6 py-4 rounded-2xl font-black uppercase text-[10px] tracking-[0.2em] text-left transition-all duration-300 flex items-center justify-between group">
                        <span>Ongkir & Biaya</span>
                        <svg :class="tab === 'shipping' ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'" class="w-4 h-4 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>

                <div class="flex-1">
                    
                    {{-- GENERAL SETTINGS --}}
                    <div x-show="tab === 'general'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                        <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-100 overflow-hidden border border-gray-100">
                            <div class="p-8 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                                <div>
                                    <h3 class="text-xl font-black text-gray-900 tracking-tight">Branding & Logo</h3>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Identitas visual utama situs</p>
                                </div>
                            </div>
                            <div class="p-8 grid md:grid-cols-2 gap-10">
                                <div class="space-y-6">
                                    <div class="w-40 h-40 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden">
                                        @if(isset($settings['site_logo']) && $settings['site_logo'])
                                            <img src="{{ asset($settings['site_logo']) }}" class="max-w-full max-h-full p-4 object-contain">
                                        @else
                                            <x-application-logo class="w-24 opacity-20 grayscale" />
                                        @endif
                                    </div>
                                    <form action="{{ route('admin.cms.update-logo') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" name="site_logo" class="text-xs mb-4">
                                        <button class="w-full py-4 bg-[#00AA5B] text-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-green-100 hover:scale-[1.02] transition-all">Update Logo</button>
                                    </form>
                                </div>
                                <form action="{{ route('admin.cms.update-text') }}" method="POST" class="space-y-6">
                                    @csrf
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Nama Situs</label>
                                        <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'MiniQ-Store' }}" class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#00AA5B]/20 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Slogan</label>
                                        <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] ?? '' }}" class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#00AA5B]/20 transition-all">
                                    </div>
                                    <button class="px-8 py-4 bg-gray-900 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-black transition-all">Simpan Perubahan</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- HOME SETTINGS --}}
                    <div x-show="tab === 'home'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                        <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-100 overflow-hidden border border-gray-100">
                            <div class="p-8 border-b border-gray-50 bg-gray-50/50">
                                <h3 class="text-xl font-black text-gray-900 tracking-tight">Home Hero Banner</h3>
                            </div>
                            <div class="p-8 space-y-8">
                                <div class="w-full h-48 bg-gray-100 rounded-[2rem] overflow-hidden relative group">
                                    @if(isset($settings['hero_banner']) && $settings['hero_banner'])
                                        <img src="{{ asset($settings['hero_banner']) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 font-bold">No Banner Uploaded</div>
                                    @endif
                                </div>
                                <form action="{{ route('admin.cms.update-images') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-4">
                                    @csrf
                                    <div class="flex-1">
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Upload Banner Baru (.jpg, .png)</label>
                                        <input type="file" name="hero_banner" class="text-xs">
                                    </div>
                                    <button class="px-8 py-4 bg-[#00AA5B] text-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-green-100 mt-auto">Update Banner</button>
                                </form>
                                <form action="{{ route('admin.cms.update-text') }}" method="POST" class="space-y-6 pt-8 border-t border-gray-50">
                                    @csrf
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Judul Utama Home (Hero Title)</label>
                                        <input type="text" name="home_title" value="{{ $settings['home_title'] ?? 'Belanja Keren di Mojokerto' }}" class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#00AA5B]/20 transition-all">
                                    </div>
                                    <button class="px-8 py-4 bg-gray-900 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-black transition-all">Simpan Teks</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- SHIPPING SETTINGS --}}
                    <div x-show="tab === 'shipping'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                        <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-100 overflow-hidden border border-gray-100">
                            <div class="p-8 border-b border-gray-50 bg-gray-50/50">
                                <h3 class="text-xl font-black text-gray-900 tracking-tight">Pengaturan Biaya & Jarak</h3>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Atur tarif pengiriman otomatis bolo</p>
                            </div>
                            <div class="p-8">
                                <form action="{{ route('admin.cms.update-text') }}" method="POST" class="grid md:grid-cols-2 gap-8">
                                    @csrf
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Biaya Dasar Ongkir (Rp)</label>
                                        <input type="number" name="shipping_base_fee" value="{{ $settings['shipping_base_fee'] ?? '5000' }}" class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#00AA5B]/20 transition-all">
                                        <p class="text-[9px] text-gray-400 mt-2 font-bold">Biaya minimal walaupun jarak dekat.</p>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Biaya per Kilometer (Rp)</label>
                                        <input type="number" name="shipping_per_km" value="{{ $settings['shipping_per_km'] ?? '2000' }}" class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#00AA5B]/20 transition-all">
                                        <p class="text-[9px] text-gray-400 mt-2 font-bold">Dihitung setelah jarak > 2 KM.</p>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Biaya per Kilogram (Rp)</label>
                                        <input type="number" name="shipping_per_kg" value="{{ $settings['shipping_per_kg'] ?? '1000' }}" class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#00AA5B]/20 transition-all">
                                        <p class="text-[9px] text-gray-400 mt-2 font-bold">Dikalikan dengan total berat pesanan.</p>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Biaya Layanan (Rp)</label>
                                        <input type="number" name="service_fee" value="{{ $settings['service_fee'] ?? '2500' }}" class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#00AA5B]/20 transition-all">
                                        <p class="text-[9px] text-gray-400 mt-2 font-bold">Biaya admin per transaksi.</p>
                                    </div>
                                    <div class="md:col-span-2 pt-6 border-t border-gray-50">
                                        <button class="px-10 py-5 bg-gray-900 text-white rounded-2xl font-black uppercase text-[10px] tracking-[0.2em] hover:bg-black transition-all shadow-xl shadow-gray-200">Simpan Pengaturan Biaya</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- ABOUT SETTINGS --}}
                    <div x-show="tab === 'about'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                        <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-100 overflow-hidden border border-gray-100">
                            <div class="p-8 border-b border-gray-50 bg-gray-50/50">
                                <h3 class="text-xl font-black text-gray-900 tracking-tight">Konten Halaman About</h3>
                            </div>
                            <form action="{{ route('admin.cms.update-text') }}" method="POST" class="p-8 space-y-6">
                                @csrf
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Hero Title</label>
                                        <input type="text" name="about_hero_title" value="{{ $settings['about_hero_title'] ?? 'Jembatan Keren Pelaku UMKM Mojokerto.' }}" class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#00AA5B]/20 transition-all">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Hero Description</label>
                                        <textarea name="about_hero_desc" rows="3" class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#00AA5B]/20 transition-all">{{ $settings['about_hero_desc'] ?? '' }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Misi Utama 1</label>
                                        <input type="text" name="about_mission_1" value="{{ $settings['about_mission_1'] ?? '' }}" class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#00AA5B]/20 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Misi Utama 2</label>
                                        <input type="text" name="about_mission_2" value="{{ $settings['about_mission_2'] ?? '' }}" class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#00AA5B]/20 transition-all">
                                    </div>
                                </div>
                                <button class="px-8 py-4 bg-gray-900 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-black transition-all">Simpan Konten About</button>
                            </form>
                            <div class="px-8 pb-8 border-t border-gray-50 pt-8">
                                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Foto Section About</label>
                                <div class="flex flex-col md:flex-row gap-8 items-end">
                                    <div class="w-48 h-32 bg-gray-100 rounded-2xl overflow-hidden shadow-inner flex-shrink-0">
                                        @if(isset($settings['about_image']) && $settings['about_image'])
                                            <img src="{{ asset($settings['about_image']) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">No Image</div>
                                        @endif
                                    </div>
                                    <form action="{{ route('admin.cms.update-images') }}" method="POST" enctype="multipart/form-data" class="flex-1">
                                        @csrf
                                        <input type="file" name="about_image" class="text-xs mb-4">
                                        <button class="w-full md:w-auto px-6 py-3 bg-[#00AA5B] text-white rounded-xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-green-100 hover:scale-[1.02] transition-all">Update Foto</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        button:focus { outline: none !important; }
    </style>
</x-admin-layout>
