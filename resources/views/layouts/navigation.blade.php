<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 transition-transform active:scale-95">
                        <div class="w-11 h-11 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-200 group-hover:rotate-6 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 118 0m-4 5v2a3 3 0 01-3 3h-6a3 3 0 01-3-3V7a3 3 0 013-3h6a3 3 0 013 3V7" />
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-black text-xl tracking-tighter text-gray-900 leading-none">MiniE-<span class="text-indigo-600">Store</span></span>
                            <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Dashboard</span>
                        </div>
                    </a>
                </div>

                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex items-center">
                    
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('item-shop.index')" :active="request()->routeIs('item-shop.*')">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        {{ __('Produk') }}
                    </x-nav-link>

                    @role('admin')
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        {{ __('Kelola User') }}
                    </x-nav-link>
                    @endrole
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-3 px-4 py-2 bg-gray-50 hover:bg-white hover:shadow-md border border-transparent rounded-[1.2rem] transition-all duration-300 group">
                                <div class="flex flex-col text-right hidden lg:flex">
                                    <span class="text-sm font-black text-gray-900 leading-none">{{ Auth::user()->name }}</span>
                                    <span class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mt-1">
                                        {{ Auth::user()->getRoleNames()->first() ?? 'user' }}
                                    </span>
                                </div>

                                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-gray-100 to-gray-200 flex items-center justify-center border border-white shadow-inner group-hover:from-indigo-500 group-hover:to-purple-500 transition-all">
                                    <span class="text-xs font-black group-hover:text-white transition-colors">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </span>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="p-2 space-y-1">
                                <x-dropdown-link :href="route('profile.edit')" class="rounded-xl font-bold text-xs uppercase tracking-widest flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2"/></svg>
                                    {{ __('My Profile') }}
                                </x-dropdown-link>

                                <div class="my-2 border-t border-gray-100"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            class="rounded-xl font-bold text-xs uppercase tracking-widest text-red-600 hover:bg-red-50 flex items-center"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="2"/></svg>
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="text-xs font-black uppercase text-gray-500 hover:text-indigo-600">Login</a>
                        <a href="{{ route('register') }}" class="text-xs font-black uppercase px-4 py-2 bg-indigo-600 text-white rounded-xl">Register</a>
                    </div>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="w-12 h-12 inline-flex items-center justify-center rounded-2xl text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-300">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200" 
         x-transition:enter-start="opacity-0 -translate-y-4" 
         x-transition:enter-end="opacity-100 translate-y-0" 
         class="sm:hidden bg-white/95 backdrop-blur-xl border-t border-gray-100 shadow-2xl overflow-hidden rounded-b-[2rem]">
        
        <div class="pt-4 pb-3 space-y-2 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('item-shop.index')" :active="request()->routeIs('item-shop.*')">
                {{ __('Produk') }}
            </x-responsive-nav-link>
            @role('admin')
            <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                {{ __('Kelola User') }}
            </x-responsive-nav-link>
            @endrole
        </div>

        <div class="pt-4 pb-8 border-t border-gray-100 px-6">
            @auth
                <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-3xl mb-4">
                    <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-black shadow-lg shadow-indigo-100">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-black text-gray-900 leading-none mb-1">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="space-y-2">
                    <x-responsive-nav-link :href="route('profile.edit')" class="rounded-2xl font-black uppercase tracking-widest text-[10px] text-gray-500 bg-white border border-gray-100 shadow-sm">
                        {{ __('Account Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                class="rounded-2xl font-black uppercase tracking-widest text-[10px] text-red-600 bg-red-50"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out Account') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @endauth
        </div>
    </div>
</nav>