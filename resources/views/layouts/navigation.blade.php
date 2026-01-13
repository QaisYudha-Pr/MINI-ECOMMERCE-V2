<nav x-data="{
    open: false,
    {{-- Mengambil jumlah item keranjang dari localStorage secara real-time --}}
    get cartCount() {
        try {
            const cart = JSON.parse(localStorage.getItem('minie_cart') || '[]');
            return cart.length;
        } catch (e) { return 0; }
    }
}"
    class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50 transition-all duration-300">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                {{-- LOGO --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}"
                        class="group flex items-center gap-3 transition-transform active:scale-95">
                        <div
                            class="w-11 h-11 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-200 group-hover:rotate-6 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 118 0m-4 5v2a3 3 0 01-3 3h-6a3 3 0 01-3-3V7a3 3 0 013-3h6a3 3 0 013 3V7" />
                            </svg>
                        </div>
                        <div class="flex flex-col text-left">
                            <span class="font-black text-xl tracking-tighter text-gray-900 leading-none">MiniE-<span
                                    class="text-indigo-600">Store</span></span>
                            <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Q-store</span>
                        </div>
                    </a>
                </div>

                {{-- NAV LINKS --}}
                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex items-center">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('item-shop.index')" :active="request()->routeIs('item-shop.*')">
                        {{ __('Produk') }}
                    </x-nav-link>
                    @auth
                        @role('admin')
                            <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                                {{ __('Kelola User') }}
                            </x-nav-link>
                        @endrole
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                {{-- Navbar Cart Icon --}}
                <div x-data="{
                    count: JSON.parse(localStorage.getItem('minie_cart') || '[]').length
                }"
                    @notify.window="count = JSON.parse(localStorage.getItem('minie_cart') || '[]').length"
                    @remove-from-cart.window="setTimeout(() => { count = JSON.parse(localStorage.getItem('minie_cart') || '[]').length }, 100)"
                    class="relative">

                    <button @click="$dispatch('open-cart')"
                        class="p-2 tracking-tighter font-black text-sm uppercase flex items-center gap-2">
                        <div class="relative">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>

                            {{-- Badge angka yang otomatis update --}}
                            <template x-if="count > 0">
                                <span
                                    class="absolute -top-1 -right-1 bg-indigo-600 text-white text-[9px] font-black w-4 h-4 rounded-full flex items-center justify-center"
                                    x-text="count">
                                </span>
                            </template>
                        </div>
                    </button>
                </div>

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="flex items-center gap-3 px-4 py-2 bg-gray-50/50 hover:bg-white hover:shadow-xl border border-transparent rounded-xl transition-all duration-300 group">
                                <div class="flex flex-col text-right hidden lg:flex">
                                    <span
                                        class="text-sm font-black text-gray-900 leading-none">{{ Auth::user()->name }}</span>
                                    <span class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mt-1">
                                        {{ Auth::user()->getRoleNames()->first() ?? 'User' }}
                                    </span>
                                </div>
                                <div
                                    class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-sm ring-1 ring-gray-100 transition-transform group-hover:scale-105">
                                    @if (Auth::user()->avatar)
                                        <img src="{{ asset(Auth::user()->avatar) }}" class="w-full h-full object-cover">
                                    @else
                                        <div
                                            class="w-full h-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white font-black text-xs">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="p-2 space-y-1">
                                <x-dropdown-link :href="route('profile.edit')"
                                    class="rounded-xl font-bold text-xs uppercase tracking-widest">
                                    {{ __('My Profile') }}
                                </x-dropdown-link>
                                <div class="my-2 border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        class="rounded-xl font-bold text-xs uppercase tracking-widest text-red-600 hover:bg-red-50"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                @endauth

                @guest
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}"
                            class="text-xs font-black uppercase text-gray-500 hover:text-indigo-600 transition-colors">Login</a>
                        <a href="{{ route('register') }}"
                            class="text-xs font-black uppercase px-5 py-2.5 bg-indigo-600 text-white rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 active:scale-95 transition-all">Register</a>
                    </div>
                @endguest
            </div>

            {{-- MOBILE MENU BUTTON --}}
            <div class="-me-2 flex items-center sm:hidden gap-3">
                {{-- Cart Icon di Mobile --}}
                <a href="#" class="relative p-2 text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 118 0m-4 5v2a3 3 0 01-3 3h-6a3 3 0 01-3-3V7a3 3 0 013-3h6a3 3 0 013 3V7" />
                    </svg>
                    <template x-if="cartCount > 0">
                        <span
                            class="absolute top-0 right-0 bg-red-500 text-white text-[8px] font-black w-4 h-4 flex items-center justify-center rounded-full border border-white"
                            x-text="cartCount"></span>
                    </template>
                </a>
                <button @click="open = ! open"
                    class="w-12 h-12 inline-flex items-center justify-center rounded-2xl text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-300 bg-white border border-gray-100 shadow-sm">
                    <svg class="h-8 w-8" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 8h16M4 16h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
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
            <x-responsive-nav-link :href="route('item-shop.index')" :active="request()->routeIs('item-shop.*')" class="rounded-2xl">
                {{ __('Produk') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-8 border-t border-gray-100 px-6">
            @auth
                <div class="flex items-center gap-4 bg-indigo-50/50 p-5 rounded-[2rem] mb-6 border border-indigo-100/50">
                    <div
                        class="w-14 h-14 rounded-2xl overflow-hidden shadow-lg border-2 border-white ring-1 ring-indigo-200">
                        @if (Auth::user()->avatar)
                            <img src="{{ asset(Auth::user()->avatar) }}" class="w-full h-full object-cover">
                        @else
                            <div
                                class="w-full h-full bg-indigo-600 flex items-center justify-center text-white font-black text-xl">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
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
