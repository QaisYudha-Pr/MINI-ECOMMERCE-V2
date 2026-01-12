<x-app-layout>
    <style>
        /* Custom Soft Glassmorphism */
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        .stat-icon-shadow {
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
        }

        /* Smooth Transition for Table Rows */
        .tr-gradient:hover {
            background: linear-gradient(90deg, rgba(79, 70, 229, 0.05) 0%, rgba(255, 255, 255, 0) 100%);
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 overflow-hidden">
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12">
            <div data-aos="fade-right" data-aos-duration="1000">
                <span class="inline-block px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-wider mb-3">
                    Inventory System
                </span>
                <h1 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight">
                    Kelola <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Produk</span>
                </h1>
                <p class="text-gray-500 text-lg mt-2 font-medium">Monitoring stok dan performa produk secara real-time.</p>
            </div>
            
            @can('create', App\Models\ItemShop::class)
                <div data-aos="fade-left" data-aos-duration="1000">
                    <a href="{{ route('item-shop.create') }}"
                        class="group relative inline-flex items-center justify-center px-8 py-4 font-bold text-white transition-all duration-200 bg-indigo-600 font-pj rounded-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 hover:bg-indigo-700 shadow-xl shadow-indigo-200 active:scale-95">
                        <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Produk Baru
                    </a>
                </div>
            @endcan
        </div>

        {{-- STATS CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div data-aos="fade-up" data-aos-delay="100" class="glass-card p-8 rounded-[2.5rem] shadow-sm hover:shadow-xl transition-all duration-500 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Total Produk</p>
                        <h3 class="text-4xl font-black text-gray-900 mt-1 group-hover:text-indigo-600 transition-colors">{{ $items->total() }}</h3>
                    </div>
                    <div class="bg-indigo-50 p-4 rounded-2xl text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500 stat-icon-shadow">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </div>
                </div>
            </div>

            <div data-aos="fade-up" data-aos-delay="200" class="glass-card p-8 rounded-[2.5rem] shadow-sm hover:shadow-xl transition-all duration-500 group border-t-4 border-t-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Halaman</p>
                        <h3 class="text-4xl font-black text-gray-900 mt-1 group-hover:text-purple-600 transition-colors">{{ $items->currentPage() }}</h3>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-2xl text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-all duration-500 stat-icon-shadow">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
            </div>

            <div data-aos="fade-up" data-aos-delay="300" class="glass-card p-8 rounded-[2.5rem] shadow-sm hover:shadow-xl transition-all duration-500 group border-t-4 border-t-emerald-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Item Per Hal</p>
                        <h3 class="text-4xl font-black text-gray-900 mt-1 group-hover:text-emerald-600 transition-colors">{{ $items->perPage() }}</h3>
                    </div>
                    <div class="bg-emerald-50 p-4 rounded-2xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 stat-icon-shadow">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLE AREA --}}
        <div data-aos="zoom-in-up" data-aos-duration="1000" class="bg-white rounded-[2.5rem] shadow-2xl shadow-gray-200/50 overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50/80 border-b border-gray-100">
                            <th class="px-8 py-6 text-left text-xs font-black text-gray-400 uppercase tracking-[0.2em]">ID</th>
                            <th class="px-8 py-6 text-left text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Produk Detail</th>
                            <th class="hidden sm:table-cell px-8 py-6 text-left text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Price Tag</th>
                            <th class="hidden lg:table-cell px-8 py-6 text-left text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Merchant</th>
                            <th class="px-8 py-6 text-center text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($items as $index => $item)
                            <tr class="tr-gradient transition-all duration-300 group">
                                <td class="px-8 py-6">
                                    <span class="text-sm font-black text-gray-300 group-hover:text-indigo-600 transition-colors">
                                        #{{ ($items->currentPage() - 1) * $items->perPage() + $index + 1 }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-5">
                                        <div class="relative flex-shrink-0">
                                            @if($item->gambar)
                                                <img src="{{ asset($item->gambar) }}" class="w-16 h-16 rounded-2xl object-cover ring-4 ring-gray-50 group-hover:ring-indigo-50 transition-all shadow-md">
                                            @else
                                                <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center text-gray-400">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors leading-tight">{{ $item->nama_barang }}</h4>
                                            <p class="text-xs font-medium text-gray-400 mt-1 line-clamp-1">{{ $item->deskripsi ?? 'No Description' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden sm:table-cell px-8 py-6">
                                    <div class="inline-flex items-center px-4 py-2 rounded-xl bg-gray-50 text-indigo-700 font-black group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="hidden lg:table-cell px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-[10px] text-white font-bold ring-2 ring-white shadow-sm">
                                            {{ strtoupper(substr($item->user?->name ?? '?', 0, 1)) }}
                                        </div>
                                        <span class="text-sm font-bold text-gray-600 italic">@ {{ $item->user?->name ?? 'System' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 translate-x-2 group-hover:translate-x-0 transition-transform">
                                        @can('view', $item)
                                            <a href="{{ route('item-shop.show', $item->id) }}" class="p-3 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl transition-all shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg></a>
                                        @endcan
                                        @can('update', $item)
                                            <a href="{{ route('item-shop.edit', $item->id) }}" class="p-3 bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white rounded-xl transition-all shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></a>
                                        @endcan
                                        @can('delete', $item)
                                            <form method="POST" action="{{ route('item-shop.destroy', $item->id) }}" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('Hapus produk ini?')" class="p-3 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl transition-all shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-32 text-center">
                                    <div data-aos="zoom-in" class="flex flex-col items-center">
                                        <div class="w-32 h-32 bg-indigo-50 rounded-full flex items-center justify-center mb-6 animate-pulse">
                                            <svg class="w-16 h-16 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                        </div>
                                        <h3 class="text-2xl font-black text-gray-800">Inventory Kosong</h3>
                                        <p class="text-gray-400 mt-2 mb-8">Wah, belum ada produk yang kamu jual nih.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        @if($items->hasPages())
            <div class="mt-12 flex justify-center" data-aos="fade-up">
                <div class="bg-white px-4 py-2 rounded-2xl shadow-lg border border-gray-100">
                    {{ $items->links('pagination::tailwind') }}
                </div>
            </div>
        @endif
    </div>
</x-app-layout>

<script>
    // Pastikan AOS terinisialisasi
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            once: true, // Animasi hanya jalan sekali saat scroll
            mirror: false
        });
    });

    console.log('Item Shop Admin/User Page Loaded');
</script>