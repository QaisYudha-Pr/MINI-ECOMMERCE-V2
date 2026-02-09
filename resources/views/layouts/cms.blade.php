<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name', 'MiniQ-Store') }} - Content</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800,900" rel="stylesheet" />
</head>
<body class="font-['Plus_Jakarta_Sans'] bg-white antialiased">
    
    {{-- SIMPLE NAV --}}
    <nav class="fixed top-0 w-full z-[100] backdrop-blur-md bg-white/70 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('home') }}" class="flex items-center">
                    <x-application-logo class="h-9 w-auto" />
                </a>
                <div class="hidden md:flex items-center gap-10">
                    <a href="{{ route('home') }}" class="text-[11px] font-bold uppercase tracking-widest text-gray-500 hover:text-emerald-600 transition-colors">Produk</a>
                    <a href="{{ route('about') }}" class="text-[11px] font-bold uppercase tracking-widest text-emerald-600 border-b-2 border-emerald-600 pb-1">Tentang Kami</a>
                    <a href="#" class="text-[11px] font-bold uppercase tracking-widest text-gray-500 hover:text-emerald-600">Bantuan</a>
                    <a href="{{ route('login') }}" class="px-6 py-2.5 bg-gray-900 text-white text-[11px] font-bold uppercase tracking-widest rounded-xl hover:bg-black transition-all">Sign In</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-20">
        {{ $slot }}
    </main>

    <x-footer />

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    </script>
</body>
</html>

