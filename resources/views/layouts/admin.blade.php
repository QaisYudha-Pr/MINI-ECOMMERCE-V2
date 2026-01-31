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

    {{-- TinyMCE (Self-hosted/Public CDN) --}}
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>

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
                {{-- Global Notifications --}}
                <div x-data="{ unreadCount: {{ auth()->user()->notifications()->where('is_read', false)->count() }} }">
                    <x-dropdown align="right" width="80">
                        <x-slot name="trigger">
                            <button @click="if(unreadCount > 0) { fetch('{{ route('notifications.read-all') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(() => unreadCount = 0) }"
                                class="relative p-2.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-300 group">
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
                                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest">Notifikasi</h3>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest" x-text="unreadCount + ' Baru'"></span>
                                    </div>
                                </div>
                            <div class="space-y-3 max-h-[300px] overflow-y-auto no-scrollbar">
                                @forelse(auth()->user()->notifications()->latest()->get() as $notif)
                                <div class="flex items-start gap-4 p-3 rounded-2xl hover:bg-slate-50 transition-all group/item">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $notif->is_read ? 'bg-slate-100 text-slate-400' : 'bg-indigo-100 text-indigo-600 shadow-lg shadow-indigo-100' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                    </div>
                                    <div class="flex-grow">
                                        <h4 class="text-[10px] font-black text-slate-900 leading-tight uppercase tracking-tight mb-0.5">{{ $notif->title }}</h4>
                                        <p class="text-[9px] text-slate-500 font-bold leading-relaxed">{{ $notif->message }}</p>
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-tighter mt-2 block">{{ $notif->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                @empty
                                <div class="py-10 text-center">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tidak ada notifikasi bolo</p>
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
                                <div class="text-sm font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">{{ Auth::user()->name }}</div>
                                <div class="text-[10px] text-slate-500 uppercase tracking-wider font-semibold">
                                    {{ Auth::user()->nama_toko ?? Auth::user()->getRoleNames()->first() }}
                                </div>
                            </div>
                            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-sm ring-1 ring-slate-100 group-hover:ring-indigo-100 transition-all">
                                @if (Auth::user()->avatar)
                                <img src="{{ asset(Auth::user()->avatar) }}" class="w-full h-full object-cover">
                                @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" class="w-full h-full object-cover">
                                @endif
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="p-2 space-y-1">
                            <x-dropdown-link :href="route('profile.edit')" class="rounded-xl font-bold text-xs uppercase tracking-widest">
                                {{ __('My Profile') }}
                            </x-dropdown-link>
                            <div class="my-2 border-t border-slate-100"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" class="rounded-xl font-bold text-xs uppercase tracking-widest text-red-600 hover:bg-red-50"
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
    <aside class="fixed top-16 left-0 z-40 w-64 h-[calc(100vh-4rem)] transition-transform bg-white border-r border-slate-200"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
        <div class="h-full px-3 py-4 overflow-y-auto">
            <ul class="space-y-2 font-medium">
                {{-- Dashboard --}}
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 transition duration-75 {{ request()->routeIs('dashboard') ? 'text-indigo-700' : 'text-slate-400 group-hover:text-slate-900' }}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>
                <!-- home -->
                <li>
                    <a href="{{ route('home') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('home') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('home') ? 'text-indigo-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="ms-3">Home</span>
                    </a>
                </li>

                {{-- User Section --}}
                @unless(Auth::user()->hasRole('seller') || Auth::user()->hasRole('admin'))
                <div class="pt-4 pb-2">
                    <span class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Shopping Hub</span>
                </div>
                <li>
                    <a href="{{ route('shop.public') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('shop.public') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('shop.public') ? 'text-indigo-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="ms-3">Jelajah Toko</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('wishlist.index') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('wishlist.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('wishlist.*') ? 'text-indigo-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span class="ms-3">Barang Favorit</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('reviews.index') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('reviews.index') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('reviews.index') ? 'text-indigo-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        <span class="ms-3">Ulasan Saya</span>
                    </a>
                </li>
                @endunless

                {{-- Seller Centre --}}
                @if(Auth::user()->hasRole('seller') || Auth::user()->hasRole('admin'))
                <div class="pt-4 pb-2">
                    <span class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Seller Centre</span>
                </div>
                <li>
                    <a href="{{ route('item-shop.index') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('item-shop.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('item-shop.*') ? 'text-indigo-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span class="ms-3">My Products</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.transactions.index') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('admin.transactions.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('admin.transactions.*') ? 'text-indigo-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="ms-3">Orders Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.withdrawals.index') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('admin.withdrawals.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('admin.withdrawals.*') ? 'text-indigo-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="ms-3">
                            @if(Auth::user()->hasRole('admin'))
                                Manajemen Payout
                            @else
                                Tarik Saldo
                            @endif
                        </span>
                    </a>
                </li>
                @endif

                {{-- Administration --}}
                @if(Auth::user()->hasRole('admin') || Auth::user()->hasPermissionTo('lihat-user'))
                <div class="pt-4 pb-2">
                    <span class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Administration</span>
                </div>
                <li>
                    <a href="{{ route('users.index') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('users.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('users.*') ? 'text-indigo-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="ms-3">Manage Users</span>
                    </a>
                </li>
                @if(Auth::user()->hasRole('admin'))
                <li>
                    <a href="{{ route('admin.sellers.index') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('admin.sellers.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('admin.sellers.*') ? 'text-indigo-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span class="ms-3">Seller Requests</span>
                    </a>
                </li>
                @endif
                @endif

                {{-- Appearance --}}
                @if(Auth::user()->hasRole('admin'))
                <div class="pt-4 pb-2">
                    <span class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Settings</span>
                </div>
                <li>
                    <a href="{{ route('admin.cms.index') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('admin.cms.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('admin.cms.*') ? 'text-indigo-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="ms-3">Konfigurasi Situs</span>
                    </a>
                </li>
                @endif

                {{-- Activity Section --}}
                <div class="pt-4 pb-2">
                    <span class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Aktivitas Saya</span>
                </div>

                {{-- @role('admin|seller')
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('income.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('income.*') ? 'text-indigo-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="ms-3">Status Pendapatan</span>
                    </a>
                </li>
                @endrole --}}

                <li>
                    <a href="{{ route('transactions.index') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('transactions.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('transactions.*') ? 'text-indigo-700' : 'text-slate-400 group-hover:text-slate-900' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v7.5m2.25-6.466a9.016 9.016 0 0 0-3.461-.203c-.536.072-.974.478-1.021 1.017a4.559 4.559 0 0 0-.018.402c0 .464.336.844.775.994l2.95 1.012c.44.15.775.53.775.994 0 .136-.006.27-.018.402-.047.539-.485.945-1.021 1.017a9.077 9.077 0 0 1-3.461-.203M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        <span class="ms-3">Riwayat Belanja</span>
                    </a>
                </li>

                {{-- Become Seller CTA --}}
                @if(!Auth::user()->hasRole('seller') && !Auth::user()->hasRole('admin'))
                <div class="mt-10 px-2">
                    <div class="relative overflow-hidden bg-[#0F172A] rounded-[2rem] p-6 shadow-2xl shadow-slate-200 group">
                        {{-- Decorative Elements --}}
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all duration-500"></div>
                        <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all duration-500"></div>

                        <div class="relative z-10">
                            <div class="w-10 h-10 bg-gradient-to-br from-[#00AA5B] to-emerald-400 rounded-xl flex items-center justify-center mb-4 shadow-lg shadow-emerald-500/20 rotate-3 group-hover:rotate-12 transition-transform duration-500">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            
                            <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-400 mb-2">Peluang Bisnis</h4>
                            <p class="text-xs font-bold text-slate-300 leading-relaxed mb-6">Buka toko pertamamu & raih cuan bareng <span class="text-white">MiniQ</span> bolo!</p>
                            
                            <a href="{{ route('seller.create') }}" class="flex items-center justify-center gap-2 w-full py-3.5 bg-white text-slate-900 rounded-xl text-center text-[10px] font-black uppercase tracking-widest hover:bg-[#00AA5B] hover:text-white transition-all transform active:scale-95 shadow-xl shadow-black/10">
                                <span>Mulai Berjualan</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center p-3 text-red-600 rounded-xl hover:bg-red-50 group">
                            <svg class="w-5 h-5 transition duration-75 text-red-500 group-hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="ms-3">Log Out</span>
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
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-indigo-600 transition-colors">
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
                                <a href="{{ url($currentUrl) }}" class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-indigo-600 transition-colors">
                                    {{ str_replace('-', ' ', $segment) }}
                                </a>
                            </div>
                        </li>
                        @endif
                    @endforeach
                </ol>

                <div class="hidden md:flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-lg shadow-emerald-200 animate-pulse"></span>
                    <span class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Live Server</span>
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
    <script>
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000,
            customClass: {
                popup: 'rounded-[1.5rem]'
            }
        });
        @endif
        @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: '<ul class="text-left list-disc pl-4">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
            customClass: {
                popup: 'rounded-[1.5rem]',
                confirmButton: 'rounded-xl'
            }
        });
        @endif
    </script>
</body>

</html>