<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MiniE-Store') }}</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800,900" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="icon" type="image/png/svg" href="{{ asset('LogoQ.svg') }}" />

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #6366f1;
            border-radius: 10px;
        }

        /* Animasi Dot (Titik-titik) */
        @keyframes blink {

            0%,
            100% {
                opacity: 0.2;
            }

            50% {
                opacity: 1;
            }
        }

        .dot-anim {
            animation: blink 1s infinite;
        }
    </style>
</head>

<body class="antialiased select-none" x-data="{ loading: true }" x-init="window.onload = () => { setTimeout(() => loading = false, 300) }">

    
    {{-- LOADING GLASSMORPHISM --}}
    <div x-show="loading" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-white/30 backdrop-blur-md" x-cloak>
    
    <div class="flex flex-col items-center p-8 rounded-[2.5rem] bg-white/50 border border-white/20 shadow-2xl">
        {{-- Spinner Kecil Indigo --}}
        <div class="w-10 h-10 border-4 border-indigo-100 border-t-indigo-600 rounded-full animate-spin mb-4"></div>

            {{-- Teks Tunggu --}}
            <div class="flex items-center gap-1">
                <span class="text-indigo-900 font-black text-[10px] uppercase tracking-[0.3em]">Tunggu Sebentar</span>
                <span class="text-indigo-600 font-black dot-anim">.</span>
                <span class="text-indigo-600 font-black dot-anim" style="animation-delay: 0.2s">.</span>
                <span class="text-indigo-600 font-black dot-anim" style="animation-delay: 0.4s">.</span>
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
                    class="bg-white border-l-4 border-indigo-600 shadow-2xl rounded-2xl p-4 min-w-[250px] flex items-center gap-3">
                    <div class="bg-indigo-100 p-2 rounded-xl">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span class="text-xs font-black text-gray-800 uppercase tracking-widest" x-text="n.message"></span>
                </div>
            </template>
        </div>
        @include('layouts.navigation')

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
            {{ $slot }}
        </main>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            AOS.init({
                duration: 500,
                once: true
            });
        });
    </script>
    @stack('scripts')
</body>

</html>

<script>
    // 1. Alert untuk Pesan Sukses (Session Success)
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'MANTAP BOLO!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000,
            customClass: {
                popup: 'rounded-[2rem]'
            }
        });
    @endif

    // 2. Alert untuk Error Validasi (Laravel Validation Errors)
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'WADUH ADA ERROR!',
            html: `
                <div class="text-left font-medium text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            `,
            confirmButtonText: 'SAYA PERBAIKI',
            confirmButtonColor: '#4f46e5', // Warna Indigo sesuai tema kamu
            customClass: {
                popup: 'rounded-[2rem]',
                confirmButton: 'rounded-xl px-6 py-3 text-xs font-black uppercase tracking-widest'
            }
        });
    @endif
</script>