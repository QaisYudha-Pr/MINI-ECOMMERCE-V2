<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'MiniE-Store') }}</title>

    @vite('resources/css/app.css')

    <link rel="icon" type="image/svg+xml" href="/LogoQ.svg">
</head>

<body class="min-h-screen bg-pattern text-gray-800">

    <!-- NAVBAR -->
    <header class="sticky top-0 z-50 mx-6 mt-6 rounded-xl bg-white/80 backdrop-blur shadow">
        <nav class="flex items-center justify-between px-6 py-3">
            <div class="flex items-center gap-2">
                <img class="h-10 w-10" src="/LogoQ.svg" alt="">
                <span class="font-bold text-lg">MiniE-Store</span>
            </div>

            <ul class="flex gap-6 text-sm font-medium items-center">
                <li><a class="hover:text-indigo-600" href="/">Beranda</a></li>
                <li><a class="hover:text-indigo-600" href="/produk">Produk</a></li>
                <li><a class="hover:text-indigo-600" href="#">Kontak</a></li>

                {{-- AUTH --}}
                @auth
                    <li class="text-gray-600">
                        Hi, {{ auth()->user()->name }}
                    </li>

                    <li>
                        <a href="/dashboard"
                            class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                            Dashboard
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('login') }}" class="hover:text-indigo-600">
                            Login
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}"
                            class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100">
                            Register
                        </a>
                    </li>
                @endauth
            </ul>
        </nav>
    </header>

    <!-- HERO -->
    <section class="mx-6 mt-16 rounded-2xl bg-white shadow p-12 text-center">
        <h1 class="text-4xl font-bold mb-4">
            Belanja Mudah, Cepat, & Terpercaya
        </h1>
        <p class="text-gray-600 max-w-xl mx-auto mb-8">
            Temukan berbagai produk pilihan dengan harga terbaik.
        </p>

        <div class="flex justify-center gap-4">
            <a href="/produk"
                class="px-6 py-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">
                Lihat Produk
            </a>
            <button class="px-6 py-3 rounded-xl border border-gray-300 hover:bg-gray-100">
                Tentang Kami
            </button>
        </div>
    </section>

    <!-- PRODUK TERBARU -->
    <section class="mx-6 mt-16">
        <h2 class="text-2xl font-bold mb-6">Produk Terbaru</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @forelse ($items as $item)
                <div class="bg-white rounded-xl shadow p-4 hover:shadow-lg transition">
                    <img src="{{ asset($item->gambar) }}"
                        class="h-40 w-full object-cover rounded mb-3"
                        alt="{{ $item->nama_barang }}">

                    <h3 class="font-semibold text-sm">
                        {{ $item->nama_barang }}
                    </h3>

                    <!-- RATING -->
                    <div class="text-yellow-400 text-sm">
                        @for ($i = 1; $i <= 5; $i++)
                            {{ $i <= round($item->ratings_avg) ? '★' : '☆' }}
                        @endfor
                    </div>

                    <p class="text-indigo-600 font-bold mt-2">
                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                    </p>

                    <a href="{{ route('produk.show', $item->id) }}"
                        class="block mt-3 text-center bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
                        Detail
                    </a>
                </div>
            @empty
                <p class="text-gray-500 col-span-4">
                    Produk belum tersedia.
                </p>
            @endforelse
        </div>
    </section>

</body>
</html>
