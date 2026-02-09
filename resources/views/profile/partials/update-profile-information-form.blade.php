<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-8">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="name" :value="__('Name')" class="ml-1 text-xs font-medium text-gray-500 mb-1.5" />
                <x-text-input id="name" name="name" type="text" class="w-full bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:bg-white rounded-xl px-4 py-3 text-sm transition-all outline-none" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            @hasanyrole('admin|seller')
            <div>
                <x-input-label for="nama_toko" :value="__('Nama Toko')" class="ml-1 text-xs font-medium text-gray-500 mb-1.5" />
                <x-text-input id="nama_toko" name="nama_toko" type="text" class="w-full bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:bg-white rounded-xl px-4 py-3 text-sm transition-all outline-none" :value="old('nama_toko', $user->nama_toko)" placeholder="Contoh: Mstore Dlanggu" />
                <x-input-error class="mt-2" :messages="$errors->get('nama_toko')" />
            </div>
            @endhasanyrole

            <div>
                <x-input-label for="phone" :value="__('Phone Number (WhatsApp)')" class="ml-1 text-xs font-medium text-gray-500 mb-1.5" />
                <x-text-input id="phone" name="phone" type="text" class="w-full bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:bg-white rounded-xl px-4 py-3 text-sm transition-all outline-none" :value="old('phone', $user->phone)" placeholder="08xxxxxxx" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" class="ml-1 text-xs font-medium text-gray-500 mb-1.5" />
                <x-text-input id="email" name="email" type="email" class="w-full bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:bg-white rounded-xl px-4 py-3 text-sm transition-all outline-none" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-xs text-gray-800 font-bold">
                            {{ __('Your email address is unverified.') }}

                            <buttwon form="send-verification" class="text-emerald-600 hover:text-emerald-800 underline">
                                {{ __('Click here to re-send.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-xs font-medium text-green-600">
                                {{ __('A new verification link has been sent.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between mb-2">
                <x-input-label for="alamat" :value="__('Detail Alamat Lengkap')" class="ml-1 text-xs font-medium text-gray-500" />
                <span class="text-[11px] text-emerald-600">Saran: Tambahkan patokan</span>
            </div>
            <textarea id="alamat" name="alamat" 
                class="w-full bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:bg-white rounded-xl px-5 py-3.5 text-sm transition-all outline-none leading-relaxed" 
                rows="3" placeholder="Contoh: Jl. Raya No. 123, SMKN 1 Dlanggu (Rumah pagar hitam cat biru)...">{{ old('alamat', $user->alamat) }}</textarea>
            <div id="osm-suggestion"></div>
            <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
        </div>

        {{-- BOLO THEME PREFERENCE — INSTANT SWITCH --}}
        <div class="pt-10 border-t border-gray-100 mt-10" x-data="themeSwitch()">
            <div class="mb-6">
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">Preferensi Warna</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Pilih warna aksen agar dashboard makin nyaman di mata.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                @php
                    $themes = [
                        'emerald' => ['name' => 'Emerald', 'desc' => 'Segar & natural', 'hex' => '#10b981', 'hex2' => '#059669', 'hex_light' => '#d1fae5', 'raw' => '#10b981'],
                        'rose'    => ['name' => 'Rose',    'desc' => 'Energik & berani', 'hex' => '#f43f5e', 'hex2' => '#e11d48', 'hex_light' => '#ffe4e6', 'raw' => '#f43f5e'],
                        'amber'   => ['name' => 'Amber',   'desc' => 'Hangat & ramah', 'hex' => '#f59e0b', 'hex2' => '#d97706', 'hex_light' => '#fef3c7', 'raw' => '#f59e0b'],
                        'slate'   => ['name' => 'Slate',   'desc' => 'Kalem & profesional', 'hex' => '#475569', 'hex2' => '#334155', 'hex_light' => '#f1f5f9', 'raw' => '#334155'],
                        'indigo'  => ['name' => 'Indigo',  'desc' => 'Modern & premium', 'hex' => '#6366f1', 'hex2' => '#4f46e5', 'hex_light' => '#e0e7ff', 'raw' => '#4f46e5'],
                    ];
                    $currentTheme = old('theme_color', $user->theme_color ?? 'indigo');
                    $popular = $popularTheme ?? 'indigo';
                @endphp

                @foreach($themes as $key => $theme)
                    <label class="relative cursor-pointer group" @click="switchTheme('{{ $key }}')" title="{{ $theme['desc'] }}">
                        <input type="radio" name="theme_color" value="{{ $key }}" class="peer sr-only" {{ $currentTheme == $key ? 'checked' : '' }} :checked="current === '{{ $key }}'">
                        
                        <div class="p-3.5 border-2 border-transparent rounded-2xl flex flex-col items-center gap-2.5 transition-all duration-300 relative"
                            :class="current === '{{ $key }}' ? 'bg-white shadow-xl' : 'bg-gray-50/80 hover:bg-white'"
                            :style="current === '{{ $key }}' ? 'border-color: {{ $theme['raw'] }}; box-shadow: 0 10px 30px -5px {{ $theme['hex_light'] }}' : ''"
                            onmouseenter="if(!this.classList.contains('shadow-xl')) this.style.boxShadow='0 8px 24px -4px {{ $theme['hex_light'] }}'"
                            onmouseleave="if(!this.classList.contains('shadow-xl')) this.style.boxShadow='none'">
                            
                            {{-- Popular badge --}}
                            @if($key === $popular)
                                <div class="absolute -top-2 left-1/2 -translate-x-1/2 px-2 py-0.5 bg-amber-400 text-white text-[9px] font-bold rounded-full shadow-sm whitespace-nowrap z-10">
                                    <span class="flex items-center gap-0.5">
                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z"/></svg>
                                        Popular
                                    </span>
                                </div>
                            @endif

                            {{-- Mini Dashboard Preview --}}
                            <div class="w-full aspect-[4/3] rounded-lg overflow-hidden border border-gray-100 bg-white mb-0.5 group-hover:border-gray-200 transition-all relative">
                                {{-- Tiny sidebar --}}
                                <div class="absolute left-0 top-0 bottom-0 w-[18%] flex flex-col gap-[3px] p-[3px]" style="background: {{ $theme['hex_light'] }};">
                                    <div class="w-full aspect-square rounded-sm" style="background: {{ $theme['hex'] }};"></div>
                                    <div class="w-full h-[3px] rounded-full opacity-40" style="background: {{ $theme['hex'] }};"></div>
                                    <div class="w-full h-[3px] rounded-full opacity-25" style="background: {{ $theme['hex'] }};"></div>
                                    <div class="w-full h-[3px] rounded-full opacity-25" style="background: {{ $theme['hex'] }};"></div>
                                </div>
                                {{-- Tiny content area --}}
                                <div class="absolute left-[22%] top-[6px] right-[5px] flex flex-col gap-[4px]">
                                    {{-- Header bar --}}
                                    <div class="w-full h-[5px] rounded-full" style="background: {{ $theme['hex_light'] }};"></div>
                                    {{-- Stat cards row --}}
                                    <div class="flex gap-[3px]">
                                        <div class="flex-1 h-[12px] rounded-sm" style="background: {{ $theme['hex_light'] }};"></div>
                                        <div class="flex-1 h-[12px] rounded-sm bg-gray-100"></div>
                                        <div class="flex-1 h-[12px] rounded-sm bg-gray-50"></div>
                                    </div>
                                    {{-- Content block --}}
                                    <div class="w-full h-[16px] rounded-sm bg-gray-50 border border-gray-100 flex items-end p-[2px] gap-[2px]">
                                        <div class="flex-1 rounded-[1px]" style="background: {{ $theme['hex'] }}; height: 60%; opacity: 0.6;"></div>
                                        <div class="flex-1 rounded-[1px]" style="background: {{ $theme['hex'] }}; height: 80%; opacity: 0.8;"></div>
                                        <div class="flex-1 rounded-[1px]" style="background: {{ $theme['hex'] }}; height: 45%; opacity: 0.5;"></div>
                                        <div class="flex-1 rounded-[1px]" style="background: {{ $theme['hex2'] }}; height: 100%;"></div>
                                        <div class="flex-1 rounded-[1px]" style="background: {{ $theme['hex'] }}; height: 70%; opacity: 0.7;"></div>
                                    </div>
                                </div>
                                {{-- Accent dot --}}
                                <div class="absolute bottom-1 right-1.5 w-[6px] h-[6px] rounded-full" style="background: {{ $theme['hex'] }};"></div>
                            </div>

                            {{-- Label & description --}}
                            <div class="text-center">
                                <span class="text-[11px] font-bold block transition-all duration-300"
                                    :class="current === '{{ $key }}' ? '' : 'text-gray-600 group-hover:text-gray-900'"
                                    :style="current === '{{ $key }}' ? 'color: {{ $theme['raw'] }}' : ''">{{ $theme['name'] }}</span>
                                <span class="text-[9px] text-gray-400 group-hover:text-gray-500 transition-colors">{{ $theme['desc'] }}</span>
                            </div>
                            
                            {{-- Checkmark --}}
                            <div class="absolute top-2 right-2 w-5 h-5 text-white rounded-full items-center justify-center transition-all duration-300"
                                style="background: linear-gradient(135deg, {{ $theme['hex'] }}, {{ $theme['hex2'] }});"
                                :class="current === '{{ $key }}' ? 'flex opacity-100 scale-100' : 'hidden opacity-0 scale-75'">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </div>
                        
                        {{-- Loading spinner --}}
                        <div class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-2xl backdrop-blur-sm z-20" x-show="loading === '{{ $key }}'" x-transition>
                            <svg class="w-5 h-5 animate-spin" style="color: {{ $theme['hex'] }}" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        </div>
                    </label>
                @endforeach

                {{-- Custom Color Card --}}
                <label class="relative cursor-pointer group" @click.prevent="showCustom = !showCustom">
                    <div class="p-3.5 border-2 border-dashed rounded-2xl flex flex-col items-center gap-2.5 transition-all duration-300 h-full justify-center"
                        :class="current.startsWith('#') ? 'border-gray-400 bg-white shadow-lg' : 'border-gray-200 bg-gray-50/80 hover:bg-white hover:border-gray-300'">
                        
                        {{-- Color wheel icon or current custom swatch --}}
                        <div class="w-full aspect-[4/3] rounded-lg overflow-hidden border border-dashed border-gray-200 bg-gradient-to-br from-rose-100 via-amber-100 to-indigo-100 flex items-center justify-center group-hover:border-gray-300 transition-all mb-0.5">
                            <template x-if="current.startsWith('#')">
                                <div class="w-8 h-8 rounded-lg shadow-sm border-2 border-white" :style="'background:' + current"></div>
                            </template>
                            <template x-if="!current.startsWith('#')">
                                <svg class="w-6 h-6 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                            </template>
                        </div>

                        <div class="text-center">
                            <span class="text-[11px] font-bold block text-gray-600 group-hover:text-gray-900 transition-colors">Custom</span>
                            <span class="text-[9px] text-gray-400">Pilih sendiri</span>
                        </div>

                        {{-- Checkmark for custom --}}
                        <div class="absolute top-2 right-2 w-5 h-5 text-white rounded-full items-center justify-center transition-all duration-300"
                            :style="current.startsWith('#') ? 'background:' + current : ''"
                            :class="current.startsWith('#') ? 'flex opacity-100 scale-100' : 'hidden opacity-0 scale-75'">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                </label>
            </div>

            {{-- Custom Color Panel --}}
            <div x-show="showCustom" x-transition.origin.top class="mt-4 p-5 bg-gray-50 rounded-2xl border border-gray-200">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3 flex-1">
                        <input type="color" x-model="customHex" 
                            class="w-10 h-10 rounded-lg border-2 border-white shadow-sm cursor-pointer [&::-webkit-color-swatch-wrapper]:p-0 [&::-webkit-color-swatch]:rounded-lg [&::-webkit-color-swatch]:border-none">
                        <div class="flex-1">
                            <label class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1 block">Hex Color</label>
                            <input type="text" x-model="customHex" maxlength="7" placeholder="#ff6b2b"
                                class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono focus:border-gray-400 focus:outline-none transition-all"
                                @input="customHex = customHex.startsWith('#') ? customHex : '#' + customHex">
                        </div>
                    </div>
                    <button type="button" @click="applyCustom()" 
                        class="px-5 py-2.5 bg-gray-900 text-white text-xs font-bold rounded-xl hover:bg-gray-800 active:scale-95 transition-all shadow-sm"
                        :class="customLoading ? 'opacity-50 pointer-events-none' : ''">
                        <span x-show="!customLoading">Terapkan</span>
                        <svg x-show="customLoading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    </button>
                </div>
                {{-- Preview swatches --}}
                <div class="flex gap-1.5 mt-3">
                    <template x-for="shade in [0.95, 0.85, 0.7, 0.5, 0, -0.15, -0.3, -0.45, -0.6]" :key="shade">
                        <div class="flex-1 h-6 rounded" :style="'background:' + mixColor(customHex, shade)"></div>
                    </template>
                </div>
                <p class="text-[10px] text-gray-400 mt-2 text-center">Preview palet warna yang akan diterapkan</p>
            </div>
            
            {{-- Status indicator --}}
            <div class="mt-4 h-5 flex items-center justify-center">
                <p x-show="saved" x-transition.opacity.duration.500ms class="text-xs font-medium text-green-600 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Warna berhasil diganti bolo!
                </p>
                <p x-show="error" x-transition.opacity.duration.500ms class="text-xs font-medium text-rose-500">
                    Gagal menyimpan, coba lagi bolo!
                </p>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('theme_color')" />
        </div>

        <script>
        function themeSwitch() {
            const colorMap = {
                emerald: { '50':'#ecfdf5','100':'#d1fae5','200':'#a7f3d0','400':'#34d399','500':'#10b981','600':'#059669','700':'#047857','900':'#064e3b' },
                rose:    { '50':'#fff1f2','100':'#ffe4e6','200':'#fecdd3','400':'#fb7185','500':'#f43f5e','600':'#e11d48','700':'#be123c','900':'#881337' },
                amber:   { '50':'#fffbeb','100':'#fef3c7','200':'#fde68a','400':'#fbbf24','500':'#f59e0b','600':'#d97706','700':'#b45309','900':'#78350f' },
                slate:   { '50':'#f8fafc','100':'#f1f5f9','200':'#e2e8f0','400':'#94a3b8','500':'#64748b','600':'#475569','700':'#334155','900':'#0f172a' },
                indigo:  { '50':'#eef2ff','100':'#e0e7ff','200':'#c7d2fe','400':'#818cf8','500':'#6366f1','600':'#4f46e5','700':'#4338ca','900':'#312e81' },
            };

            function hexToRgb(hex) {
                const r = parseInt(hex.slice(1,3), 16);
                const g = parseInt(hex.slice(3,5), 16);
                const b = parseInt(hex.slice(5,7), 16);
                return { r, g, b };
            }

            function rgbToHex(r, g, b) {
                return '#' + [r,g,b].map(x => Math.max(0, Math.min(255, Math.round(x))).toString(16).padStart(2,'0')).join('');
            }

            function mixColor(hex, weight) {
                if (!hex || !hex.match(/^#[0-9a-fA-F]{6}$/)) return '#cccccc';
                const { r, g, b } = hexToRgb(hex);
                if (weight > 0) {
                    return rgbToHex(r + (255-r)*weight, g + (255-g)*weight, b + (255-b)*weight);
                } else {
                    const w = Math.abs(weight);
                    return rgbToHex(r*(1-w), g*(1-w), b*(1-w));
                }
            }

            function generatePalette(hex) {
                return {
                    '50':  mixColor(hex, 0.95),
                    '100': mixColor(hex, 0.85),
                    '200': mixColor(hex, 0.7),
                    '400': mixColor(hex, 0.3),
                    '500': hex,
                    '600': mixColor(hex, -0.15),
                    '700': mixColor(hex, -0.3),
                    '900': mixColor(hex, -0.6),
                };
            }

            return {
                current: '{{ $currentTheme }}',
                loading: null,
                saved: false,
                error: false,
                showCustom: {{ str_starts_with($currentTheme, '#') ? 'true' : 'false' }},
                customHex: '{{ str_starts_with($currentTheme, '#') ? $currentTheme : '#6366f1' }}',
                customLoading: false,

                mixColor,

                applyColors(colors) {
                    const root = document.documentElement;
                    Object.entries(colors).forEach(([shade, hex]) => {
                        root.style.setProperty('--brand-' + shade, hex);
                    });
                },

                switchTheme(theme) {
                    if (this.current === theme || this.loading) return;
                    this.loading = theme;
                    this.saved = false;
                    this.error = false;
                    this.showCustom = false;

                    const colors = colorMap[theme];
                    if (colors) this.applyColors(colors);

                    this.persistTheme(theme, () => {
                        this.current = theme;
                        this.loading = null;
                    }, () => {
                        const old = colorMap[this.current];
                        if (old) this.applyColors(old);
                        this.loading = null;
                    });
                },

                applyCustom() {
                    const hex = this.customHex;
                    if (!hex.match(/^#[0-9a-fA-F]{6}$/)) {
                        this.error = true;
                        setTimeout(() => this.error = false, 3000);
                        return;
                    }
                    this.customLoading = true;
                    this.saved = false;
                    this.error = false;

                    const palette = generatePalette(hex);
                    this.applyColors(palette);

                    this.persistTheme(hex, () => {
                        this.current = hex;
                        this.customLoading = false;
                    }, () => {
                        const old = colorMap[this.current] || generatePalette(this.current);
                        this.applyColors(old);
                        this.customLoading = false;
                    });
                },

                persistTheme(theme, onSuccess, onFail) {
                    fetch('{{ route("profile.theme.update") }}', {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ theme_color: theme })
                    })
                    .then(r => { if (!r.ok) throw new Error(); return r.json(); })
                    .then(() => {
                        onSuccess();
                        this.saved = true;
                        setTimeout(() => this.saved = false, 2500);
                    })
                    .catch(() => {
                        onFail();
                        this.error = true;
                        setTimeout(() => this.error = false, 3000);
                    });
                }
            };
        }
        </script>

        {{-- MAP LOCATION SELLER --}}
        <div class="space-y-6 pt-10 border-t border-gray-100 mt-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">Lokasi Toko / Pengiriman</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Tentukan lokasi pengiriman agar ongkir pembeli akurat.</p>
                    </div>
                </div>
                {{-- Helper Buttons --}}
                <div class="flex gap-2">
                    <button type="button" onclick="window.open('https://www.google.com/maps', '_blank')" 
                        class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all cursor-pointer shadow-sm border border-rose-100 flex items-center gap-2" title="Buka Google Maps untuk Akurasi">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z"/></svg>
                        <span class="text-[11px] font-medium hidden sm:inline">Cek G-Maps</span>
                    </button>
                    <button type="button" onclick="navigator.geolocation.getCurrentPosition(p => window.selectSellerLocation(p.coords.latitude, p.coords.longitude, 'Lokasimu Saat Ini'))" 
                        class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all cursor-pointer shadow-sm border border-emerald-100" title="Gunakan Lokasi Saat Ini">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </button>
                </div>
            </div>

            <div class="bg-emerald-50/50 rounded-2xl p-4 border border-emerald-100/50 flex items-start gap-4">
                <div class="w-8 h-8 bg-emerald-600 rounded-xl flex items-center justify-center text-white shrink-0 shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-xs font-semibold text-emerald-900 mb-1">Tips Pinpoint Akurat:</h4>
                    <ul class="text-[11px] text-emerald-700 leading-relaxed list-disc ml-3">
                        <li>Gunakan tombol "Lokasi Saat Ini" jika sedang di lokasi.</li>
                        <li>Jika meleset, buka Google Maps, cari rumahmu, copy koordinatnya, lalu geser marker map di bawah secara manual.</li>
                        <li>Pastikan titik marker berhenti tepat di atap rumahmu.</li>
                    </ul>
                </div>
            </div>

            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400 group-focus-within:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" id="seller-map-search" 
                    class="w-full bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:bg-white rounded-xl pl-11 pr-4 py-3 text-sm transition-all outline-none" 
                    placeholder="Cari lokasi toko kamu (Nama Jalan/Gedung)...">
                
                <div id="seller-search-results" class="absolute z-[2000] w-full mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 hidden overflow-hidden"></div>
            </div>
            
            <div id="seller-map" style="height: 300px;" class="rounded-2xl border border-gray-200 overflow-hidden"></div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1 ml-1">Latitude</label>
                    <input id="latitude" name="latitude" type="text" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs text-gray-500 cursor-not-allowed" value="{{ old('latitude', $user->latitude) }}" readonly>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1 ml-1">Longitude</label>
                    <input id="longitude" name="longitude" type="text" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs text-gray-500 cursor-not-allowed" value="{{ old('longitude', $user->longitude) }}" readonly>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

