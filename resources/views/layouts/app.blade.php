<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $siteSettings['site_name'] ?? config('app.name', 'MiniQ-Store') }} | {{ $siteSettings['site_tagline'] ?? 'Belanja Keren Bolo' }}</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800,900" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="icon" type="image/png/svg" href="{{ asset('LogoQ.svg') }}" />
    @stack('styles')

    {{-- Dynamic Theming --}}
    @include('layouts.partials.dynamic-theme')

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
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

        /* Animasi Dot (Titik-titik) */
        @keyframes blink {
            0%, 100% { opacity: 0.2; }
            50% { opacity: 1; }
        }

        .dot-anim {
            animation: blink 1s infinite;
        }
    </style>

    @include('layouts.partials.alpine-stores')
</head>

<body class="antialiased select-none" x-data="{ loading: true }" x-init="window.onload = () => { setTimeout(() => loading = false, 300) }">

    
    {{-- LOADING GLASSMORPHISM --}}
    <div x-show="loading" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-white/30 backdrop-blur-md" x-cloak>
    
    <div class="flex flex-col items-center p-8 rounded-2xl bg-white/50 border border-white/20 shadow-lg">
        {{-- Spinner Kecil emerald --}}
        <div class="w-10 h-10 border-4 border-emerald-100 border-t-emerald-600 rounded-full animate-spin mb-4"></div>

            {{-- Teks Tunggu --}}
            <div class="flex items-center gap-1">
                <span class="text-gray-600 text-sm font-medium">Tunggu sebentar</span>
                <span class="text-emerald-600 font-medium dot-anim">.</span>
                <span class="text-emerald-600 font-medium dot-anim" style="animation-delay: 0.2s">.</span>
                <span class="text-emerald-600 font-medium dot-anim" style="animation-delay: 0.4s">.</span>
            </div>
        </div>
    </div>

    
    
    {{-- KONTEN UTAMA --}}
    <div class="min-h-screen">
        {{-- Container Toast Notification --}}
        <div x-data="{ notifications: [] }"
            @notify.window="let id = Date.now(); notifications.push({id, message: $event.detail, type: 'success'}); setTimeout(() => { notifications = notifications.filter(n => n.id !== id) }, 3000)"
            class="fixed top-24 right-6 z-[9999] flex flex-col gap-3">
    
            <template x-for="n in notifications" :key="n.id">
                <div x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
                    x-transition:leave="transition ease-in duration-300" x-transition:leave-end="translate-x-full opacity-0"
                    class="bg-white border-l-4 border-emerald-600 shadow-lg rounded-xl p-4 min-w-[250px] flex items-center gap-3">
                    <div class="bg-emerald-50 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700" x-text="n.message"></span>
                </div>
            </template>
        </div>
        @include('layouts.navigation')
        <x-cart-modal />

        @if (isset($header))
            <header class="bg-white/40 backdrop-blur-sm border-b border-gray-100">
                <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                    <div data-aos="fade-down">
                        {{ $header }}
                    </div>
                </div>
            </header>
        @endif

        <main class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{-- Global Breadcrumbs --}}
                <nav class="flex mb-8 items-center justify-between" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li class="inline-flex items-center">
                            <a href="{{ route('home') }}" class="inline-flex items-center text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400 hover:text-emerald-600 transition-colors">
                                <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                Home
                            </a>
                        </li>
                        @php
                            $segments = Request::segments();
                            $currentUrl = '';
                        @endphp
                        @foreach($segments as $segment)
                            @php $currentUrl .= '/'.$segment; @endphp
                            <li>
                                <div class="flex items-center">
                                    <span class="text-slate-300 mx-1">/</span>
                                    <a href="{{ url($currentUrl) }}" class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400 hover:text-emerald-600 transition-colors">
                                        {{ str_replace('-', ' ', $segment) }}
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </nav>

                {{ $slot }}
            </div>
        </main>

        <x-footer />
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            AOS.init({
                duration: 500,
                once: true
            });
        });

        // Notification management functions
        function deleteNotification(id, element, e) {
            if (e) { e.preventDefault(); e.stopPropagation(); }
            
            let item = element.closest('[class*="group/notif"]') || element.closest('.relative') || element.parentElement;
            if (!item) return;

            item.style.transition = 'all 0.3s ease';
            item.style.opacity = '0.5';
            item.style.pointerEvents = 'none';

            fetch(`/notifications/${id}/delete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if(response.ok) {
                    item.style.transform = 'translateX(20px)';
                    item.style.opacity = '0';
                    setTimeout(() => item.remove(), 300);
                } else {
                    item.style.opacity = '1';
                    item.style.pointerEvents = 'auto';
                }
            })
            .catch(() => {
                item.style.opacity = '1';
                item.style.pointerEvents = 'auto';
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
                                container.innerHTML = '<p class="text-[11px] font-bold text-slate-300 text-center py-10 uppercase tracking-[0.2em]">Semua aman terkendali!</p>';
                            }
                            Swal.fire({ icon: 'success', title: 'Bersih!', text: 'Semua notifikasi berhasil dihapus.', timer: 1500, showConfirmButton: false });
                        }
                    });
                }
            });
        }
    </script>
    @stack('scripts')
</body>

</html>

