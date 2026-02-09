<x-admin-layout title="Settings CMS">
    <div x-data="{ tab: localStorage.getItem('active_cms_tab') || 'general' }" x-init="$watch('tab', val => localStorage.setItem('active_cms_tab', val))">
        <div class="max-w-7xl mx-auto">
            
            {{-- Navigation & Save Actions --}}
            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-8 mb-16" x-data="{ 
                submitActive(currentTab) {
                    const activePanel = document.querySelector(`.panel-${currentTab}`);
                    if(!activePanel) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Panel pengaturan tidak ditemukan.',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        return;
                    }
                    // Strategy: Find the main submit button in the active panel
                    const targetForm = activePanel.querySelector('.primary-form') || activePanel.querySelector('.cms-form') || activePanel.querySelector('.ajax-form') || activePanel.querySelector('form');
                    if(targetForm) {
                        targetForm.requestSubmit();
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oppss!',
                            text: 'Tidak ada form yang bisa disimpan di tab ini.',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                }
            }">
                {{-- Tabs Container with Horizontal Scroll on Mobile --}}
                <div class="flex items-center gap-2 p-2 bg-white/60 backdrop-blur-md rounded-2xl border border-slate-200/60 shadow-sm overflow-x-auto no-scrollbar max-w-full">
                    <button @click="tab = 'general'" 
                        :class="tab === 'general' ? 'bg-emerald-600 text-white shadow-xl shadow-emerald-100 scale-[1.02]' : 'bg-transparent text-slate-500 hover:bg-slate-50 hover:text-slate-900'" 
                        class="px-6 py-4 rounded-2xl font-semibold uppercase text-xs tracking-[0.1em] transition-all duration-300 flex items-center gap-3 shrink-0">
                        <i class="fa-solid fa-gear"></i>
                        <span>Umum</span>
                    </button>

                    <button @click="tab = 'home'" 
                        :class="tab === 'home' ? 'bg-emerald-600 text-white shadow-xl shadow-emerald-100 scale-[1.02]' : 'bg-transparent text-slate-500 hover:bg-slate-50 hover:text-slate-900'" 
                        class="px-6 py-4 rounded-2xl font-semibold uppercase text-xs tracking-[0.1em] transition-all duration-300 flex items-center gap-3 shrink-0">
                        <i class="fa-solid fa-house"></i>
                        <span>Home</span>
                    </button>

                    <button @click="tab = 'logistics'" 
                        :class="tab === 'logistics' ? 'bg-emerald-600 text-white shadow-xl shadow-emerald-100 scale-[1.02]' : 'bg-transparent text-slate-500 hover:bg-slate-50 hover:text-slate-900'" 
                        class="px-6 py-4 rounded-2xl font-semibold uppercase text-xs tracking-[0.1em] transition-all duration-300 flex items-center gap-3 shrink-0">
                        <i class="fa-solid fa-truck-fast"></i>
                        <span>Logistik</span>
                    </button>

                    <button @click="tab = 'about'" 
                        :class="tab === 'about' ? 'bg-emerald-600 text-white shadow-xl shadow-emerald-100 scale-[1.02]' : 'bg-transparent text-slate-500 hover:bg-slate-50 hover:text-slate-900'" 
                        class="px-6 py-4 rounded-2xl font-semibold uppercase text-xs tracking-[0.1em] transition-all duration-300 flex items-center gap-3 shrink-0">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>About</span>
                    </button>

                    <button @click="tab = 'revenue'" 
                        :class="tab === 'revenue' ? 'bg-emerald-600 text-white shadow-xl shadow-emerald-100 scale-[1.02]' : 'bg-transparent text-slate-500 hover:bg-slate-50 hover:text-slate-900'" 
                        class="px-6 py-4 rounded-2xl font-semibold uppercase text-xs tracking-[0.1em] transition-all duration-300 flex items-center gap-3 shrink-0">
                        <i class="fa-solid fa-wallet"></i>
                        <span>Keuangan</span>
                    </button>

                    <button @click="tab = 'footer'" 
                        :class="tab === 'footer' ? 'bg-emerald-600 text-white shadow-xl shadow-emerald-100 scale-[1.02]' : 'bg-transparent text-slate-500 hover:bg-slate-50 hover:text-slate-900'" 
                        class="px-6 py-4 rounded-2xl font-semibold uppercase text-xs tracking-[0.1em] transition-all duration-300 flex items-center gap-3 shrink-0">
                        <i class="fa-solid fa-layer-group"></i>
                        <span>Footer</span>
                    </button>
                </div>

                <div class="flex-shrink-0">
                    <button @click="submitActive(tab)"
                        class="w-full xl:w-auto px-12 py-5 bg-[#0F172A] text-white rounded-2xl font-semibold uppercase text-[11px] tracking-[0.2em] hover:bg-black transition-all shadow-lg shadow-emerald-100 active:scale-95 border-b-4 border-black/20">
                        SIMPAN PENGATURAN BOLO
                    </button>
                </div>
            </div>

            <div class="w-full">
                {{-- Content Panels --}}
                <div class="space-y-12">
                    {{-- GENERAL SETTINGS --}}
                    <div x-show="tab === 'general'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-12 panel-general">
                        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-100">
                            <div class="p-10 border-b border-slate-50 bg-slate-50/30 flex justify-between items-center">
                                <div>
                                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight uppercase">Identitas & Branding</h3>
                                    <p class="text-xs font-medium text-slate-400 mt-1">Kelola logo dan nama situs utama</p>
                                </div>
                            </div>
                            <div class="p-10 sm:p-16">
                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
                                    {{-- LEFT: LOGO SECTION --}}
                                    <div class="lg:col-span-4 flex flex-col items-center">
                                        <div class="relative group/logo w-64 h-64">
                                            <div class="w-full h-full bg-slate-50 rounded-2xl border-8 border-white shadow-lg shadow-slate-100 flex items-center justify-center overflow-hidden relative transition-transform duration-500 group-hover/logo:scale-[1.03] ring-1 ring-slate-100">
                                                @if(isset($settings['site_logo']) && $settings['site_logo'])
                                                    <img src="{{ asset($settings['site_logo']) }}" class="w-full h-full p-10 object-contain relative z-10" id="preview_site_logo">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center relative z-10" id="preview_site_logo_container">
                                                        <x-application-logo class="w-24 opacity-20 grayscale" />
                                                    </div>
                                                @endif
                                                <div class="absolute inset-0 bg-emerald-600/5 opacity-0 group-hover/logo:opacity-100 transition-opacity duration-300"></div>
                                            </div>

                                            <form action="{{ route('admin.cms.update-logo') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="file" name="site_logo" id="site_logo_input" class="hidden" onchange="initCropper(this, 1/1, 'site_logo')">
                                                <label for="site_logo_input" class="absolute -bottom-4 -right-4 w-16 h-16 bg-emerald-600 rounded-[1.5rem] flex items-center justify-center text-white shadow-lg shadow-emerald-200 cursor-pointer hover:bg-black hover:rotate-12 transition-all duration-300 border-4 border-white">
                                                    <i class="fa-solid fa-camera text-xl"></i>
                                                </label>
                                                <input type="hidden" name="cropped_site_logo" id="cropped_site_logo">
                                                <button id="save_logo_btn" class="hidden"></button>
                                            </form>
                                        </div>
                                        <p class="mt-8 text-xs font-medium text-slate-400 text-center leading-relaxed">Rasio 1:1 disarankan<br>Klik ikon kamera ganti logo</p>
                                    </div>

                                    {{-- RIGHT: BRAND TEXT SETTINGS --}}
                                    <div class="lg:col-span-8 space-y-12">
                                        <form action="{{ route('admin.cms.update-text') }}" method="POST" class="space-y-12 cms-form primary-form">
                                            @csrf
                                            <div class="grid grid-cols-1 gap-12">
                                                <div class="relative group">
                                                    <label class="block text-[11px] font-semibold uppercase tracking-[0.4em] text-slate-400 mb-5 px-2 group-focus-within:text-emerald-600 transition-colors">NAMA SITUS</label>
                                                    <div class="relative">
                                                        <div class="absolute inset-y-0 left-0 pl-10 flex items-center pointer-events-none text-slate-300 group-focus-within:text-emerald-600 transition-colors">
                                                            <i class="fa-solid fa-store"></i>
                                                        </div>
                                                        <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'Q-Store' }}" 
                                                            class="w-full pl-20 pr-10 py-7 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-900 focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 focus:bg-white transition-all duration-300 outline-none shadow-sm"
                                                            placeholder="Masukkan Nama Situs...">
                                                    </div>
                                                </div>

                                                <div class="relative group">
                                                    <label class="block text-[11px] font-semibold uppercase tracking-[0.4em] text-slate-400 mb-5 px-2 group-focus-within:text-emerald-600 transition-colors">SLOGAN / TAGLINE</label>
                                                    <div class="relative">
                                                        <div class="absolute inset-y-0 left-0 pl-10 flex items-center pointer-events-none text-slate-300 group-focus-within:text-emerald-600 transition-colors">
                                                            <i class="fa-solid fa-quote-left"></i>
                                                        </div>
                                                        <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] ?? '' }}" 
                                                            class="w-full pl-20 pr-10 py-7 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-900 focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 focus:bg-white transition-all duration-300 outline-none shadow-sm"
                                                            placeholder="Contoh: Belanja Mudah & Murah Bolo">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="pt-6 flex justify-end gap-4">
                                                <button type="button" onclick="confirmReset('umum')" class="px-8 py-7 bg-white text-rose-500 border-2 border-rose-100 rounded-2xl font-semibold uppercase text-[11px] tracking-[0.3em] hover:bg-rose-50 transition-all duration-300">
                                                    <i class="fa-solid fa-rotate-left mr-2"></i>
                                                    RESET DEFAULT
                                                </button>
                                                <button type="submit" class="group flex items-center gap-4 px-12 py-7 bg-[#0F172A] text-white rounded-2xl font-semibold uppercase text-[11px] tracking-[0.3em] shadow-lg hover:bg-black hover:scale-[1.05] active:scale-95 transition-all duration-500 border-b-4 border-black/30">
                                                    <span>UPDATE IDENTITAS</span>
                                                    <i class="fa-solid fa-check-circle text-emerald-400 group-hover:scale-125 transition-transform"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- HOME SETTINGS --}}
                    <div x-show="tab === 'home'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8 panel-home">
                        <div class="flex justify-end mb-4">
                            <button type="button" onclick="confirmReset('home')" class="px-8 py-5 bg-white text-rose-500 border-2 border-rose-100 rounded-2xl font-semibold uppercase text-xs tracking-widest hover:bg-rose-50 transition-all flex items-center gap-2">
                                <i class="fa-solid fa-trash-arrow-up"></i>
                                RESET SEMUA KONTEN HOME KE DEFAULT
                            </button>
                        </div>
                        <div class="bg-white rounded-2xl shadow-xl shadow-gray-100 overflow-hidden border border-gray-100">
                            <div class="p-8 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 tracking-tight">Home Banner Sliders</h3>
                                    <p class="text-xs font-bold text-gray-400 mt-1">Banner bergerak di halaman utama (Rasio 21:9)</p>
                                </div>
                                <button onclick="document.getElementById('home_sliders_input').click()" class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-xs font-semibold shadow-lg shadow-emerald-100 hover:scale-[1.05] transition-all">TAMBAH SLIDER BOLO</button>
                            </div>
                            <div class="p-8">
                                <form action="{{ route('admin.cms.update-images') }}" method="POST" enctype="multipart/form-data" id="form-sliders">
                                    @csrf
                                    <input type="file" name="home_slider" id="home_sliders_input" class="hidden" onchange="initCropper(this, 21/9, 'home_slider')">
                                    <input type="hidden" name="cropped_home_slider" id="cropped_home_slider">
                                    <button type="submit" id="submit_home_slider" class="hidden"></button>
                                </form>

                                @php
                                    $sliders = isset($settings['home_sliders']) ? json_decode($settings['home_sliders'], true) : [];
                                @endphp

                                @if(count($sliders) > 0)
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                        @foreach($sliders as $slider)
                                            <div class="relative group aspect-[21/9] rounded-2xl overflow-hidden border border-gray-100 shadow-sm">
                                                <img src="{{ asset($slider) }}" class="w-full h-full object-cover">
                                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center gap-2">
                                                    <form action="{{ route('admin.cms.delete-slider') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="path" value="{{ $slider }}">
                                                        <button type="button" onclick="confirmDeleteSlider(this.form)" class="p-3 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-all shadow-xl">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="py-12 text-center border-2 border-dashed border-gray-100 rounded-2xl bg-gray-50/30">
                                        <div class="flex justify-center gap-4 mb-6">
                                            @for($i=1; $i<=3; $i++)
                                            <div class="w-32 aspect-[21/9] rounded-lg overflow-hidden border border-gray-200 grayscale opacity-50">
                                                <img src="{{ asset('banners/banner'.$i.'.svg') }}" class="w-full h-full object-cover">
                                            </div>
                                            @endfor
                                        </div>
                                        <p class="text-xs font-medium text-gray-400 leading-relaxed">
                                            Menggunakan Banner Default Q-Store<br>
                                            <span class="text-xs font-bold text-emerald-400">Unggah gambar baru untuk mengganti default</span>
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-xl shadow-slate-100/50 overflow-hidden border border-slate-100/50 mt-8">
                            <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                                <h3 class="text-xl font-bold text-slate-900 tracking-tight">Home Secondary Banner</h3>
                                <p class="text-xs font-medium text-slate-400 mt-1">Banner statis tambahan di bawah slider</p>
                            </div>
                            <div class="p-8 space-y-8">
                                <div class="w-full h-56 bg-slate-100 rounded-2xl overflow-hidden relative group border-4 border-white shadow-inner">
                                    @if(isset($settings['hero_banner']) && $settings['hero_banner'])
                                        <img src="{{ asset($settings['hero_banner']) }}" class="w-full h-full object-cover" id="preview_hero_banner">
                                    @else
                                        <img src="{{ asset('banners/hero_default.svg') }}" class="w-full h-full object-cover opacity-30 grayscale" id="preview_hero_banner">
                                        <div class="absolute inset-0 flex items-center justify-center text-slate-400 font-semibold text-[11px]" id="preview_hero_banner_container">Default Q-Store Banner</div>
                                    @endif
                                </div>
                                <form action="{{ route('admin.cms.update-images') }}" method="POST" enctype="multipart/form-data" class="flex flex-col lg:flex-row gap-6">
                                    @csrf
                                    <div class="flex-1">
                                        <label class="block text-xs font-semibold text-slate-400 mb-4">UPLOAD BANNER (.JPG, .PNG)</label>
                                        <div class="relative group/file">
                                            <input type="file" name="hero_banner" id="hero_banner_input" class="hidden" onchange="initCropper(this, 21/9, 'hero_banner')">
                                            <label for="hero_banner_input" class="flex items-center justify-center gap-4 w-full py-5 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl text-xs font-semibold uppercase tracking-[0.15em] text-slate-400 cursor-pointer hover:border-emerald-600 hover:text-emerald-600 hover:bg-emerald-50/30 transition-all duration-300">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                                PILIH BANNER BARU BOLO
                                            </label>
                                        </div>
                                        <input type="hidden" name="cropped_hero_banner" id="cropped_hero_banner">
                                    </div>
                                    <button class="px-10 py-5 bg-emerald-600 text-white rounded-2xl font-semibold uppercase text-xs tracking-[0.2em] shadow-xl shadow-emerald-100 mt-auto hover:bg-emerald-700 hover:scale-[1.02] active:scale-95 transition-all duration-300">SIMPAN BANNER BOLO</button>
                                </form>
                                <form action="{{ route('admin.cms.update-text') }}" method="POST" class="space-y-10 cms-form primary-form pt-10 border-t border-slate-50">
                                    @csrf
                                    <div class="grid grid-cols-1 lg:grid-cols-4 items-center gap-6 group">
                                        <label class="lg:col-span-1 text-[11px] font-semibold uppercase tracking-[0.25em] text-slate-400 group-focus-within:text-emerald-600 transition-colors">JUDUL UTAMA (HERO)</label>
                                        <div class="lg:col-span-3">
                                            <input type="text" name="home_title" value="{{ $settings['home_title'] ?? 'Belanja Keren di Mojokerto' }}" class="w-full px-10 py-6 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all duration-300">
                                        </div>
                                    </div>
                                    
                                    <div class="pt-4 space-y-10">
                                        <div class="grid grid-cols-1 lg:grid-cols-4 items-center gap-6 group">
                                            <label class="lg:col-span-1 text-[11px] font-semibold uppercase tracking-[0.25em] text-slate-400 group-focus-within:text-emerald-600 transition-colors">JUDUL SECTION TOKO</label>
                                            <div class="lg:col-span-3">
                                                <input type="text" name="trusted_sellers_title" value="{{ $settings['trusted_sellers_title'] ?? 'Toko Pilihan Bolo' }}" class="w-full px-10 py-6 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all duration-300">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 lg:grid-cols-4 items-center gap-6 group">
                                            <label class="lg:col-span-1 text-[11px] font-semibold uppercase tracking-[0.25em] text-slate-400 group-focus-within:text-emerald-600 transition-colors">SUB-JUDUL TOKO</label>
                                            <div class="lg:col-span-3">
                                                <input type="text" name="trusted_sellers_subtitle" value="{{ $settings['trusted_sellers_subtitle'] ?? 'Belanja aman dari seller terpercaya' }}" class="w-full px-10 py-6 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all duration-300">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-end pt-4">
                                        <button class="px-12 py-6 bg-slate-900 text-white rounded-2xl font-semibold uppercase text-[11px] tracking-[0.3em] hover:bg-black hover:scale-[1.02] active:scale-95 transition-all duration-300 shadow-lg shadow-slate-200">SIMPAN TEKS BANNER BOLO</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- LOGISTICS SETTINGS (Unified Shipping & Couriers) --}}
                    <div x-show="tab === 'logistics'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-12 panel-logistics">
                        
                        {{-- Part 1: Global Rates Configuration --}}
                        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-100 ring-1 ring-slate-100">
                            <div class="p-10 border-b border-slate-50 bg-slate-50/10">
                                <div class="flex items-center gap-6">
                                    <div class="w-14 h-14 bg-emerald-600 rounded-[1.5rem] flex items-center justify-center text-white shadow-xl shadow-emerald-100 shrink-0">
                                        <i class="fa-solid fa-calculator text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-slate-900 tracking-tight uppercase">Perhitungan Tarif & Ongkir</h3>
                                        <p class="text-xs font-medium text-slate-400 mt-1">Konfigurasi tarif dasar yang akan dikalikan dengan multiplier kurir</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-10 sm:p-16">
                                <form action="{{ route('admin.cms.update-text') }}" method="POST" class="grid grid-cols-1 gap-y-12 cms-form primary-form">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                                        <div class="space-y-4 group">
                                            <label class="block text-[11px] font-semibold text-slate-400 px-2 group-focus-within:text-emerald-600">ONGKIR MINIMAL</label>
                                            <input type="number" name="shipping_base_fee" value="{{ $settings['shipping_base_fee'] ?? '5000' }}" 
                                                class="w-full px-8 py-6 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all outline-none">
                                        </div>

                                        <div class="space-y-4 group">
                                            <label class="block text-[11px] font-semibold text-slate-400 px-2 group-focus-within:text-emerald-600">BIAYA PER KM</label>
                                            <input type="number" name="shipping_per_km" value="{{ $settings['shipping_per_km'] ?? '2000' }}" 
                                                class="w-full px-8 py-6 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all outline-none">
                                        </div>

                                        <div class="space-y-4 group">
                                            <label class="block text-[11px] font-semibold text-slate-400 px-2 group-focus-within:text-emerald-600">BIAYA PER KG</label>
                                            <input type="number" name="shipping_per_kg" value="{{ $settings['shipping_per_kg'] ?? '1000' }}" 
                                                class="w-full px-8 py-6 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all outline-none">
                                        </div>
                                    </div>

                                    <div class="bg-emerald-50/50 p-10 rounded-2xl border border-emerald-100/50 flex flex-col md:flex-row gap-8 items-center mt-4">
                                        <div class="w-16 h-16 bg-white rounded-[1.5rem] text-emerald-600 flex items-center justify-center shrink-0 shadow-sm border border-emerald-50">
                                            <i class="fa-solid fa-circle-info text-2xl"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-xs font-bold text-emerald-900 mb-2">LOGIKA HARGA AKHIR BOLO</h4>
                                            <p class="text-[11px] font-bold text-emerald-700/80 leading-relaxed">
                                                ((Minimal + Biaya Jarak + Biaya Berat) × <span class="text-emerald-900 underline font-bold decoration-emerald-200 decoration-4 underline-offset-8">Multiplier Kurir</span>) + Extra Kurir
                                            </p>
                                            <p class="text-[11px] font-medium text-slate-400 mt-2">
                                                *Biaya Layanan (Admin Fee) ditambahkan otomatis pada Total Akhir Belanja (Grand Total).
                                            </p>
                                        </div>
                                        <button type="button" onclick="confirmReset('logistik')" class="w-full md:w-auto px-8 py-6 bg-white text-rose-500 border-2 border-rose-100 rounded-2xl font-semibold uppercase text-xs tracking-widest hover:bg-rose-50 transition-all shrink-0">
                                            RESET TARIF
                                        </button>
                                        <button type="submit" class="w-full md:w-auto px-10 py-6 bg-[#0F172A] text-white rounded-2xl font-semibold uppercase text-xs tracking-[0.3em] hover:bg-black transition-all shadow-xl border-b-4 border-black/20 shrink-0">
                                            <span>SIMPAN TARIF BOLO</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Part 2: Courier Services List --}}
                        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-100 ring-1 ring-slate-100">
                            <div class="p-10 border-b border-slate-50 bg-slate-50/30 flex justify-between items-center flex-wrap gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm">
                                        <i class="fa-solid fa-motorcycle text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-slate-900 tracking-tight uppercase">Daftar Layanan Kurir</h3>
                                        <p class="text-xs font-medium text-slate-400 mt-1">Kelola aturan multiplier ditiap jenis layanan</p>
                                    </div>
                                </div>
                                <button onclick="openModal('addCourierModal')" class="px-8 py-5 bg-emerald-600 text-white rounded-2xl font-semibold uppercase text-xs tracking-widest shadow-xl shadow-emerald-100 hover:bg-emerald-700 hover:scale-[1.05] transition-all flex items-center gap-3">
                                    <i class="fa-solid fa-plus-circle text-lg"></i>
                                    <span>TAMBAH KURIR BOLO</span>
                                </button>
                            </div>
                            <div class="p-4 sm:p-10">
                                <div class="overflow-x-auto custom-scrollbar">
                                    <table class="w-full text-left min-w-[900px]">
                                        <thead>
                                            <tr>
                                                <th class="pb-8 px-6 text-xs font-semibold text-slate-400">Layanan</th>
                                                <th class="pb-8 px-6 text-xs font-semibold text-slate-400">Parameter Biaya</th>
                                                <th class="pb-8 px-6 text-xs font-semibold text-slate-400">Limitansi</th>
                                                <th class="pb-8 px-6 text-xs font-semibold text-slate-400">Status</th>
                                                <th class="pb-8 px-6 text-xs font-semibold text-slate-400 text-right">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @foreach($couriers as $courier)
                                            <tr class="group hover:bg-slate-50 transition-all duration-300">
                                                <td class="py-8 px-6">
                                                    <div class="flex items-center gap-4">
                                                        <div class="w-12 h-12 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-sm">
                                                            <i class="fa-solid fa-truck-pickup text-sm"></i>
                                                        </div>
                                                        <div>
                                                            <p class="font-bold text-sm text-slate-900 uppercase group-hover:text-emerald-600 transition-colors">{{ $courier->name }}</p>
                                                            <p class="text-xs font-bold text-slate-400 mt-1">{{ $courier->service_name }} • {{ $courier->estimated_time }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-8 px-6">
                                                    <div class="flex flex-col gap-2">
                                                        <span class="inline-flex px-3 py-1 bg-emerald-50 text-emerald-700 rounded-lg font-bold text-[11px] tracking-widest uppercase border border-emerald-100 w-fit">Multiplier: x{{ $courier->multiplier }}</span>
                                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">Extra Fee: Rp{{ number_format($courier->base_extra_cost ?? 0, 0, ',', '.') }}</span>
                                                    </div>
                                                </td>
                                                <td class="py-8 px-6">
                                                    <div class="flex flex-col gap-2">
                                                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wide">Jarak Max: {{ $courier->max_distance ?? '∞' }} KM</span>
                                                        <span class="text-xs font-bold text-emerald-400 uppercase tracking-wide">Beban Max: {{ $courier->max_weight ?? '∞' }} KG</span>
                                                    </div>
                                                </td>
                                                <td class="py-8 px-6">
                                                    <span class="inline-flex px-4 py-1.5 rounded-full text-[11px] font-semibold uppercase tracking-[0.1em] {{ $courier->is_active ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                                        {{ $courier->is_active ? 'Aktif Bolo' : 'Nonaktif' }}
                                                    </span>
                                                </td>
                                                <td class="py-8 px-6 text-right">
                                                    <div class="flex items-center justify-end gap-3">
                                                        <button onclick="editCourier({{ $courier->toJson() }})" class="w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                        </button>
                                                        <button onclick="toggleCourier({{ $courier->id }})" class="w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                                            <i class="fa-solid fa-power-off text-xs"></i>
                                                        </button>
                                                        <button onclick="deleteCourier({{ $courier->id }})" class="w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                                                            <i class="fa-solid fa-trash-can text-xs"></i>
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
                    <div x-show="tab === 'about'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8 panel-about">
                        <div class="bg-white rounded-2xl shadow-xl shadow-slate-100/50 overflow-hidden border border-slate-100/50">
                            <div class="p-10 border-b border-slate-50 bg-slate-50/30">
                                <h3 class="text-2xl font-bold text-slate-900 tracking-tight uppercase">Konten Halaman About</h3>
                                <p class="text-xs font-medium text-slate-400 mt-2">Atur cerita dan visi platform bolo</p>
                            </div>
                            <form action="{{ route('admin.cms.update-text') }}" method="POST" class="p-10 space-y-12 cms-form primary-form">
                                @csrf
                                <div class="grid grid-cols-1 gap-10">
                                    <div class="grid grid-cols-1 lg:grid-cols-4 items-center gap-6 group">
                                        <label class="lg:col-span-1 text-[11px] font-semibold uppercase tracking-[0.25em] text-slate-400 group-focus-within:text-emerald-600 transition-colors">JUDUL HERO</label>
                                        <div class="lg:col-span-3">
                                            <input type="text" name="about_hero_title" value="{{ $settings['about_hero_title'] ?? 'Jembatan Keren Pelaku UMKM Mojokerto.' }}" class="w-full px-10 py-6 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all duration-300">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 lg:grid-cols-4 items-start gap-6 group">
                                        <label class="lg:col-span-1 text-[11px] font-semibold uppercase tracking-[0.25em] text-slate-400 group-focus-within:text-emerald-600 transition-colors pt-6">DESKRIPSI ABOUT</label>
                                        <div class="lg:col-span-3">
                                            <textarea name="about_hero_desc" rows="5" class="w-full px-10 py-6 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all duration-300 resize-none">{{ $settings['about_hero_desc'] ?? '' }}</textarea>
                                        </div>
                                    </div>

                                    <div class="grid lg:grid-cols-2 gap-10">
                                        <div class="grid grid-cols-1 lg:grid-cols-4 items-center gap-6 group">
                                            <label class="lg:col-span-1 text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-400 group-focus-within:text-emerald-600 transition-colors">MISI 1</label>
                                            <div class="lg:col-span-3">
                                                <input type="text" name="about_mission_1" value="{{ $settings['about_mission_1'] ?? '' }}" class="w-full px-10 py-6 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all duration-300">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 lg:grid-cols-4 items-center gap-6 group">
                                            <label class="lg:col-span-1 text-[11px] font-semibold uppercase tracking-[0.1em] text-slate-400 group-focus-within:text-emerald-600 transition-colors">MISI 2</label>
                                            <div class="lg:col-span-3">
                                                <input type="text" name="about_mission_2" value="{{ $settings['about_mission_2'] ?? '' }}" class="w-full px-10 py-6 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all duration-300">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end pt-6 gap-4">
                                    <button type="button" onclick="confirmReset('about')" class="px-8 py-6 bg-white text-rose-500 border-2 border-rose-100 rounded-2xl font-semibold uppercase text-[11px] tracking-[0.3em] hover:bg-rose-50 transition-all duration-300">
                                        RESET ABOUT
                                    </button>
                                    <button class="px-12 py-6 bg-slate-900 text-white rounded-2xl font-semibold uppercase text-[11px] tracking-[0.3em] hover:bg-black hover:scale-[1.02] active:scale-95 transition-all duration-300 shadow-lg shadow-slate-200">SIMPAN KONTEN ABOUT BOLO</button>
                                </div>
                            </form>
                            
                            <div class="p-10 border-t border-slate-50 bg-slate-50/10">
                                <label class="block text-xs font-semibold text-slate-400 mb-6">FOTO SECTION ABOUT (16:9)</label>
                                <div class="flex flex-col lg:flex-row gap-10 items-start">
                                    <div class="w-full lg:w-72 h-44 bg-slate-100 rounded-2xl overflow-hidden shadow-inner flex-shrink-0 border-4 border-white">
                                        @if(isset($settings['about_image']) && $settings['about_image'])
                                            <img src="{{ asset($settings['about_image']) }}" class="w-full h-full object-cover" id="preview_about_image">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300 font-bold text-xs" id="preview_about_image_container">No Image</div>
                                        @endif
                                    </div>
                                    <form action="{{ route('admin.cms.update-images') }}" method="POST" enctype="multipart/form-data" class="flex-1 w-full space-y-6">
                                        @csrf
                                        <div class="relative group/file">
                                            <input type="file" name="about_image" id="about_image_input" class="hidden" onchange="initCropper(this, 16/9, 'about_image')">
                                            <label for="about_image_input" class="flex items-center justify-center gap-4 w-full py-6 bg-white border-2 border-dashed border-slate-200 rounded-2xl text-xs font-semibold text-slate-400 cursor-pointer hover:border-emerald-600 hover:text-emerald-600 transition-all duration-300">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                PILIH FOTO ABOUT BOLO
                                            </label>
                                        </div>
                                        <input type="hidden" name="cropped_about_image" id="cropped_about_image">
                                        <button class="w-full md:w-auto px-10 py-5 bg-emerald-600 text-white rounded-2xl font-semibold uppercase text-xs tracking-[0.2em] shadow-xl shadow-emerald-100 hover:bg-emerald-700 hover:scale-[1.02] active:scale-95 transition-all duration-300">SIMPAN FOTO BOLO</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- REVENUE SETTINGS --}}
                    <div x-show="tab === 'revenue'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-12 panel-revenue">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                            <div class="bg-gradient-to-br from-[#0F172A] to-[#1E293B] rounded-2xl shadow-lg shadow-emerald-100 overflow-hidden p-12 flex items-center justify-between text-white relative group min-h-[200px] border border-white/5 ring-1 ring-white/10">
                                <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-500/20 rounded-full blur-[100px] group-hover:scale-150 transition-transform duration-1000"></div>
                                <div class="relative z-10">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                                        <p class="text-xs font-medium text-slate-400">Kas Platform Bolo</p>
                                    </div>
                                    <h3 class="text-4xl font-bold tracking-tight leading-none flex items-baseline gap-2">
                                        <span class="text-2xl font-bold text-slate-500">Rp</span>{{ number_format($platformEarnings, 0, ',', '.') }}
                                    </h3>
                                    <p class="text-[11px] font-bold text-slate-500 uppercase mt-4 tracking-[0.2em] bg-white/5 w-fit px-3 py-1 rounded-lg">Admin Fee + Komisi Seller</p>
                                </div>
                                <div class="w-20 h-20 bg-white/5 backdrop-blur-2xl border border-white/10 rounded-2xl flex items-center justify-center text-emerald-400 shadow-lg relative z-10 group-hover:rotate-12 transition-all duration-500">
                                    <i class="fa-solid fa-vault text-3xl"></i>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-100 p-12 flex items-center justify-between group min-h-[200px] hover:border-emerald-100 transition-colors duration-500">
                                <div class="relative z-10">
                                    <p class="text-xs font-medium text-slate-400 mb-4">Total Admin Fee</p>
                                    <h3 class="text-4xl font-semibold text-emerald-600 tracking-tight leading-none flex items-baseline gap-2">
                                        <span class="text-2xl font-bold text-emerald-200">Rp</span>{{ number_format($totalAdminFee, 0, ',', '.') }}
                                    </h3>
                                    <div class="mt-4 flex items-center gap-2">
                                        <i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i>
                                        <p class="text-[11px] font-bold text-slate-400">Pendapatan Bersih Ops</p>
                                    </div>
                                </div>
                                <div class="w-20 h-20 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 shadow-inner group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                                    <i class="fa-solid fa-percent text-3xl"></i>
                                </div>
                            </div>

                            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-100 p-12 flex items-center justify-between group min-h-[200px] hover:border-slate-300 transition-colors duration-500">
                                <div>
                                    <p class="text-xs font-medium text-slate-400 mb-4">Omzet Penjualan</p>
                                    <h3 class="text-4xl font-bold text-slate-900 tracking-tight leading-none flex items-baseline gap-2">
                                        <span class="text-2xl font-bold text-slate-300">Rp</span>{{ number_format($totalSalesItems, 0, ',', '.') }}
                                    </h3>
                                    <div class="mt-4 flex items-center gap-2">
                                        <i class="fa-solid fa-cart-shopping text-slate-300 text-xs"></i>
                                        <p class="text-[11px] font-bold text-slate-400">Total Transaksi Produk</p>
                                    </div>
                                </div>
                                <div class="w-20 h-20 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-900 shadow-inner group-hover:-rotate-12 transition-all duration-500">
                                    <i class="fa-solid fa-chart-line text-3xl"></i>
                                </div>
                            </div>
                        </div>

                        {{-- FREE SHIPPING & COMMISSION SETTINGS --}}
                        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-100 ring-1 ring-slate-100"
                             x-data="{ 
                                minOrder: '{{ $settings['free_shipping_min_order'] ?? 30000 }}',
                                maxDist: '{{ $settings['free_shipping_max_dist'] ?? 20 }}',
                                limitDist: '{{ $settings['free_shipping_limit_dist'] ?? 5 }}',
                                subsidy: '{{ $settings['free_shipping_subsidy'] ?? 5000 }}' 
                             }">
                            <div class="p-10 border-b border-slate-50 bg-emerald-50/20">
                                <div class="flex items-center gap-6">
                                    <div class="w-14 h-14 bg-emerald-600 rounded-[1.5rem] flex items-center justify-center text-white shadow-xl shadow-emerald-100 shrink-0">
                                        <i class="fa-solid fa-tags text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-slate-900 tracking-tight uppercase">Promo & Potongan Bolo</h3>
                                        <p class="text-xs font-medium text-slate-400 mt-1">Kelola aturan gratis ongkir & komisi tiap transaksi</p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-10 py-8 bg-amber-50/50 border-b border-amber-100/50 flex items-center gap-6">
                                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-amber-500 shadow-sm border border-amber-100">
                                    <i class="fa-solid fa-bolt text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-amber-900 mb-1">LOGIKA SMART PROMO BOLO</p>
                                    <p class="text-[11px] font-bold text-amber-700/80 leading-relaxed">
                                        Jarak < <span x-text="limitDist" class="text-amber-900 font-bold decoration-amber-300 underline underline-offset-4 decoration-4">5</span>KM = Gratis Ongkir TOTAL | Jarak <span x-text="limitDist">5</span>-<span x-text="maxDist">20</span>KM = Potongan <span x-text="'Rp' + parseInt(subsidy).toLocaleString('id-ID')"></span>.
                                    </p>
                                </div>
                            </div>
                            <form action="{{ route('admin.cms.settings.update') }}" method="POST" class="p-10 sm:p-16 space-y-12 ajax-form primary-form">
                                @csrf
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                                    <div class="space-y-10">
                                        <div class="flex items-center gap-4 mb-2">
                                            <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600">
                                                <i class="fa-solid fa-gift text-xs"></i>
                                            </div>
                                            <label class="text-[11px] font-semibold text-slate-400">Parameter Gratis Ongkir</label>
                                        </div>

                                        <div class="grid grid-cols-1 items-center gap-4 group">
                                            <label class="text-xs font-semibold uppercase tracking-[0.1em] text-slate-400 group-focus-within:text-emerald-600 transition-colors ml-4">MIN. BELANJA (Rp)</label>
                                            <input type="number" name="free_shipping_min_order" x-model="minOrder" class="w-full px-10 py-6 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all duration-300">
                                        </div>

                                        <div class="grid grid-cols-1 items-center gap-4 group">
                                            <label class="text-xs font-semibold uppercase tracking-[0.1em] text-slate-400 group-focus-within:text-emerald-600 transition-colors ml-4">MAX. SUBSIDI ONGKIR (Rp)</label>
                                            <input type="number" name="free_shipping_subsidy" x-model="subsidy" class="w-full px-10 py-6 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all duration-300">
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="group">
                                                <label class="text-xs font-semibold uppercase tracking-[0.1em] text-slate-400 group-focus-within:text-emerald-600 transition-colors ml-4 mb-2 block">RADIUS GRATIS TOTAL (KM)</label>
                                                <input type="number" name="free_shipping_limit_dist" x-model="limitDist" class="w-full px-8 py-6 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all duration-300">
                                            </div>
                                            <div class="group">
                                                <label class="text-xs font-semibold uppercase tracking-[0.1em] text-slate-400 group-focus-within:text-emerald-600 transition-colors ml-4 mb-2 block">MAX. JARAK PROMO (KM)</label>
                                                <input type="number" name="free_shipping_max_dist" x-model="maxDist" class="w-full px-8 py-6 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all duration-300">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-10">
                                        <div class="flex items-center gap-4 mb-2">
                                            <div class="w-8 h-8 bg-rose-50 rounded-lg flex items-center justify-center text-rose-600">
                                                <i class="fa-solid fa-hand-holding-dollar text-xs"></i>
                                            </div>
                                            <label class="text-[11px] font-semibold text-slate-400">Komisi & Pajak Platform</label>
                                        </div>

                                        <div class="grid grid-cols-1 items-center gap-4 group">
                                            <label class="text-xs font-semibold uppercase tracking-[0.1em] text-slate-400 group-focus-within:text-rose-600 transition-colors ml-4">ADMIN FEE PER ORDER (Rp)</label>
                                            <input type="number" name="admin_fee" value="{{ $settings['admin_fee'] ?? '2000' }}" class="w-full px-10 py-6 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-rose-600/5 focus:border-rose-600 transition-all duration-300">
                                        </div>

                                        <div class="grid grid-cols-1 items-center gap-4 group">
                                            <label class="text-xs font-semibold uppercase tracking-[0.1em] text-slate-400 group-focus-within:text-rose-600 transition-colors ml-4">PERSENTASE KOMISI SELLER (%)</label>
                                            <div class="relative">
                                                <input type="number" name="seller_commission_pct" value="{{ $settings['seller_commission_pct'] ?? '5' }}" class="w-full px-10 py-6 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-rose-600/5 focus:border-rose-600 transition-all duration-300">
                                                <div class="absolute right-8 top-1/2 -translate-y-1/2 font-bold text-rose-400 text-lg">%</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end pt-8 gap-4">
                                    <button type="button" onclick="confirmReset('revenue')" class="px-10 py-7 bg-white text-rose-500 border-2 border-rose-100 rounded-2xl font-semibold uppercase text-[11px] tracking-[0.3em] hover:bg-rose-50 transition-all duration-300">
                                        RESET PARAMETER
                                    </button>
                                    <button type="submit" class="group flex items-center gap-6 px-14 py-7 bg-slate-900 text-white rounded-2xl font-semibold uppercase text-[11px] tracking-[0.3em] hover:bg-black hover:scale-[1.02] active:scale-95 transition-all duration-300 shadow-lg shadow-slate-200 border-b-4 border-black/30">
                                        <span>update parameter bolo</span>
                                        <i class="fa-solid fa-arrow-right-long group-hover:translate-x-2 transition-transform"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- FINANCIAL RECAP SECTION --}}
                        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-100 ring-1 ring-slate-100">
                            <div class="p-12 border-b border-slate-50 bg-gradient-to-r from-slate-50 to-white flex flex-col lg:flex-row justify-between items-center gap-8">
                                <div>
                                    <div class="flex items-center gap-4 mb-3">
                                        <div class="w-12 h-12 bg-emerald-600 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-200">
                                            <i class="fa-solid fa-file-invoice-dollar text-white"></i>
                                        </div>
                                        <h3 class="text-2xl font-bold text-slate-900 tracking-tight uppercase">Rekapitulasi Keuangan</h3>
                                    </div>
                                    <p class="text-[11px] font-medium text-slate-400">Laporan detail transaksi & fee platform bolo</p>
                                </div>
                                <a href="{{ route('admin.cms.revenue.export') }}" class="group px-10 py-6 bg-emerald-600 text-white rounded-2xl font-semibold uppercase text-[11px] tracking-[0.3em] shadow-lg shadow-emerald-100 hover:bg-emerald-700 hover:scale-[1.05] active:scale-95 transition-all duration-300 flex items-center gap-4">
                                    <i class="fa-solid fa-file-excel text-lg group-hover:rotate-12 transition-transform"></i>
                                    <span>EKSPOR EXCEL BOLO</span>
                                </a>
                            </div>
                            
                            <div class="p-4 sm:p-10">
                                <div class="overflow-x-auto custom-scrollbar">
                                    <table class="w-full text-left min-w-[800px]">
                                        <thead>
                                            <tr>
                                                <th class="pb-8 px-6 text-xs font-semibold text-slate-400">Invoice Info</th>
                                                <th class="pb-8 px-6 text-xs font-semibold text-slate-400">Total Transaksi</th>
                                                <th class="pb-8 px-6 text-xs font-semibold text-slate-400 text-center">Admin Fee</th>
                                                <th class="pb-8 px-6 text-xs font-semibold text-slate-400">Keuntungan Toko</th>
                                                <th class="pb-8 px-6 text-xs font-semibold text-slate-400">Waktu Transaksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @php
                                                $statuses = ['paid', 'success', 'shipped', 'completed'];
                                                $recentTransactions = \App\Models\Transaction::with('user')->whereIn('status', $statuses)->latest()->take(10)->get();
                                            @endphp
                                            @foreach($recentTransactions as $trx)
                                            <tr class="group hover:bg-slate-50 transition-all duration-300">
                                                <td class="py-8 px-6">
                                                    <div class="flex items-center gap-4">
                                                        <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                                                            <i class="fa-solid fa-barcode text-xs"></i>
                                                        </div>
                                                        <div>
                                                            <p class="font-bold text-sm text-slate-900 group-hover:text-emerald-600 transition-colors">#{{ $trx->invoice_number }}</p>
                                                            <p class="text-[11px] font-bold text-slate-400 mt-1">Selesai Berhasil</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-8 px-6">
                                                    <p class="font-bold text-sm text-slate-900 tracking-tight">Rp{{ number_format($trx->total_price, 0, ',', '.') }}</p>
                                                </td>
                                                <td class="py-8 px-6 text-center">
                                                    <span class="inline-flex px-5 py-2.5 bg-emerald-50 text-emerald-700 rounded-full font-bold text-xs tracking-widest uppercase border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                                                        +Rp{{ number_format($trx->admin_fee, 0, ',', '.') }}
                                                    </span>
                                                </td>
                                                <td class="py-8 px-6">
                                                    <p class="font-bold text-sm text-slate-400 decoration-emerald-200">Rp{{ number_format($trx->total_price - $trx->admin_fee, 0, ',', '.') }}</p>
                                                </td>
                                                <td class="py-8 px-6">
                                                    <div class="flex flex-col">
                                                        <span class="text-xs font-bold text-slate-800 leading-none">{{ $trx->created_at->format('d M Y') }}</span>
                                                        <span class="text-[11px] font-bold text-slate-400 mt-1">{{ $trx->created_at->format('H:i') }} WIB</span>
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

                    {{-- FOOTER SETTINGS PANEL --}}
                    <div x-show="tab === 'footer'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-12 panel-footer">
                        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-100 ring-1 ring-slate-100">
                            <div class="p-10 border-b border-slate-50 bg-slate-50/30">
                                <div class="flex items-center gap-6">
                                    <div class="w-14 h-14 bg-emerald-600 rounded-[1.5rem] flex items-center justify-center text-white shadow-xl shadow-emerald-100 shrink-0">
                                        <i class="fa-solid fa-shoe-prints text-xl rotate-90"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-slate-900 tracking-tight uppercase">Konten Footer & Kontak</h3>
                                        <p class="text-xs font-medium text-slate-400 mt-1">Atur informasi sosial media dan copyright di kaki halaman</p>
                                    </div>
                                </div>
                            </div>
                            
                            <form action="{{ route('admin.cms.update-text') }}" method="POST" class="cms-form primary-form">
                                @csrf
                                <div class="p-10 sm:p-16 space-y-12">
                                    {{-- Social Media --}}
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                                        <div class="space-y-8">
                                            <div class="flex items-center gap-4 mb-2">
                                                <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600">
                                                    <i class="fa-solid fa-share-nodes text-xs"></i>
                                                </div>
                                                <label class="text-[11px] font-semibold text-slate-400">Social Media Bolo</label>
                                            </div>
                                            
                                            <div class="group relative">
                                                <div class="absolute left-6 top-1/2 -translate-y-1/2 text-emerald-400 group-focus-within:text-emerald-600 transition-colors">
                                                    <i class="fa-brands fa-instagram text-xl"></i>
                                                </div>
                                                <input type="text" name="footer_social_ig" value="{{ $settings['footer_social_ig'] ?? '' }}" 
                                                    class="w-full pl-16 pr-10 py-6 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 focus:bg-white transition-all outline-none" 
                                                    placeholder="Username Instagram">
                                            </div>

                                            <div class="group relative">
                                                <div class="absolute left-6 top-1/2 -translate-y-1/2 text-emerald-400 group-focus-within:text-emerald-600 transition-colors">
                                                    <i class="fa-brands fa-whatsapp text-xl"></i>
                                                </div>
                                                <input type="text" name="footer_social_wa" value="{{ $settings['footer_social_wa'] ?? '' }}" 
                                                    class="w-full pl-16 pr-10 py-6 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 focus:bg-white transition-all outline-none" 
                                                    placeholder="Contoh: 628123456789">
                                            </div>

                                            <div class="group relative">
                                                <div class="absolute left-6 top-1/2 -translate-y-1/2 text-blue-500 group-focus-within:text-blue-600 transition-colors">
                                                    <i class="fa-brands fa-facebook text-xl"></i>
                                                </div>
                                                <input type="text" name="footer_social_fb" value="{{ $settings['footer_social_fb'] ?? '' }}" 
                                                    class="w-full pl-16 pr-10 py-6 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 focus:bg-white transition-all outline-none" 
                                                    placeholder="Link Facebook Page">
                                            </div>
                                        </div>

                                        <div class="space-y-8">
                                            <div class="flex items-center gap-4 mb-2">
                                                <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600">
                                                    <i class="fa-solid fa-info-circle text-xs"></i>
                                                </div>
                                                <label class="text-[11px] font-semibold text-slate-400">Copyright & Info</label>
                                            </div>
                                            
                                            <div class="space-y-2">
                                                <label class="block text-[11px] font-medium text-slate-400 uppercase ml-4 tracking-widest">Teks Copyright</label>
                                                <input type="text" name="footer_copyright" value="{{ $settings['footer_copyright'] ?? '© 2026 Q-Store Mojokerto.' }}" 
                                                    class="w-full px-10 py-6 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 focus:bg-white transition-all outline-none"
                                                    placeholder="Contoh: &copy; 2026 Q-Store.">
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-[11px] font-medium text-slate-400 uppercase ml-4 tracking-widest">Alamat Fisik Toko</label>
                                                <textarea name="footer_address" rows="3" class="w-full px-10 py-6 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 focus:bg-white transition-all resize-none outline-none" placeholder="Alamat lengkap bolo...">{{ $settings['footer_address'] ?? 'Jl. Benteng Pancasila, Mojokerto, Jawa Timur' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="p-10 sm:p-16 pt-0 flex justify-end gap-4">
                                    <button type="button" onclick="confirmReset('footer')" class="px-8 py-7 bg-white text-rose-500 border-2 border-rose-100 rounded-2xl font-semibold uppercase text-[11px] tracking-[0.3em] hover:bg-rose-50 transition-all duration-300">
                                        RESET FOOTER
                                    </button>
                                    <button type="submit" class="group flex items-center gap-4 px-12 py-7 bg-[#0F172A] text-white rounded-2xl font-semibold uppercase text-[11px] tracking-[0.3em] shadow-lg hover:bg-black hover:scale-[1.05] active:scale-95 transition-all duration-500 border-b-4 border-black/30">
                                        <span>SIMPAN FOOTER BOLO</span>
                                        <i class="fa-solid fa-check-double text-emerald-400 group-hover:rotate-12 transition-transform"></i>
                                    </button>
                                </div>
                            </form>
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

    {{-- CROPPER MODAL (FIXED & MODERN) --}}
    <div x-data="{ 
            open: false, 
            type: '',
            init() {
                window.addEventListener('open-cms-cropper', (e) => {
                    this.type = e.detail.type;
                    this.open = true;
                });
                window.addEventListener('close-cms-cropper', () => {
                    this.open = false;
                });
            }
        }"
        x-init="init()"
        x-show="open"
        x-cloak
        @keydown.escape.window="open = false; closeCropper()"
        class="fixed inset-0 z-[200] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm p-4">
        
        <div class="bg-white rounded-2xl w-full max-w-4xl overflow-hidden shadow-lg relative animate-fade-in-up">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 tracking-tight uppercase">POTONG <span class="text-emerald-600">GAMBAR BOLO</span></h3>
                    <p class="text-xs font-bold text-gray-400 mt-1">Sesuaikan posisi gambar biar pas di mata ya!</p>
                </div>
                <button @click="open = false; closeCropper()" class="p-2.5 bg-white text-gray-400 rounded-xl hover:text-red-500 transition-all shadow-sm border border-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-8">
                <div class="h-auto min-h-[300px] max-h-[55vh] rounded-2xl bg-gray-50 border-2 border-dashed border-gray-200 relative overflow-hidden">
                    <img id="cropperImage" src="" class="max-w-full block">
                </div>
                <!-- ... existing buttons ... -->
            </div>

            <div class="p-8 bg-gray-50/50 border-t border-gray-50 flex gap-4">
                <button @click="open = false; closeCropper()" class="flex-1 py-4 bg-white text-gray-500 rounded-2xl font-semibold uppercase text-xs tracking-widest border border-gray-100 hover:bg-gray-100 transition-all">
                    BATAL
                </button>
                <button onclick="applyCrop()" class="flex-1 py-4 bg-emerald-600 text-white rounded-2xl font-semibold uppercase text-xs tracking-widest shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition-all">
                    POTONG BOLO
                </button>
            </div>
        </div>
    </div>

    {{-- MODALS --}}
    <div id="addCourierModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeModal('addCourierModal')"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-lg">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-slate-900 tracking-tight">TAMBAH KURIR LOKAL</h3>
                    <button onclick="closeModal('addCourierModal')" class="p-3 bg-slate-50 text-slate-400 rounded-2xl hover:text-rose-500 transition-all border border-slate-100">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>
                    <form action="{{ route('admin.cms.couriers.store') }}" method="POST" class="p-10 sm:p-14 space-y-10">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                            <div class="space-y-3 group">
                                <label class="block text-xs font-semibold text-slate-400 ml-4 group-focus-within:text-emerald-600 transition-colors">Nama Kurir</label>
                                <input type="text" name="name" required placeholder="Contoh: SADEWA" class="w-full px-8 py-5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all outline-none">
                            </div>
                            <div class="space-y-3 group">
                                <label class="block text-xs font-semibold text-slate-400 ml-4 group-focus-within:text-emerald-600 transition-colors">Nama Layanan</label>
                                <input type="text" name="service_name" required placeholder="Contoh: KILAT" class="w-full px-8 py-5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all outline-none">
                            </div>
                            <div class="space-y-3 group">
                                <label class="block text-xs font-semibold text-slate-400 ml-4 group-focus-within:text-emerald-600 transition-colors">Multiplier Biaya</label>
                                <input type="number" name="multiplier" step="0.1" value="1.0" required class="w-full px-8 py-5 bg-emerald-50/30 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all outline-none">
                                <p class="text-[11px] text-slate-400 mt-2 font-bold uppercase ml-4">Misal 1.2 = Biaya naik 20%</p>
                            </div>
                            <div class="space-y-3 group">
                                <label class="block text-xs font-semibold text-slate-400 ml-4 group-focus-within:text-emerald-600 transition-colors">Extra Admin (Rp)</label>
                                <input type="number" name="base_extra_cost" value="0" required class="w-full px-8 py-5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all outline-none">
                                <p class="text-[11px] text-slate-400 mt-2 font-bold uppercase ml-4">Flat tambahan ditiap order bolo</p>
                            </div>
                            <div class="space-y-3 group">
                                <label class="block text-xs font-semibold text-slate-400 ml-4 group-focus-within:text-emerald-600 transition-colors">Max Jarak (KM)</label>
                                <input type="number" name="max_distance" placeholder="Unlimited" class="w-full px-8 py-5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all outline-none">
                            </div>
                            <div class="space-y-3 group">
                                <label class="block text-xs font-semibold text-slate-400 ml-4 group-focus-within:text-emerald-600 transition-colors">Max Beban (KG)</label>
                                <input type="number" name="max_weight" placeholder="Unlimited" class="w-full px-8 py-5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all outline-none">
                            </div>
                            <div class="sm:col-span-2 space-y-3 group">
                                <label class="block text-xs font-semibold text-slate-400 ml-4 group-focus-within:text-emerald-600 transition-colors">Estimasi Waktu</label>
                                <input type="text" name="estimated_time" placeholder="2-4 JAM atau 1 HARI" required class="w-full px-8 py-5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all outline-none">
                            </div>
                            <div class="sm:col-span-2 space-y-3 group">
                                <label class="block text-xs font-semibold text-slate-400 ml-4 group-focus-within:text-emerald-600 transition-colors">Deskripsi Singkat</label>
                                <textarea name="description" rows="2" class="w-full px-8 py-6 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all outline-none resize-none" placeholder="Pesan sekarang, sampai sebentar lagi..."></textarea>
                            </div>
                        </div>
                        <div class="pt-6">
                            <button type="submit" class="w-full py-7 bg-[#0F172A] text-white rounded-2xl font-semibold uppercase text-[11px] tracking-[0.3em] hover:bg-black transition-all shadow-lg shadow-emerald-100 border-b-4 border-black/30">SIMPAN KURIR BOLO</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- EDIT COURIER MODAL (BOLO STYLE) --}}
        <div id="editCourierModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" onclick="closeModal('editCourierModal')"></div>
            <div class="relative min-h-screen flex items-center justify-center p-6">
                <div class="relative bg-white rounded-[3.5rem] w-full max-w-2xl overflow-hidden shadow-lg ring-1 ring-white/20">
                    <div class="p-10 sm:p-14 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                        <div class="flex items-center gap-5">
                            <div class="w-12 h-12 bg-emerald-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-100">
                                <i class="fa-solid fa-pen-nib text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-slate-900 tracking-tight uppercase">Update Kurir Bolo</h3>
                                <p class="text-xs font-medium text-slate-400 mt-1">Sesuaikan preferensi layanan pengiriman</p>
                            </div>
                        </div>
                        <button onclick="closeModal('editCourierModal')" class="p-3 bg-white text-slate-400 rounded-2xl hover:text-rose-500 transition-all border border-slate-100">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>
                    <form id="editCourierForm" action="" method="POST" class="p-10 sm:p-14 space-y-10">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                            <div class="space-y-3 group">
                                <label class="block text-xs font-semibold text-slate-400 ml-4 group-focus-within:text-emerald-600 transition-colors">Nama Kurir</label>
                                <input type="text" name="name" id="edit_name" required class="w-full px-8 py-5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all outline-none">
                            </div>
                            <div class="space-y-3 group">
                                <label class="block text-xs font-semibold text-slate-400 ml-4 group-focus-within:text-emerald-600 transition-colors">Nama Layanan</label>
                                <input type="text" name="service_name" id="edit_service_name" required class="w-full px-8 py-5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all outline-none">
                            </div>
                            <div class="space-y-3 group">
                                <label class="block text-xs font-semibold text-slate-400 ml-4 group-focus-within:text-emerald-600 transition-colors">Multiplier Biaya</label>
                                <input type="number" name="multiplier" id="edit_multiplier" step="0.1" required class="w-full px-8 py-5 bg-emerald-50/30 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all outline-none">
                            </div>
                            <div class="space-y-3 group">
                                <label class="block text-xs font-semibold text-slate-400 ml-4 group-focus-within:text-emerald-600 transition-colors">Extra Admin (Rp)</label>
                                <input type="number" name="base_extra_cost" id="edit_base_extra_cost" required class="w-full px-8 py-5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all outline-none">
                            </div>
                            <div class="space-y-3 group">
                                <label class="block text-xs font-semibold text-slate-400 ml-4 group-focus-within:text-emerald-600 transition-colors">Max Jarak (KM)</label>
                                <input type="number" name="max_distance" id="edit_max_distance" class="w-full px-8 py-5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all outline-none">
                            </div>
                            <div class="space-y-3 group">
                                <label class="block text-xs font-semibold text-slate-400 ml-4 group-focus-within:text-emerald-600 transition-colors">Max Beban (KG)</label>
                                <input type="number" name="max_weight" id="edit_max_weight" class="w-full px-8 py-5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all outline-none">
                            </div>
                            <div class="sm:col-span-2 space-y-3 group">
                                <label class="block text-xs font-semibold text-slate-400 ml-4 group-focus-within:text-emerald-600 transition-colors">Estimasi Waktu</label>
                                <input type="text" name="estimated_time" id="edit_estimated_time" required class="w-full px-8 py-5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all outline-none">
                            </div>
                            <div class="sm:col-span-2 space-y-3 group">
                                <label class="block text-xs font-semibold text-slate-400 ml-4 group-focus-within:text-emerald-600 transition-colors">Deskripsi Singkat</label>
                                <textarea name="description" id="edit_description" rows="2" class="w-full px-8 py-6 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-8 focus:ring-emerald-600/5 focus:border-emerald-600 transition-all outline-none resize-none"></textarea>
                            </div>
                        </div>
                        <div class="pt-6">
                            <button type="submit" class="w-full py-7 bg-emerald-600 text-white rounded-2xl font-semibold uppercase text-[11px] tracking-[0.3em] hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100 border-b-4 border-emerald-900/30">UPDATE KURIR BOLO</button>
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
                    
                    // Dispatch Alpine event
                    window.dispatchEvent(new CustomEvent('open-cms-cropper', {
                        detail: { type: type }
                    }));
                    
                    if (cropper) {
                        cropper.destroy();
                    }
                    
                    // Increase timeout slightly and ensure display is ready
                    setTimeout(() => {
                        cropper = new Cropper(image, {
                            aspectRatio: aspectRatio,
                            viewMode: 1,
                            dragMode: 'move',
                            autoCropArea: 0.8,
                            background: true,
                            responsive: true,
                            restore: true,
                            checkOrientation: true,
                            modal: true,
                            guides: true,
                            center: true,
                            highlight: true,
                            cropBoxMovable: true,
                            cropBoxResizable: true,
                            toggleDragModeOnDblclick: true,
                            movable: true,
                            zoomable: true,
                            rotatable: true,
                            scalable: true,
                        });
                    }, 500); 
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function closeCropper() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            if (currentInput) currentInput.value = '';
        }

        function applyCrop() {
            if (!cropper) return;

            let config;
            if (currentType === 'site_logo') {
                config = { width: 512, height: 512 };
            } else if (currentType === 'hero_banner' || currentType === 'home_slider') {
                config = { width: 1920, height: 823 }; // 21:9 approx
            } else if (currentType === 'about_image') {
                config = { width: 1280, height: 720 }; // 16:9
            } else {
                config = { width: 1080, height: 1080 };
            }

            const canvas = cropper.getCroppedCanvas(config);
            const base64Image = canvas.toDataURL('image/jpeg', 0.9);
            
            // Set hidden input value
            const hiddenInput = document.getElementById('cropped_' + currentType);
            if (hiddenInput) {
                hiddenInput.value = base64Image;
            }

            // Update preview if exists
            const preview = document.getElementById('preview_' + currentType);
            const container = document.getElementById('preview_' + currentType + '_container');
            
            if (preview) {
                preview.src = base64Image;
                preview.classList.remove('grayscale', 'opacity-20');
            } else if (container) {
                container.innerHTML = `<img src="${base64Image}" class="w-full h-full object-cover">`;
            }

            // If it's home_slider, auto submit the form
            if (currentType === 'home_slider') {
                document.getElementById('form-sliders').requestSubmit();
            }

            // Auto submit branding logo
            if (currentType === 'site_logo') {
                const logoBtn = document.getElementById('save_logo_btn');
                if (logoBtn) logoBtn.click();
            }

            // Close modal successfully in Alpine v2 or v3
            window.dispatchEvent(new CustomEvent('close-cms-cropper'));
            
            closeCropper();

            Swal.fire({
                icon: 'success',
                title: 'Mantap Bolo!',
                text: 'Gambar berhasil dipotong dan diterapkan.',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
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
            document.getElementById('edit_max_distance').value = courier.max_distance;
            document.getElementById('edit_max_weight').value = courier.max_weight;
            
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
                    confirmButton: 'rounded-xl font-bold uppercase text-xs tracking-widest px-6 py-3',
                    cancelButton: 'rounded-xl font-bold uppercase text-xs tracking-widest px-6 py-3'
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
                    confirmButton: 'rounded-xl font-bold uppercase text-xs tracking-widest px-6 py-3',
                    cancelButton: 'rounded-xl font-bold uppercase text-xs tracking-widest px-6 py-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteCourierForm');
                    form.action = `/admin/cms/couriers/${id}`;
                    form.submit();
                }
            });
        }

        function confirmDeleteSlider(form) {
            Swal.fire({
                title: 'Hapus Slide?',
                text: "Banner ini akan dihapus dari halaman utama bolo!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-[1.5rem]',
                    confirmButton: 'rounded-xl font-bold uppercase text-xs tracking-widest px-6 py-3',
                    cancelButton: 'rounded-xl font-bold uppercase text-xs tracking-widest px-6 py-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.requestSubmit();
                }
            });
        }

        function confirmReset(type) {
            Swal.fire({
                title: 'Reset ke Default?',
                text: `Semua konten pada bagian ${type.toUpperCase()} akan dikembalikan ke pengaturan awal pabrik bolo!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#000000',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Reset Bolo!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-[1.5rem]',
                    confirmButton: 'rounded-xl font-bold uppercase text-xs tracking-widest px-6 py-3',
                    cancelButton: 'rounded-xl font-bold uppercase text-xs tracking-widest px-6 py-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('admin.cms.reset') }}";
                    
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = "{{ csrf_token() }}";
                    
                    const typeInput = document.createElement('input');
                    typeInput.type = 'hidden';
                    typeInput.name = 'type';
                    typeInput.value = type;
                    
                    form.appendChild(csrf);
                    form.appendChild(typeInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // AJAX Form Handling to prevent full page reload
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.classList.contains('no-ajax')) return; 
            
            // Check if it's a CMS form (has cms-form class or is inside any CMS panel)
            const isCmsForm = form.classList.contains('cms-form') || 
                             form.closest('[class*="panel-"]');

            if (isCmsForm) { 
                e.preventDefault();
                
                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]') || form.querySelector('button:not([type])');
                const originalBtnText = submitBtn ? submitBtn.innerHTML : '';
                
                // Show floating loading if not a small button
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';
                }
                
                fetch(form.action, {
                    method: form.method || 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) throw response;
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Beres Bolo! ✅',
                            text: data.message,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            customClass: { popup: 'rounded-2xl' }
                        });
                        
                        // Handle Logo/Image Updates
                        if (data.path && data.field) {
                            const preview = document.getElementById('preview_' + data.field);
                            if (preview) {
                                preview.src = data.path;
                                preview.classList.remove('opacity-20', 'grayscale');
                            }
                        } else if (data.path && form.getAttribute('action').includes('logo')) {
                            const preview = document.getElementById('preview_site_logo');
                            if (preview) preview.src = data.path;
                        }

                        // Special: ONLY reload for things that change the structure significantly
                        // such as deleting sliders or adding/removing couriers
                        const needsReload = ['delete-slider', 'couriers'].some(path => form.getAttribute('action').includes(path));
                        
                        if (needsReload) {
                            setTimeout(() => location.reload(), 1000);
                        }
                    } else {
                        throw data;
                    }
                })
                .catch(async (err) => {
                    let message = 'Gagal menyimpan pengaturan bolo.';
                    try {
                        const data = await err.json();
                        message = data.message || Object.values(data.errors).flat().join('<br>');
                    } catch(e) {}
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Waduh Error!',
                        html: message,
                        confirmButtonColor: '#059669',
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: 'rounded-xl font-bold uppercase text-xs tracking-widest px-8 py-4'
                        }
                    });
                })
                .finally(() => {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                    }
                });
            }
        });
    </script>
</x-admin-layout>

