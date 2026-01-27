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

                    <button @click="tab = 'couriers'" 
                        :class="tab === 'couriers' ? 'bg-[#00AA5B] text-white shadow-lg shadow-green-100 translate-x-2' : 'bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700'" 
                        class="w-full px-6 py-4 rounded-2xl font-black uppercase text-[10px] tracking-[0.2em] text-left transition-all duration-300 flex items-center justify-between group">
                        <span>Kelola Kurir</span>
                        <svg :class="tab === 'couriers' ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'" class="w-4 h-4 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
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
                                            <img src="{{ asset($settings['site_logo']) }}" class="max-w-full max-h-full p-4 object-contain" id="preview_site_logo">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center" id="preview_site_logo_container">
                                                <x-application-logo class="w-24 opacity-20 grayscale" />
                                            </div>
                                        @endif
                                    </div>
                                    <form action="{{ route('admin.cms.update-logo') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="relative group/file mb-4">
                                            <input type="file" name="site_logo" id="site_logo_input" class="hidden" onchange="initCropper(this, 1/1, 'site_logo')">
                                            <label for="site_logo_input" class="flex items-center justify-center gap-3 w-full py-4 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl text-[10px] font-black uppercase tracking-widest text-gray-400 cursor-pointer group-hover/file:border-[#00AA5B] group-hover/file:text-[#00AA5B] transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                Pilih Logo Baru
                                            </label>
                                        </div>
                                        <input type="hidden" name="cropped_site_logo" id="cropped_site_logo">
                                        <button class="w-full py-4 bg-[#00AA5B] text-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-green-100 hover:scale-[1.02] active:scale-95 transition-all">Update Logo</button>
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
                                        <img src="{{ asset($settings['hero_banner']) }}" class="w-full h-full object-cover" id="preview_hero_banner">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 font-bold" id="preview_hero_banner_container">No Banner Uploaded</div>
                                    @endif
                                </div>
                                <form action="{{ route('admin.cms.update-images') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-4">
                                    @csrf
                                    <div class="flex-1">
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Upload Banner Baru (.jpg, .png)</label>
                                        <div class="relative group/file">
                                            <input type="file" name="hero_banner" id="hero_banner_input" class="hidden" onchange="initCropper(this, 16/9, 'hero_banner')">
                                            <label for="hero_banner_input" class="flex items-center justify-center gap-3 w-full py-4 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl text-[10px] font-black uppercase tracking-widest text-gray-400 cursor-pointer group-hover/file:border-[#00AA5B] group-hover/file:text-[#00AA5B] transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                                Pilih Banner Eksklusif
                                            </label>
                                        </div>
                                        <input type="hidden" name="cropped_hero_banner" id="cropped_hero_banner">
                                    </div>
                                    <button class="px-8 py-4 bg-[#00AA5B] text-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-green-100 mt-auto hover:scale-[1.02] active:scale-95 transition-all">Update Banner</button>
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
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Biaya Handling Kurir (Rp)</label>
                                        <input type="number" name="shipping_handling_fee" value="{{ $settings['shipping_handling_fee'] ?? '0' }}" class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#00AA5B]/20 transition-all">
                                        <p class="text-[9px] text-gray-400 mt-2 font-bold">Biaya tambahan khusus kurir lokal.</p>
                                    </div>
                                    <div class="md:col-span-2 pt-6 border-t border-gray-50">
                                        <button class="px-10 py-5 bg-gray-900 text-white rounded-2xl font-black uppercase text-[10px] tracking-[0.2em] hover:bg-black transition-all shadow-xl shadow-gray-200">Simpan Pengaturan Biaya</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- COURIER SETTINGS --}}
                    <div x-show="tab === 'couriers'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                        <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-100 overflow-hidden border border-gray-100">
                            <div class="p-8 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                                <div>
                                    <h3 class="text-xl font-black text-gray-900 tracking-tight">Daftar Kurir Lokal</h3>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Kelola opsi pengiriman internal</p>
                                </div>
                                <button onclick="openModal('addCourierModal')" class="px-6 py-3 bg-[#00AA5B] text-white rounded-xl font-black uppercase text-[9px] tracking-widest shadow-lg shadow-green-100 hover:scale-[1.05] transition-all">Tambah Kurir</button>
                            </div>
                            <div class="p-8">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left">
                                        <thead>
                                            <tr class="border-b border-gray-50">
                                                <th class="pb-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Nama/Layanan</th>
                                                <th class="pb-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Biaya Tambahan</th>
                                                <th class="pb-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Estimasi</th>
                                                <th class="pb-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Status</th>
                                                <th class="pb-4 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50">
                                            @foreach($couriers as $courier)
                                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                                <td class="py-6">
                                                    <p class="text-sm font-black text-gray-800 uppercase">{{ $courier->name }} - {{ $courier->service_name }}</p>
                                                    <p class="text-[10px] font-bold text-gray-400 mt-1">{{ $courier->description }}</p>
                                                </td>
                                                <td class="py-6">
                                                    <p class="text-sm font-black text-[#00AA5B]">+Rp{{ number_format($courier->base_extra_cost, 0, ',', '.') }}</p>
                                                    <p class="text-[10px] font-bold text-gray-400 mt-1">Multiplier: x{{ $courier->multiplier }}</p>
                                                </td>
                                                <td class="py-6 text-sm font-bold text-gray-600 uppercase">{{ $courier->estimated_time }}</td>
                                                <td class="py-6">
                                                    <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest {{ $courier->is_active ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                                        {{ $courier->is_active ? 'Aktif' : 'Nonaktif' }}
                                                    </span>
                                                </td>
                                                <td class="py-6 text-right">
                                                    <div class="flex items-center justify-end gap-2">
                                                        <button onclick="editCourier({{ $courier->toJson() }})" class="p-2 hover:bg-white rounded-lg transition-colors group/btn">
                                                            <svg class="w-5 h-5 text-gray-400 group-hover/btn:text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                        </button>
                                                        <button onclick="toggleCourier({{ $courier->id }})" class="p-2 hover:bg-white rounded-lg transition-colors group/btn">
                                                            <svg class="w-5 h-5 text-gray-400 group-hover/btn:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                                        </button>
                                                        <button onclick="deleteCourier({{ $courier->id }})" class="p-2 hover:bg-white rounded-lg transition-colors group/btn">
                                                            <svg class="w-5 h-5 text-gray-400 group-hover/btn:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
                                            <img src="{{ asset($settings['about_image']) }}" class="w-full h-full object-cover" id="preview_about_image">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300" id="preview_about_image_container">No Image</div>
                                        @endif
                                    </div>
                                    <form action="{{ route('admin.cms.update-images') }}" method="POST" enctype="multipart/form-data" class="flex-1">
                                        @csrf
                                        <div class="relative group/file mb-4">
                                            <input type="file" name="about_image" id="about_image_input" class="hidden" onchange="initCropper(this, 16/9, 'about_image')">
                                            <label for="about_image_input" class="flex items-center justify-center gap-3 w-full py-4 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl text-[10px] font-black uppercase tracking-widest text-gray-400 cursor-pointer group-hover/file:border-[#00AA5B] group-hover/file:text-[#00AA5B] transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                Ganti Foto Profil Perusahaan
                                            </label>
                                        </div>
                                        <input type="hidden" name="cropped_about_image" id="cropped_about_image">
                                        <button class="w-full md:w-auto px-6 py-3 bg-[#00AA5B] text-white rounded-xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-green-100 hover:scale-[1.02] active:scale-95 transition-all">Update Foto</button>
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

    {{-- CROPPER MODAL --}}
    <div id="cropperModal" class="fixed inset-0 z-[200] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black/90 backdrop-blur-sm transition-opacity"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-[2.5rem] w-full max-w-4xl overflow-hidden shadow-2xl">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 tracking-tight">CROP GAMBAR BOLO</h3>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Sesuaikan posisi gambar biar pas</p>
                    </div>
                    <button onclick="closeCropper()" class="p-2 hover:bg-white rounded-xl transition-colors shadow-sm">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-8">
                    <div class="max-h-[60vh] overflow-hidden rounded-2xl bg-gray-100">
                        <img id="cropperImage" src="" class="max-w-full">
                    </div>
                </div>
                <div class="p-6 bg-gray-50/50 border-t border-gray-50 flex justify-end gap-3">
                    <button onclick="closeCropper()" class="px-8 py-4 bg-white text-gray-500 rounded-2xl font-black uppercase text-[10px] tracking-widest border border-gray-100 hover:bg-gray-100 transition-all">Batal</button>
                    <button onclick="applyCrop()" class="px-10 py-4 bg-[#00AA5B] text-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-green-100 hover:scale-[1.02] transition-all">Potong & Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODALS --}}
    <div id="addCourierModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeModal('addCourierModal')"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-[2.5rem] w-full max-w-lg overflow-hidden shadow-2xl">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="text-xl font-black text-gray-900 tracking-tight text-black">TAMBAH KURIR LOKAL</h3>
                    <button onclick="closeModal('addCourierModal')" class="p-2 hover:bg-gray-50 rounded-xl transition-colors">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="{{ route('admin.cms.couriers.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-1">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Nama Kurir</label>
                            <input type="text" name="name" required placeholder="Contoh: SADEWA" class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#FFD600]/20 transition-all text-black">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Nama Layanan</label>
                            <input type="text" name="service_name" required placeholder="Contoh: KILAT" class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#FFD600]/20 transition-all text-black">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Multiplier Biaya</label>
                            <input type="number" name="multiplier" step="0.1" value="1.0" required class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#FFD600]/20 transition-all text-black">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Biaya Tambahan (Rp)</label>
                            <input type="number" name="base_extra_cost" value="0" required class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#FFD600]/20 transition-all text-black">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Estimasi Waktu</label>
                            <input type="text" name="estimated_time" placeholder="2-4 JAM atau 1 HARI" required class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#FFD600]/20 transition-all text-black">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Deskripsi Singkat</label>
                            <textarea name="description" rows="2" class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#FFD600]/20 transition-all text-black" placeholder="Pesan sekarang, sampai sebentar lagi..."></textarea>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full py-5 bg-black text-white rounded-2xl font-black uppercase text-[10px] tracking-[0.2em] hover:bg-gray-900 transition-all shadow-xl shadow-gray-200">Simpan Kurir Baru</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- EDIT COURIER MODAL --}}
    <div id="editCourierModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeModal('editCourierModal')"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-[2.5rem] w-full max-w-lg overflow-hidden shadow-2xl">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="text-xl font-black text-gray-900 tracking-tight text-black">EDIT KURIR LOKAL</h3>
                    <button onclick="closeModal('editCourierModal')" class="p-2 hover:bg-gray-50 rounded-xl transition-colors">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form id="editCourierForm" action="" method="POST" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-1">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Nama Kurir</label>
                            <input type="text" name="name" id="edit_name" required class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#FFD600]/20 transition-all text-black">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Nama Layanan</label>
                            <input type="text" name="service_name" id="edit_service_name" required class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#FFD600]/20 transition-all text-black">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Multiplier Biaya</label>
                            <input type="number" name="multiplier" id="edit_multiplier" step="0.1" required class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#FFD600]/20 transition-all text-black">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Biaya Tambahan (Rp)</label>
                            <input type="number" name="base_extra_cost" id="edit_base_extra_cost" required class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#FFD600]/20 transition-all text-black">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Estimasi Waktu</label>
                            <input type="text" name="estimated_time" id="edit_estimated_time" required class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#FFD600]/20 transition-all text-black">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Deskripsi Singkat</label>
                            <textarea name="description" id="edit_description" rows="2" class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#FFD600]/20 transition-all text-black"></textarea>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full py-5 bg-black text-white rounded-2xl font-black uppercase text-[10px] tracking-[0.2em] hover:bg-gray-900 transition-all shadow-xl shadow-gray-200">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form id="toggleCourierForm" method="POST" action="" style="display:none;">@csrf</form>
    <form id="deleteCourierForm" method="POST" action="" style="display:none;">@csrf @method('DELETE')</form>

    <script>
        let cropper = null;
        let currentInput = null;
        let currentType = '';

        function initCropper(input, aspectRatio, type) {
            if (input.files && input.files[0]) {
                currentInput = input;
                currentType = type;
                const reader = new FileReader();
                reader.onload = function(e) {
                    const image = document.getElementById('cropperImage');
                    image.src = e.target.result;
                    document.getElementById('cropperModal').classList.remove('hidden');
                    
                    if (cropper) {
                        cropper.destroy();
                    }
                    
                    cropper = new Cropper(image, {
                        aspectRatio: aspectRatio,
                        viewMode: 2,
                        dragMode: 'move',
                        autoCropArea: 1,
                        restore: false,
                        guides: true,
                        center: true,
                        highlight: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: false,
                    });
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function closeCropper() {
            document.getElementById('cropperModal').classList.add('hidden');
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            currentInput.value = ''; // Reset input if cancelled
        }

        function applyCrop() {
            if (!cropper) return;

            const canvas = cropper.getCroppedCanvas({
                width: currentType === 'site_logo' ? 400 : 1200, // Quality settings
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });

            const base64Image = canvas.toDataURL('image/jpeg', 0.9);
            
            // Set hidden input value
            document.getElementById('cropped_' + currentType).value = base64Image;

            // Update preview
            const preview = document.getElementById('preview_' + currentType);
            const container = document.getElementById('preview_' + currentType + '_container');
            
            if (preview) {
                preview.src = base64Image;
            } else if (container) {
                container.innerHTML = `<img src="${base64Image}" class="max-w-full max-h-full object-contain">`;
            }

            document.getElementById('cropperModal').classList.add('hidden');
            
            // Optional: Info SweetAlert
            Swal.fire({
                icon: 'info',
                title: 'Gambar Terpotong!',
                text: 'Jangan lupa klik tombol "Update" untuk menyimpan ke server.',
                timer: 2000,
                showConfirmButton: false,
                customClass: { popup: 'rounded-[1.5rem]' }
            });
        }

        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        function editCourier(courier) {
            const form = document.getElementById('editCourierForm');
            form.action = `/admin/cms/couriers/${courier.id}`;
            
            document.getElementById('edit_name').value = courier.name;
            document.getElementById('edit_service_name').value = courier.service_name;
            document.getElementById('edit_description').value = courier.description;
            document.getElementById('edit_multiplier').value = courier.multiplier;
            document.getElementById('edit_base_extra_cost').value = courier.base_extra_cost;
            document.getElementById('edit_estimated_time').value = courier.estimated_time;
            
            openModal('editCourierModal');
        }
        function toggleCourier(id) {
            Swal.fire({
                title: 'Ubah Status?',
                text: "Ubah status aktif kurir ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#000000',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Ubah!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-[1.5rem]',
                    confirmButton: 'rounded-xl font-bold uppercase text-[10px] tracking-widest px-6 py-3',
                    cancelButton: 'rounded-xl font-bold uppercase text-[10px] tracking-widest px-6 py-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('toggleCourierForm');
                    form.action = `/admin/cms/couriers/${id}/toggle`;
                    form.submit();
                }
            });
        }
        function deleteCourier(id) {
            Swal.fire({
                title: 'Hapus Kurir?',
                text: "Kurir akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-[1.5rem]',
                    confirmButton: 'rounded-xl font-bold uppercase text-[10px] tracking-widest px-6 py-3',
                    cancelButton: 'rounded-xl font-bold uppercase text-[10px] tracking-widest px-6 py-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteCourierForm');
                    form.action = `/admin/cms/couriers/${id}`;
                    form.submit();
                }
            });
        }
    </script>
</x-admin-layout>
