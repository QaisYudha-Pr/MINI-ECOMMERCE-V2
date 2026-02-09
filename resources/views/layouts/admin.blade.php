<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <!-- ikilo tempat sidebar dashboard -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MiniQ-Store') }}</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800,900" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="icon" type="image/png/svg" href="{{ asset('LogoQ.svg') }}" />
    @stack('styles')

    @include('layouts.partials.alpine-stores')

    {{-- Dynamic Theming --}}
    @include('layouts.partials.dynamic-theme')

    <style>
        [x-cloak] {
            display: none !important;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            @apply bg-slate-300;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            @apply bg-slate-400;
        }
    </style>
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-900" x-data="{ sidebarOpen: false }">

    {{-- Top Navbar --}}
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-slate-200 shadow-sm h-16">
        <div class="px-4 lg:px-6 w-full h-full flex items-center justify-between">
            <div class="flex items-center gap-4">
                {{-- Mobile Toggle --}}
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 text-slate-500 hover:text-slate-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center">
                    <x-application-logo class="h-8" />
                </a>
            </div>

            <div class="flex items-center gap-4">
                {{-- Global Cart --}}
                <button @click="$store.cart.show = true" class="relative p-2.5 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all duration-300 group">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <template x-if="$store.cart.items.length > 0">
                        <span class="absolute top-2 right-2 bg-rose-500 text-white text-[11px] font-bold w-4 h-4 rounded-full flex items-center justify-center border-2 border-white animate-bounce" x-text="$store.cart.items.length"></span>
                    </template>
                </button>

                {{-- Global Notifications --}}
                <div x-data="{ unreadCount: {{ auth()->user()->notifications()->where('is_read', false)->count() }} }">
                    <x-dropdown align="right" width="80">
                        <x-slot name="trigger">
                            <button @click="if(unreadCount > 0) { fetch('{{ route('notifications.read-all') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(() => unreadCount = 0) }"
                                class="relative p-2.5 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all duration-300 group">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                <template x-if="unreadCount > 0">
                                    <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-rose-500 border-2 border-white rounded-full animate-pulse"></span>
                                </template>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="w-80 p-4">
                                <div class="flex items-center justify-between mb-4 border-b border-slate-50 pb-4">
                                    <div>
                                        <h3 class="text-xs font-bold text-slate-900">Notifikasi</h3>
                                        <span class="text-[11px] font-bold text-slate-400" x-text="unreadCount + ' Baru'"></span>
                                    </div>
                                </div>
                            <div class="space-y-3 max-h-[300px] overflow-y-auto no-scrollbar">
                                @forelse(auth()->user()->notifications()->latest()->take(10)->get() as $notif)
                                <div onclick="markNotificationRead({{ $notif->id }}, this, event)" class="relative cursor-pointer flex items-start gap-4 p-3 rounded-2xl hover:bg-slate-50 transition-all group/item {{ $notif->is_read ? 'opacity-50' : '' }}">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $notif->is_read ? 'bg-slate-100 text-slate-400' : 'bg-emerald-100 text-emerald-600 shadow-lg shadow-emerald-100' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                    </div>
                                    <div class="flex-grow pr-6">
                                        <h4 class="text-xs font-bold text-slate-900 leading-tight mb-0.5">{{ $notif->title }}</h4>
                                        <p class="text-[11px] text-slate-500 font-bold leading-relaxed">{{ $notif->message }}</p>
                                        <span class="text-[11px] font-medium text-slate-400 mt-2 block">{{ $notif->created_at->diffForHumans() }}</span>
                                    </div>
                                    {{-- Delete Record --}}
                                    <button onclick="deleteNotification({{ $notif->id }}, this, event)" class="absolute top-3 right-3 opacity-0 group-hover/item:opacity-100 transition-all duration-200 p-1 text-slate-300 hover:text-rose-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                @empty
                                <div class="py-10 text-center">
                                    <p class="text-xs font-medium text-slate-400">Tidak ada notifikasi bolo</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </x-slot>
                </x-dropdown>
                </div>

                {{-- User Dropdown --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-3 transition-all duration-300 group">
                            <div class="text-right hidden sm:block">
                                <div class="text-sm font-bold text-slate-700 group-hover:text-emerald-600 transition-colors">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-slate-500 font-semibold">
                                    {{ Auth::user()->nama_toko ?? Auth::user()->getRoleNames()->first() }}
                                </div>
                            </div>
                            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-sm ring-1 ring-slate-100 group-hover:ring-emerald-100 transition-all">
                                @if (Auth::user()->avatar)
                                <img src="{{ asset(Auth::user()->avatar) }}" class="w-full h-full object-cover">
                                @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=059669&background=ECFDF5" class="w-full h-full object-cover">
                                @endif
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="p-2 space-y-1">
                            <x-dropdown-link :href="route('profile.edit')" class="rounded-xl font-bold text-xs">
                                {{ __('My Profile') }}
                            </x-dropdown-link>
                            <div class="my-2 border-t border-slate-100"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" class="rounded-xl font-bold text-xs text-red-600 hover:bg-red-50"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </nav>

    {{-- Sidebar --}}
    <aside class="themed-sidebar fixed top-16 left-0 z-40 w-64 h-[calc(100vh-4rem)] transition-transform bg-white border-r border-slate-200"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
        <div class="h-full px-3 py-4 overflow-y-auto">
            <ul class="space-y-2 font-medium">
                {{-- MAIN SECTION --}}
                <div class="pb-2">
                    <span class="px-3 text-xs font-medium sidebar-section-label text-emerald-600">Menu Utama</span>
                </div>
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('dashboard') ? 'sidebar-active bg-emerald-50 text-emerald-700' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 transition duration-75 {{ request()->routeIs('dashboard') ? 'text-emerald-700' : 'text-slate-400 group-hover:text-slate-900' }}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                        </svg>
                        <span class="ms-3 text-xs font-bold">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('home') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('home') ? 'sidebar-active bg-emerald-50 text-emerald-700' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('home') ? 'text-emerald-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="ms-3 text-xs font-bold">Ke Toko (Home)</span>
                    </a>
                </li>

                {{-- COURIER HUB: Khusus Driver Bolo! --}}
                @if(Auth::user()->hasRole('courier'))
                <div x-data="{ open: true }">
                    <div class="pt-4 pb-2 flex items-center justify-between px-3 cursor-pointer group/header" @click="open = !open">
                        <span class="text-xs font-semibold sidebar-section-label text-blue-600 uppercase tracking-wider">Pusat Pengiriman</span>
                        <svg class="w-3 h-3 text-slate-400 group-hover/header:text-blue-600 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2">
                        @php
                            $pendingDeliveriesCount = \App\Models\Transaction::where('courier_id', Auth::id())
                                ->where('status', 'shipped')
                                ->count();
                        @endphp
                        <li>
                            <a href="{{ route('courier.deliveries.index') }}" class="flex items-center justify-between p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('courier.deliveries.*') ? 'sidebar-active bg-blue-50 text-blue-700' : '' }}">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('courier.deliveries.*') ? 'text-blue-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V14a1 1 0 01-1 1h-1m-1 1a1 1 0 102 0v-5m-7 0h.01" />
                                    </svg>
                                    <span class="ms-3 text-xs font-bold">Antaran Saya</span>
                                </div>
                                @if($pendingDeliveriesCount > 0)
                                    <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-blue-500 rounded-lg shadow-sm shadow-blue-200">
                                        {{ $pendingDeliveriesCount }}
                                    </span>
                                @endif
                            </a>
                        </li>
                    </div>
                </div>
                @endif

                {{-- CUSTOMER HUB: Hide for courier while working --}}
                @if(!Auth::user()->hasRole('courier'))
                <div x-data="{ open: true }">
                    <div class="pt-4 pb-2 flex items-center justify-between px-3 cursor-pointer group/header" @click="open = !open">
                        <span class="text-xs font-semibold sidebar-section-label text-emerald-600">Aktivitas Belanja</span>
                        <svg class="w-3 h-3 text-slate-400 group-hover/header:text-emerald-600 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    
                    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2">
                        <li>
                            <a href="{{ route('transactions.index') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('transactions.*') ? 'sidebar-active bg-emerald-50 text-emerald-700' : '' }}">
                                <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('transactions.*') ? 'text-emerald-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <span class="ms-3 text-xs font-bold">Riwayat Belanja</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('wishlist.index') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('wishlist.*') ? 'sidebar-active bg-emerald-50 text-emerald-700' : '' }}">
                                <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('wishlist.*') ? 'text-emerald-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <span class="ms-3 text-xs font-bold">Daftar Suka</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('reviews.index') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('reviews.index') ? 'sidebar-active bg-emerald-50 text-emerald-700' : '' }}">
                                <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('reviews.index') ? 'text-emerald-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                                <span class="ms-3 text-xs font-bold">Ulasan Saya</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('chat.index') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('chat.*') ? 'sidebar-active bg-emerald-50 text-emerald-700' : '' }}">
                                <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('chat.*') ? 'text-emerald-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <span class="ms-3 text-xs font-bold">Pesan / Chat</span>
                            </a>
                        </li>
                    </div>
                </div>
                @endif

                {{-- SELLER CENTRE --}}
                @if(Auth::user()->hasRole('seller') || Auth::user()->hasRole('admin'))
                <div x-data="{ open: true }">
                    <div class="pt-4 pb-2 flex items-center justify-between px-3 cursor-pointer group/header" @click="open = !open">
                        <span class="px-3 text-xs font-semibold sidebar-section-label text-emerald-600">Manajemen Toko</span>
                        <svg class="w-3 h-3 text-slate-400 group-hover/header:text-emerald-600 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2">
                        @php
                            $pendingOrdersCount = \App\Models\Transaction::where('seller_id', Auth::id())
                                ->whereIn('status', ['paid', 'waiting_confirmation'])
                                ->count();
                        @endphp
                        <li>
                            <a href="{{ route('item-shop.index') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('item-shop.*') ? 'sidebar-active bg-emerald-50 text-emerald-700' : '' }}">
                                <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('item-shop.*') ? 'text-emerald-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <span class="ms-3 text-xs font-bold">Produk Saya</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.transactions.index') }}" class="flex items-center justify-between p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('admin.transactions.*') ? 'sidebar-active bg-emerald-50 text-emerald-700' : '' }}">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('admin.transactions.*') ? 'text-emerald-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    <span class="ms-3 text-xs font-bold">Pesanan Masuk</span>
                                </div>
                                @if($pendingOrdersCount > 0)
                                    <span class="w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-white shadow-sm"></span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.withdrawals.index') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('admin.withdrawals.*') ? 'sidebar-active bg-emerald-50 text-emerald-700' : '' }}">
                                <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('admin.withdrawals.*') ? 'text-emerald-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="ms-3 text-xs font-bold">
                                    @if(Auth::user()->hasRole('admin'))
                                        Manajemen Payout
                                    @else
                                        Keuangan & Saldo
                                    @endif
                                </span>
                            </a>
                        </li>
                        @if(Auth::user()->hasRole('admin'))
                        <li>
                            <a href="{{ route('admin.vouchers.index') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('admin.vouchers.*') ? 'sidebar-active bg-emerald-50 text-emerald-700' : '' }}">
                                <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('admin.vouchers.*') ? 'text-emerald-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                </svg>
                                <span class="ms-3 text-xs font-bold">Kelola Voucher</span>
                            </a>
                        </li>
                        @endif
                    </div>
                </div>
                @endif

                {{-- ADMINISTRATION --}}
                @if(Auth::user()->hasRole('admin') || Auth::user()->hasPermissionTo('lihat-user'))
                <div x-data="{ open: true }">
                    <div class="pt-4 pb-2 flex items-center justify-between px-3 cursor-pointer group/header" @click="open = !open">
                        <span class="px-3 text-xs font-medium sidebar-section-label text-emerald-600">Administrasi</span>
                        <svg class="w-3 h-3 text-slate-400 group-hover/header:text-emerald-600 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2">
                        <li>
                            <a href="{{ route('users.index') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('users.*') ? 'sidebar-active bg-emerald-50 text-emerald-700' : '' }}">
                                <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('users.*') ? 'text-emerald-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span class="ms-3 text-xs font-bold">Kelola User</span>
                            </a>
                        </li>
                        @if(Auth::user()->hasRole('admin'))
                        <li>
                            <a href="{{ route('admin.sellers.index') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('admin.sellers.*') ? 'sidebar-active bg-emerald-50 text-emerald-700' : '' }}">
                                <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('admin.sellers.*') ? 'text-emerald-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span class="ms-3 text-xs font-bold">Permintaan Seller</span>
                            </a>
                        </li>
                        @endif
                    </div>
                </div>
                @endif

                {{-- SYSTEM SETTINGS --}}
                @if(Auth::user()->hasRole('admin'))
                <div x-data="{ open: true }">
                    <div class="pt-4 pb-2 flex items-center justify-between px-3 cursor-pointer group/header" @click="open = !open">
                        <span class="px-3 text-xs font-medium sidebar-section-label text-emerald-600">Pengaturan</span>
                        <svg class="w-3 h-3 text-slate-400 group-hover/header:text-emerald-600 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2">
                        <li>
                            <a href="{{ route('admin.cms.index') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('admin.cms.*') ? 'sidebar-active bg-emerald-50 text-emerald-700' : '' }}">
                                <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('admin.cms.*') ? 'text-emerald-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="ms-3 text-xs font-bold">Konfigurasi Situs</span>
                            </a>
                        </li>
                    </div>
                </div>
                @endif

                {{-- Activity Section --}}
                {{-- Logout section --}}
                <div class="pt-4 pb-2">
                    <span class="px-3 text-xs font-medium sidebar-section-label text-emerald-600">Sistem</span>
                </div>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center p-3 text-red-600 rounded-xl hover:bg-red-50 group">
                            <svg class="w-5 h-5 transition duration-75 text-red-500 group-hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="ms-3 text-xs font-bold">Log Out</span>
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="lg:ml-64 pt-16 min-h-screen bg-[#F8FAFC]">
        <main class="p-4 lg:p-8 pt-8">
            {{-- Modern Breadcrumbs --}}
            <nav class="flex mb-8 items-center justify-between" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-xs font-semibold text-slate-400 hover:text-emerald-600 transition-colors">
                            <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            Main
                        </a>
                    </li>
                    @php
                        $segments = Request::segments();
                        $currentUrl = '';
                    @endphp
                    @foreach($segments as $segment)
                        @php $currentUrl .= '/'.$segment; @endphp
                        @if($segment != 'admin')
                        <li>
                            <div class="flex items-center">
                                <span class="text-slate-300 mx-1">/</span>
                                <a href="{{ url($currentUrl) }}" class="text-xs font-semibold text-slate-400 hover:text-emerald-600 transition-colors">
                                    {{ str_replace('-', ' ', $segment) }}
                                </a>
                            </div>
                        </li>
                        @endif
                    @endforeach
                </ol>

                <div class="hidden md:flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-lg shadow-emerald-200 animate-pulse"></span>
                    <span class="text-[11px] font-semibold text-slate-400">Live Server</span>
                </div>
            </nav>

            @if (isset($header))
                <div class="max-w-7xl mx-auto mb-8">
                    {{ $header }}
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

    {{-- Scripts Stack --}}
    @stack('scripts')

    {{-- Global Alert Script --}}
    <x-cart-modal />
    <script>
        function deleteNotification(id, element, e) {
            // Handle event properly
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            } else if (typeof event !== 'undefined') {
                event.preventDefault();
                event.stopPropagation();
            }
            
            // Find parent container
            let item = element.closest('[class*="group/item"]') || element.closest('.relative') || element.parentElement.parentElement;
            if (!item) {
                console.error('Container not found');
                return;
            }

            // Beri feedback visual instan (optimistic UI)
            item.style.transition = 'all 0.3s ease';
            item.style.opacity = '0.5';
            item.style.pointerEvents = 'none';

            fetch(`/notifications/${id}/delete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Delete response status:', response.status);
                if(response.ok) {
                    // Animasi hapus yang lebih mantap
                    item.style.transform = 'translateX(20px)';
                    item.style.opacity = '0';
                    setTimeout(() => {
                        item.remove();
                        
                        // Jika sudah tidak ada notifikasi lagi, tampilkan pesan kosong
                        const container = document.getElementById('notifications-list');
                        if (container && container.children.length === 0) {
                            container.innerHTML = '<p class="text-xs font-bold text-slate-300 text-center py-10">Semua aman terkendali!</p>';
                        }
                    }, 300);
                } else {
                    // Jika gagal, kembalikan tampilan
                    item.style.opacity = '1';
                    item.style.pointerEvents = 'auto';
                    response.text().then(text => console.error('Delete failed:', text));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                item.style.opacity = '1';
                item.style.pointerEvents = 'auto';
            });
        }

        function markNotificationRead(id, element, e) {
            if (e) {
                e.stopPropagation();
            }
            
            let item = element.closest('[class*="group/item"]') || element;
            
            fetch(`/notifications/mark-as-read/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if(response.ok) {
                    item.classList.add('opacity-50');
                    const dot = item.querySelector('.bg-emerald-600, .bg-emerald-100');
                    if(dot) {
                        dot.classList.remove('bg-emerald-600', 'bg-emerald-100', 'shadow-lg', 'shadow-emerald-100');
                        dot.classList.add('bg-slate-100', 'text-slate-400');
                    }
                }
            });
        }

        function deleteAllNotifications() {
            Swal.fire({
                title: 'Hapus semua notifikasi?',
                text: 'Aksi ini tidak bisa dibatalkan bolo!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus Semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/notifications/delete-all', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    }).then(response => {
                        if(response.ok) {
                            const container = document.getElementById('notifications-list');
                            if (container) {
                                container.innerHTML = '<p class="text-xs font-bold text-slate-300 text-center py-10">Semua aman terkendali!</p>';
                            }
                            Swal.fire({ icon: 'success', title: 'Bersih!', text: 'Semua notifikasi berhasil dihapus.', timer: 1500, showConfirmButton: false });
                        }
                    });
                }
            });
        }
    </script>
</body>

</html>

