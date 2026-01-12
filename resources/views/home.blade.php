<x-app-layout>

    {{-- HERO --}}
    <section class="mx-6 mt-16 rounded-2xl bg-white shadow p-12 text-center">
        ...
    </section>

    {{-- PRODUK --}}
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

                    <a href="{{ route('item-shop.show', $item->id) }}"
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

</x-app-layout>
