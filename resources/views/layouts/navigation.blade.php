<nav x-data="{
    open: false,
    kategoriOpen: false,
    cartCount: 0,
    updateCartCount() {
        try {
            const cart = JSON.parse(localStorage.getItem('minie_cart') || '[]');
            this.cartCount = cart.length;
        } catch (e) { this.cartCount = 0; }
    },
    init() {
        this.updateCartCount();
        window.addEventListener('cart-updated', () => this.updateCartCount());
    }
}"
    class="bg-white border-b border-gray-100 sticky top-0 z-50 transition-all duration-300">

    {{-- 1. TOP BAR (PROMO/APP LINK) --}}
    <div class="bg-gray-50 border-b border-gray-100 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center h-8 text-[11px] font-medium text-gray-500">
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-[#00AA5B] flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Gratis Ongkir + Banyak Promo
                </a>
            </div>
            <div class="flex items-center gap-6">
                <a href="#" class="hover:text-[#00AA5B]">Tentang MiniE</a>
                <a href="#" class="hover:text-[#00AA5B]">Mulai Berjualan</a>
                <a href="#" class="hover:text-[#00AA5B]">Promo</a>
                <a href="#" class="hover:text-[#00AA5B]">MiniE Care</a>
            </div>
        </div>
    </div>

    {{-- 2. MAIN NAV --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 md:h-20 gap-4">
            {{-- LOGO --}}
            <div class="shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="group flex items-center gap-2">
                    <span class="font-black text-2xl tracking-tighter text-[#00AA5B]">MiniQ</span>
                    <span class="font-black text-2xl tracking-tighter text-gray-900">Store</span>
                </a>
            </div>

            {{-- KATEGORI --}}
            <div class="hidden lg:block relative" @click.away="kategoriOpen = false">
                <button @click="kategoriOpen = !kategoriOpen" class="text-xs font-semibold text-gray-600 hover:text-[#00AA5B] px-2 py-1">
                    Kategori
                </button>
                <div x-show="kategoriOpen" x-cloak class="absolute top-full left-0 mt-2 w-56 bg-white border border-gray-100 shadow-xl rounded-2xl py-2 z-[60]">
                    <template x-for="cat in ['Elektronik', 'Fashion', 'Kesehatan', 'Hobi']" :key="cat">
                        <button @click="$store.global.setCategory(cat.toLowerCase()); kategoriOpen = false" 
                            class="w-full text-left block px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-50 hover:text-[#00AA5B]">
                            <span x-text="cat"></span>
                        </button>
                    </template>
                </div>
            </div>

            {{-- SEARCH BAR --}}
            <div class="flex-grow max-w-3xl relative" 
                x-data="{ 
                    searchFocused: false, 
                    results: [],
                    loading: false,
                    async handleSearch(q) {
                        if (q.length < 2) { this.results = []; return; }
                        this.loading = true;
                        try {
                            const resp = await fetch(`/search?q=${q}`);
                            this.results = await resp.json();
                        } catch (e) { console.error(e); }
                        this.loading = false;
                    }
                }">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400 group-focus-within:text-[#00AA5B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" 
                        x-model="$store.global.search"
                        @input.debounce.300ms="handleSearch($event.target.value)"
                        @focus="searchFocused = true"
                        @click.away="searchFocused = false"
                        placeholder="Cari di MiniQ Store"
                        class="w-full bg-white border border-gray-200 rounded-lg pl-10 pr-4 py-2.5 text-xs focus:ring-1 focus:ring-[#00AA5B] focus:border-[#00AA5B] transition-all outline-none">
                </div>

                {{-- Search Suggestions Dropdown --}}
                <div x-show="searchFocused && ($store.global.search.length > 0 || results.length > 0)" x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-100 shadow-2xl rounded-xl overflow-hidden z-[70]">
                    
                    {{-- Popular/Default State --}}
                    <template x-if="$store.global.search.length === 0">
                        <div class="p-4">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Pencarian Populer</h4>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="tag in ['Laptop Gaming', 'Sepatu Pria', 'TWS', 'Meja Kerja']">
                                    <button @click="$store.global.setSearch(tag); handleSearch(tag)" 
                                        class="px-3 py-1.5 bg-gray-50 border border-gray-100 rounded-lg text-[10px] font-bold text-gray-600 hover:text-[#00AA5B] hover:border-[#00AA5B] transition-all">
                                        <span x-text="tag"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- Live Results matching the image style --}}
                    <template x-if="$store.global.search.length > 0">
                        <div class="py-2">
                            <div x-show="loading" class="px-4 py-2 text-[10px] text-gray-400 italic">Mencari bolo...</div>
                            
                            <template x-for="item in results" :key="item.id">
                                <a :href="item.url" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors group">
                                    <div class="relative shrink-0">
                                        <img :src="item.seller_avatar" class="w-10 h-10 rounded-full border border-gray-100 object-cover">
                                        <template x-if="item.is_verified">
                                            <div class="absolute -bottom-1 -right-1 bg-white rounded-full p-0.5 shadow-sm">
                                                <svg class="w-3 h-3 text-[#00AA5B]" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                </svg>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <div class="flex items-center gap-1">
                                            <span class="text-xs font-bold text-gray-800 line-clamp-1" x-text="item.name"></span>
                                            <template x-if="item.is_verified">
                                                <span class="text-[9px] font-black text-[#00AA5B] uppercase tracking-tighter">Official</span>
                                            </template>
                                        </div>
                                        <div class="text-[10px] text-gray-500 font-medium" x-text="item.location"></div>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-300 group-hover:text-[#00AA5B] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </template>

                            <div x-show="results.length === 0 && !loading" class="px-4 py-8 text-center">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Barang nggak ketemu bolo</p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- RIGHT ICONS --}}
            <div class="flex items-center gap-2 md:gap-5">
                <button @click="$dispatch('open-cart')" class="relative p-2 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <template x-if="cartCount > 0">
                        <span class="absolute top-1 right-1 bg-red-500 text-white text-[8px] font-bold w-4 h-4 rounded-full flex items-center justify-center" x-text="cartCount"></span>
                    </template>
                </button>

                <div class="h-6 w-[1px] bg-gray-200 hidden md:block"></div>

                @auth
                <div class="hidden md:block">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 px-2 py-1 hover:bg-gray-50 rounded-lg transition-colors">
                                <div class="w-8 h-8 rounded-full overflow-hidden border border-gray-200">
                                    @if (Auth::user()->avatar)
                                        <img src="{{ asset(Auth::user()->avatar) }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <span class="text-xs font-semibold text-gray-600 truncate max-w-[100px]">{{ Auth::user()->name }}</span>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('dashboard')">{{ __('Dashboard') }}</x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
                @endauth

                @guest
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('login') }}" class="text-xs font-bold text-[#00AA5B] border border-[#00AA5B] px-4 py-1.5 rounded-lg hover:bg-gray-50 transition-all">Masuk</a>
                    <a href="{{ route('register') }}" class="text-xs font-bold text-white bg-[#00AA5B] border border-[#00AA5B] px-4 py-1.5 rounded-lg hover:bg-[#00944f] transition-all">Daftar</a>
                </div>
                @endguest

                {{-- Mobile Toggle --}}
                <button @click="open = !open" class="md:hidden p-2 text-gray-600">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- 3. SUB NAV (LOCATION) --}}
    <div class="bg-white border-t border-gray-50 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 h-8 flex items-center text-[11px] text-gray-500">
            <div class="flex items-center gap-1 cursor-pointer hover:text-gray-700">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Dikirim ke</span>
                <span class="font-bold text-gray-800">Alamat Anda</span>
                <svg class="w-3 h-3 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU DRAWER --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        @click.away="open = false"
        class="sm:hidden bg-white border-t border-gray-100 shadow-2xl rounded-b-[2.5rem] overflow-hidden" x-cloak>

        <div class="pt-4 pb-3 space-y-2 px-4">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')" class="rounded-2xl">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="rounded-2xl">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('shop.public')" :active="request()->routeIs('shop.public')" class="rounded-2xl">
                {{ __('Produk') }}
            </x-responsive-nav-link>
            @auth
                @unlessrole('admin')
                <x-responsive-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')" class="rounded-2xl">
                    {{ __('Riwayat Pesanan') }}
                </x-responsive-nav-link>
                @endunlessrole
            @endauth
        </div>

        <div class="pt-4 pb-8 border-t border-gray-100 px-6">
            @auth
                <div class="flex items-center gap-4 bg-indigo-50/50 p-5 rounded-[2rem] mb-6 border border-indigo-100/50">
                    <div
                        class="w-14 h-14 rounded-2xl overflow-hidden shadow-lg border-2 border-white ring-1 ring-indigo-200">
                        @if (Auth::user()->avatar)
                            <img src="{{ asset(Auth::user()->avatar) }}" class="w-full h-full object-cover">
                        @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div>
                        <div class="font-black text-gray-900 text-lg leading-none mb-1">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">
                            {{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="space-y-3">
                    <a href="{{ route('profile.edit') }}"
                        class="w-full rounded-2xl font-black uppercase tracking-widest text-[11px] py-4 bg-white border border-gray-100 text-gray-700 shadow-sm flex justify-center items-center">
                        {{ __('Account Profile') }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full rounded-2xl font-black uppercase tracking-widest text-[11px] py-4 bg-red-50 text-red-600 border border-red-100 flex justify-center items-center">
                            {{ __('Log Out Account') }}
                        </button>
                    </form>
                </div>
            @endauth

            @guest
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('login') }}"
                        class="flex justify-center items-center py-4 rounded-2xl font-black uppercase tracking-widest text-[11px] bg-gray-50 text-gray-700 border border-gray-100">Login</a>
                    <a href="{{ route('register') }}"
                        class="flex justify-center items-center py-4 rounded-2xl font-black uppercase tracking-widest text-[11px] bg-indigo-600 text-white shadow-lg shadow-indigo-100">Register</a>
                </div>
            @endguest
        </div>
    </div>
</nav>
