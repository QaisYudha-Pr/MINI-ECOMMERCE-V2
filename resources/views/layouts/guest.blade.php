<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50 relative overflow-hidden">
            {{-- Abstract Ornaments --}}
            <div class="absolute top-[-10%] left-[-10%] w-[40rem] h-[40rem] bg-emerald-50 rounded-full blur-[120px] -z-10 opacity-60"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[30rem] h-[30rem] bg-slate-100 rounded-full blur-[100px] -z-10 opacity-60"></div>

            <div class="z-10 transform scale-125 mb-8">
                <a href="/">
                    <x-application-logo />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-10 py-12 bg-white shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden sm:rounded-2xl z-10">
                {{ $slot }}
            </div>

            <div class="mt-8 text-center z-10">
                <p class="text-xs font-medium text-slate-400">© {{ date('Y') }} MINI Q STORE — BOLO TERUS</p>
            </div>
        </div>
    </body>
</html>

