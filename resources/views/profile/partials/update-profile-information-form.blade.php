<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-8">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="name" :value="__('Name')" class="ml-1 text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2" />
                <x-text-input id="name" name="name" type="text" class="w-full bg-gray-50 border-2 border-gray-50 focus:border-indigo-500 focus:bg-white rounded-2xl px-5 py-3.5 text-sm font-bold transition-all outline-none" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="phone" :value="__('Phone Number (WhatsApp)')" class="ml-1 text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2" />
                <x-text-input id="phone" name="phone" type="text" class="w-full bg-gray-50 border-2 border-gray-50 focus:border-indigo-500 focus:bg-white rounded-2xl px-5 py-3.5 text-sm font-bold transition-all outline-none" :value="old('phone', $user->phone)" placeholder="08xxxxxxx" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" class="ml-1 text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2" />
                <x-text-input id="email" name="email" type="email" class="w-full bg-gray-50 border-2 border-gray-50 focus:border-indigo-500 focus:bg-white rounded-2xl px-5 py-3.5 text-sm font-bold transition-all outline-none" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-xs text-gray-800 font-bold">
                            {{ __('Your email address is unverified.') }}

                            <buttwon form="send-verification" class="text-indigo-600 hover:text-indigo-800 underline">
                                {{ __('Click here to re-send.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-black text-[10px] text-green-600 uppercase tracking-wider">
                                {{ __('A new verification link has been sent.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div>
            <x-input-label for="alamat" :value="__('Detail Alamat Lengkap')" class="ml-1 text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2" />
            <textarea id="alamat" name="alamat" 
                class="w-full bg-gray-50 border-2 border-gray-50 focus:border-indigo-500 focus:bg-white rounded-[2rem] px-6 py-4 text-xs font-bold transition-all outline-none leading-relaxed" 
                rows="4" placeholder="Contoh: Jl. Raya No. 123, SMKN 1 Dlanggu...">{{ old('alamat', $user->alamat) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
        </div>

        {{-- MAP LOCATION SELLER --}}
        <div class="space-y-6 pt-10 border-t border-gray-100 mt-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black uppercase tracking-widest text-gray-900">Lokasi Toko / Pengiriman</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Tentukan lokasi pengiriman kamu agar ongkir pembeli akurat bolo!</p>
                    </div>
                </div>
                {{-- Locate Me Button --}}
                <button type="button" onclick="navigator.geolocation.getCurrentPosition(p => window.selectSellerLocation(p.coords.latitude, p.coords.longitude, 'Lokasimu Saat Ini'))" 
                    class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all cursor-pointer shadow-sm border border-indigo-100" title="Gunakan Lokasi Saat Ini">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </button>
            </div>

            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" id="seller-map-search" 
                    class="w-full bg-gray-50 border-2 border-gray-50 focus:border-indigo-500 focus:bg-white rounded-xl pl-11 pr-4 py-3 text-xs transition-all outline-none font-bold" 
                    placeholder="Cari lokasi toko kamu (Nama Jalan/Gedung)...">
                
                <div id="seller-search-results" class="absolute z-[2000] w-full mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 hidden overflow-hidden"></div>
            </div>
            
            <div id="seller-map" style="height: 300px;" class="rounded-[2rem] border-2 border-gray-50 shadow-inner overflow-hidden"></div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Latitude</label>
                    <input id="latitude" name="latitude" type="text" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-black text-gray-500 cursor-not-allowed" value="{{ old('latitude', $user->latitude) }}" readonly>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Longitude</label>
                    <input id="longitude" name="longitude" type="text" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-xs font-black text-gray-500 cursor-not-allowed" value="{{ old('longitude', $user->longitude) }}" readonly>
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
