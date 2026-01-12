<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $item->nama_barang }}</title>
    @vite('resources/css/app.css')
</head>

{{-- Sebagai Detail Produk --}} 

<body class="bg-gray-100 min-h-screen p-8">

    <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow p-8 grid md:grid-cols-2 gap-8">
        <img src="{{ $item->gambar ?? 'https://via.placeholder.com/500' }}"
             class="rounded-xl w-full object-cover">

        <div>
            <h1 class="text-3xl font-bold mb-2">{{ $item->nama_barang }}</h1>

            <p class="text-gray-600 mb-4">{{ $item->deskripsi }}</p>

            <p class="text-2xl font-semibold text-indigo-600 mb-6">
                Rp {{ number_format($item->harga, 0, ',', '.') }}
            </p>

            {{-- ROLE / AUTH LOGIC --}}
            @auth
                <button class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700">
                    Beli Sekarang
                </button>
            @else
                <div class="p-4 bg-yellow-50 border border-yellow-300 rounded-xl">
                    <p class="text-sm">
                        Login dulu buat beli produk 
                    </p>
                    <a href="{{ route('login') }}"
                       class="inline-block mt-3 text-indigo-600 font-semibold">
                        Login →
                    </a>
                </div>
            @endauth
        </div>
    </div>

</body>
</html>
