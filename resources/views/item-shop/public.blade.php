<x-app-layout>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }

        .product-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(79, 70, 229, 0.15);
        }

        .product-image {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .product-image img {
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .product-card:hover .product-image img {
            transform: scale(1.1);
        }
    </style>

    {{-- HERO SECTION --}}
    <section class="mx-3 sm:mx-6 mt-8 sm:mt-16 rounded-2xl bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 shadow-2xl p-8 sm:p-12 md:p-16 text-center text-white fade-in-up">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black mb-4 leading-tight">
                Toko Mini E-Store
            </h1>
            <p class="text-base sm:text-lg opacity-95 font-medium">
                Temukan produk berkualitas dengan harga terjangkau dari penjual terpercaya
            </p>
        </div>
    </section>

    {{-- PRODUK SECTION --}}
    <section class="mx-3 sm:mx-6 mt-12 sm:mt-16 mb-8 sm:mb-16">
        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Produk Terbaru</h2>
                <p class="text-gray-600 text-sm mt-1">{{ $items->total() }} produk tersedia</p>
            </div>
            @auth
                @can('create', App\Models\ItemShop::class)
                    <a href="{{ route('item-shop.create') }}"
                        class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:shadow-lg transition-all duration-300 font-semibold flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Produk
                    </a>
                @endcan
            @endauth
        </div>

        {{-- PRODUCTS GRID --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
            @forelse ($items as $item)
                <div class="product-card bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100 hover:border-indigo-200">
                    {{-- IMAGE CONTAINER --}}
                    <div class="product-image h-48 sm:h-56 relative">
                        @if($item->gambar)
                            <img src="{{ asset($item->gambar) }}"
                                class="w-full h-full object-cover"
                                alt="{{ $item->nama_barang }}" loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-gray-400 text-xs">Tidak ada gambar</span>
                                </div>
                            </div>
                        @endif
                        @if(auth()->check() && auth()->user()->can('update', $item))
                            <div class="absolute top-2 right-2">
                                <span class="bg-indigo-600 text-white px-3 py-1 rounded-full text-xs font-semibold">Milik Anda</span>
                            </div>
                        @endif
                    </div>

                    {{-- CONTENT --}}
                    <div class="p-4 sm:p-5 flex flex-col h-full">
                        {{-- NAMA PRODUK --}}
                        <h3 class="font-bold text-sm sm:text-base text-gray-900 line-clamp-2 mb-2">
                            {{ $item->nama_barang }}
                        </h3>

                        {{-- DESKRIPSI --}}
                        <p class="text-xs text-gray-600 line-clamp-2 mb-3 flex-grow">
                            {{ Str::limit($item->deskripsi ?? 'Tidak ada deskripsi', 60) }}
                        </p>

                        {{-- PENJUAL --}}
                        @if($item->user)
                            <div class="flex items-center gap-2 mb-3 text-xs text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="truncate">{{ $item->user->name }}</span>
                            </div>
                        @endif

                        {{-- HARGA --}}
                        <div class="mb-4 pb-4 border-b border-gray-100">
                            <p class="text-xl sm:text-2xl font-black text-indigo-600">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- BUTTONS --}}
                        <div class="flex gap-2 flex-col sm:flex-row">
                            <a href="{{ route('produk.show', $item->id) }}"
                                class="flex-1 text-center bg-indigo-600 text-white py-2.5 rounded-xl hover:bg-indigo-700 active:scale-95 transition-all duration-200 text-sm font-semibold">
                                Lihat Detail
                            </a>

                            @auth
                                @can('update', $item)
                                    <a href="{{ route('item-shop.edit', $item->id) }}"
                                        class="px-3 py-2.5 border-2 border-blue-500 text-blue-600 rounded-xl hover:bg-blue-50 active:scale-95 transition-all duration-200 text-sm font-semibold"
                                        title="Edit Produk">
                                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                @endcan

                                @can('delete', $item)
                                    <form method="POST" action="{{ route('item-shop.destroy', $item->id) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Yakin ingin menghapus produk ini?')"
                                            class="px-3 py-2.5 bg-red-100 text-red-600 rounded-xl hover:bg-red-200 active:scale-95 transition-all duration-200 text-sm font-semibold w-full sm:w-auto"
                                            title="Hapus Produk">
                                            <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                @endcan
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16 px-4">
                    <div class="max-w-md mx-auto">
                        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h3 class="text-xl font-bold text-gray-700 mb-2">Produk Belum Tersedia</h3>
                        <p class="text-gray-500 mb-6">Saat ini belum ada produk yang ditampilkan. Silakan check kembali nanti atau tambahkan produk baru.</p>
                        @auth
                            @can('create', App\Models\ItemShop::class)
                                <a href="{{ route('item-shop.create') }}"
                                    class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-all duration-200 font-semibold">
                                    Tambah Produk Pertama
                                </a>
                            @endcan
                        @endauth
                    </div>
                </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        @if($items->hasPages())
            <div class="mt-12 flex justify-center">
                <div class="inline-block">
                    {{ $items->links('pagination::tailwind') }}
                </div>
            </div>
        @endif
    </section>

</x-app-layout>

<script>
    console.log('Halo dari item-shop public view!');
</script>