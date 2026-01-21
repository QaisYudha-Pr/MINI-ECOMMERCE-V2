<x-admin-layout>
    {{-- Tambahkan library SweetAlert & AOS di head jika belum ada --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.7);
        }

        .tr-gradient:hover {
            background: linear-gradient(90deg, rgba(79, 70, 229, 0.03) 0%, rgba(255, 255, 255, 0) 100%);
            transform: translateY(-2px);
        }

        /* Custom Scrollbar biar smooth */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 overflow-hidden">
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12">
            <div data-aos="fade-right">
                <span
                    class="inline-block px-4 py-1.5 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-[0.2em] mb-4">
                    Master Inventory
                </span>
                <h1 class="text-4xl md:text-6xl font-black text-gray-900 tracking-tighter">
                    Manage <span class="text-indigo-600">Items.</span>
                </h1>
                <p class="text-gray-400 text-lg mt-2 font-medium">Monitoring inventory status and product performance.
                </p>
            </div>

            @can('create', App\Models\ItemShop::class)
                <div data-aos="fade-left">
                    <a href="{{ route('item-shop.create') }}"
                        class="group relative inline-flex items-center justify-center px-10 py-5 font-black text-white transition-all duration-300 bg-gray-900 rounded-[2rem] hover:bg-indigo-600 hover:-translate-y-1 shadow-2xl shadow-gray-200">
                        <svg class="w-5 h-5 mr-3 group-hover:rotate-90 transition-transform duration-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                        </svg>
                        NEW PRODUCT
                    </a>
                </div>
            @endcan
        </div>

        {{-- STATS CARDS (Dynamic for Admin & User) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            {{-- Total Product --}}
            <div data-aos="fade-up" data-aos-delay="100" class="glass-card p-8 rounded-[3rem] shadow-sm group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-2">
                            My Inventory</p>
                        <h3 class="text-4xl font-black text-gray-900 group-hover:text-indigo-600 transition-colors">
                            {{ $items->total() }} <span class="text-sm font-medium text-gray-400 uppercase">Items</span>
                        </h3>
                    </div>
                    <div class="bg-indigo-50 p-4 rounded-3xl text-indigo-600 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Info Halaman --}}
            <div data-aos="fade-up" data-aos-delay="200"
                class="glass-card p-8 rounded-[3rem] shadow-sm group border-b-4 border-indigo-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-2">
                            Active View</p>
                        <h3 class="text-4xl font-black text-gray-900 group-hover:text-indigo-600 transition-colors">Page
                            {{ $items->currentPage() }}</h3>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-3xl text-gray-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Status Check --}}
            <div data-aos="fade-up" data-aos-delay="300" class="glass-card p-8 rounded-[3rem] shadow-sm group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-2">
                            Inventory Health</p>
                        @if ($lowStockCount > 0)
                            <h3 class="text-2xl font-black text-red-600 uppercase animate-pulse">
                                Requires Attention
                            </h3>
                            <p class="text-xs font-bold text-red-400 mt-1">{{ $lowStockCount }} Items Low Stock (≤ 5)</p>
                        @else
                            <h3 class="text-2xl font-black text-gray-900 uppercase italic">
                                Excellent
                            </h3>
                            <p class="text-xs font-bold text-green-500 mt-1">Stock Levels Healthy</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- SEARCH BAR AREA (UNTUK SEMUA USER) --}}
        <div class="mb-8" data-aos="fade-up" data-aos-delay="400">
            <div class="flex items-center justify-between mb-4 px-2">
                <h3 class="font-black text-xl text-gray-900 uppercase tracking-tight">Search <span class="text-indigo-600">Product</span></h3>
            </div>
            <form action="{{ route('item-shop.index') }}" method="GET" class="relative group">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-indigo-600 transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="searchInput" name="search" placeholder="Cari nama produk, kategori..."
                    class="block w-full pl-16 pr-24 py-6 bg-white border-none rounded-[2rem] shadow-xl shadow-gray-100 focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-gray-700">

                <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                    @if (request('search'))
                        <a href="{{ route('item-shop.index') }}"
                            class="mr-4 text-[10px] font-black text-gray-400 hover:text-red-500 uppercase tracking-widest">Clear</a>
                    @endif
                    <button type="submit"
                        class="px-8 py-3 bg-gray-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-lg">
                        Search
                    </button>
                </div>
            </form>
        </div>

        {{-- TABLE AREA --}}
        <div data-aos="zoom-in"
            class="bg-white rounded-[3rem] shadow-2xl shadow-gray-200/50 overflow-hidden border border-gray-50">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th
                                class="px-4 md:px-8 py-7 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                Rank</th>
                            <th
                                class="px-4 md:px-8 py-7 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                Product & Category</th>
                            <th
                                class="px-4 md:px-8 py-7 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                Status & Stock</th>
                            <th
                                class="px-4 md:px-8 py-7 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                Price</th>
                            <th
                                class="px-4 md:px-8 py-7 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                Settings</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($items as $index => $item)
                            <tr class="tr-gradient transition-all duration-300 group">
                                <td class="px-4 md:px-8 py-6">
                                    <span class="text-xs font-black text-gray-300 group-hover:text-indigo-600">
                                        #{{ sprintf('%02d', ($items->currentPage() - 1) * $items->perPage() + $index + 1) }}
                                    </span>
                                </td>
                                <td class="px-4 md:px-8 py-6">
                                    <div class="flex items-center gap-6">
                                        <div class="relative shrink-0">
                                            @if ($item->gambar)
                                                <img src="{{ asset($item->gambar) }}"
                                                    class="w-16 h-16 rounded-[1.25rem] object-cover ring-4 ring-gray-50 group-hover:ring-indigo-100 transition-all shadow-lg">
                                            @else
                                                <div
                                                    class="w-16 h-16 bg-gray-100 rounded-[1.25rem] flex items-center justify-center text-gray-300">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                     </svg>
                                                 </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            {{-- Contoh highlight hasil pencarian --}}
                                            <h4
                                                class="font-black text-gray-900 group-hover:text-indigo-600 transition-colors uppercase tracking-tight truncate">
                                                {!! request('search')
                                                    ? str_replace(request('search'), '<span class="bg-yellow-200">' . request('search') . '</span>', $item->nama_barang)
                                                    : $item->nama_barang !!}
                                            </h4>
                                            <span
                                                class="text-[9px] font-black bg-indigo-50 text-indigo-500 px-2 py-0.5 rounded-md uppercase tracking-widest mt-1 inline-block">
                                                {{ $item->kategori ?? 'UNSORTED' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 md:px-8 py-6">
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="w-2 h-2 rounded-full {{ $item->stok > 10 ? 'bg-green-500' : 'bg-red-500 animate-pulse' }}"></span>
                                            <span class="text-sm font-black text-gray-700">{{ $item->stok }} <span
                                                    class="text-[10px] text-gray-400 uppercase font-bold">In
                                                    Stock</span></span>
                                        </div>
                                        <p class="text-[10px] font-bold text-gray-400 mt-1 italic">Sold by
                                            {{ $item->user?->name ?? 'System' }}</p>
                                    </div>
                                </td>
                                <td class="px-4 md:px-8 py-6">
                                    <div
                                        class="inline-flex px-4 py-2 rounded-2xl bg-gray-900 text-white font-black text-xs shadow-xl shadow-gray-200">
                                        IDR {{ number_format($item->harga, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-4 md:px-8 py-6">
                                    <div class="flex justify-center gap-3">
                                        @can('view', $item)
                                            <a href="{{ route('item-shop.show', $item->id) }}"
                                                class="p-3 bg-gray-50 text-gray-400 hover:bg-indigo-600 hover:text-white rounded-2xl transition-all"><svg
                                                    class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg></a>
                                        @endcan
                                        @can('update', $item)
                                            <a href="{{ route('item-shop.edit', $item->id) }}"
                                                class="p-3 bg-gray-50 text-gray-400 hover:bg-amber-500 hover:text-white rounded-2xl transition-all"><svg
                                                    class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg></a>
                                        @endcan
                                        @can('delete', $item)
                                            <button type="button" onclick="btnDelete('{{ $item->id }}')"
                                                class="p-3 bg-gray-50 text-gray-400 hover:bg-red-500 hover:text-white rounded-2xl transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                            <form id="delete-form-{{ $item->id }}"
                                                action="{{ route('item-shop.destroy', $item->id) }}" method="POST"
                                                class="hidden">
                                                @csrf @method('DELETE')
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-32 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-32 h-32 bg-indigo-50 rounded-full flex items-center justify-center mb-6">
                                            <svg class="w-16 h-16 text-indigo-200" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                        <h3 class="text-2xl font-black text-gray-900">EMPTY INVENTORY</h3>
                                        <p class="text-gray-400 mt-2 font-medium">Time to add some luxury products.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        @if ($items->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $items->links() }}
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                once: true,
                duration: 800
            });
        });

        // Function SweetAlert Hapus
        function btnDelete(id) {
            Swal.fire({
                title: 'DELETE ITEM?',
                text: "This action cannot be undone bolo!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#111827',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'YES, DELETE IT!',
                cancelButtonText: 'CANCEL',
                customClass: {
                    popup: 'rounded-[3rem]',
                    confirmButton: 'rounded-2xl px-6 py-3 font-black text-xs uppercase tracking-widest',
                    cancelButton: 'rounded-2xl px-6 py-3 font-black text-xs uppercase tracking-widest'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }

        const searchInput = document.getElementById('searchInput');
        const tableBody = document.querySelector('tbody');

        searchInput.addEventListener('keyup', function() {
            let value = this.value;

            // Fetch data ke controller yang sama tapi via AJAX
            fetch(`{{ route('item-shop.index') }}?search=${value}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    tableBody.innerHTML = ''; // Kosongkan tabel

                    if (data.items.length > 0) {
                        data.items.forEach((item, index) => {
                            // Render baris tabel baru
                            tableBody.innerHTML += `
                    <tr class="tr-gradient transition-all duration-300 group border-b border-gray-50">
                        <td class="px-8 py-6 text-xs font-black text-gray-300">#${index + 1}</td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-6">
                                <img src="/${item.gambar}" class="w-16 h-16 rounded-[1.25rem] object-cover shadow-lg">
                                <div>
                                    <h4 class="font-black text-gray-900 uppercase">${item.nama_barang}</h4>
                                    <span class="text-[9px] font-black bg-indigo-50 text-indigo-500 px-2 py-0.5 rounded-md uppercase tracking-widest">${item.kategori}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-black text-gray-700">${item.stok} In Stock</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="inline-flex px-4 py-2 rounded-2xl bg-gray-900 text-white font-black text-xs">
                                IDR ${new Intl.NumberFormat('id-ID').format(item.harga)}
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                             <span class="text-gray-400 text-[10px] font-black italic uppercase">Refresh to Edit</span>
                        </td>
                    </tr>
                `;
                        });
                    } else {
                        tableBody.innerHTML =
                            '<tr><td colspan="5" class="py-20 text-center font-black text-gray-400">DATA TIDAK DITEMUKAN BOLO</td></tr>';
                    }
                });
        });
    </script>
</x-admin-layout>
