<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MiniE-Store') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800,900" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: #f8fafc; /* Warna abu-abu sangat muda biar card putih makin kontras */
            }
            /* Custom scrollbar biar makin premium */
            ::-webkit-scrollbar { width: 8px; }
            ::-webkit-scrollbar-track { background: #f1f1f1; }
            ::-webkit-scrollbar-thumb { 
                background: #6366f1; 
                border-radius: 10px;
            }
            ::-webkit-scrollbar-thumb:hover { background: #4f46e5; }
        </style>
    </head>
    <body class="antialiased select-none">
        <div class="min-h-screen">
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
            // Inisialisasi animasi AOS
            AOS.init({
                duration: 800,
                once: true,
                easing: 'ease-out-back'
            });
        </script>
    </body>
</html>