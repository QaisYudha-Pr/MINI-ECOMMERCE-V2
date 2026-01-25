<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <!-- ikilo tempat sidebar dashboard -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MiniQ-Store') }}</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800,900" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="icon" type="image/png/svg" href="{{ asset('LogoQ.svg') }}" />
    @stack('styles')

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
                @if(Auth::user()->hasRole('seller') || Auth::user()->hasRole('admin') || Auth::user()->hasPermissionTo('lihat-produk'))
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
                @endif

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

                {{-- CMS SECTION --}}
                @if(Auth::user()->hasRole('admin'))
                <div class="pt-4 pb-2">
                    <span class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Appearance</span>
                </div>
                <li>
                    <a href="{{ route('admin.cms.index') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('admin.cms.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('admin.cms.*') ? 'text-indigo-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                        </svg>
                        <span class="ms-3">CMS Settings</span>
                    </a>
                </li>
                @endif

                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('income.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('income.*') ? 'text-indigo-700' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="ms-3">Status Pendapatan</span>
                    </a>
                </li>

                @unlessrole('admin')
                <li>
                    <a href="{{ route('transactions.index') }}" class="flex items-center p-3 text-slate-700 rounded-xl hover:bg-slate-50 group {{ request()->routeIs('transactions.*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('transactions.*') ? 'text-indigo-700' : 'text-slate-400 group-hover:text-slate-900' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v7.5m2.25-6.466a9.016 9.016 0 0 0-3.461-.203c-.536.072-.974.478-1.021 1.017a4.559 4.559 0 0 0-.018.402c0 .464.336.844.775.994l2.95 1.012c.44.15.775.53.775.994 0 .136-.006.27-.018.402-.047.539-.485.945-1.021 1.017a9.077 9.077 0 0 1-3.461-.203M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        <span class="ms-3">Detail Transaction</span>
                    </a>
                </li>
                @endunlessrole

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
    <div class="p-8 lg:ml-64 pt-28 min-h-screen bg-[#f8fafc]">
        @if (isset($header))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
                {{ $header }}
            </div>
        @endif
        <main>
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