<x-admin-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">
                Ulasan <span class="text-emerald-600">Saya</span>
            </h2>

            <div class="grid gap-6">
                @forelse ($reviews as $review)
                    <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                        <div class="flex gap-6">
                            <div class="w-24 h-24 bg-slate-50 rounded-2xl flex-shrink-0 overflow-hidden border border-slate-100">
                                @if($review->itemShop && $review->itemShop->gambar)
                                    <img src="{{ asset('storage/' . $review->itemShop->gambar) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">{{ $review->itemShop->nama_barang ?? 'Produk dihapus' }}</h3>
                                        <div class="flex items-center gap-1 mt-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="mt-4 text-gray-600 text-sm font-medium">"{{ $review->comment }}"</p>
                                
                                @if($review->photo)
                                    <div class="mt-4">
                                        <img src="{{ asset($review->photo) }}" class="w-32 h-32 object-cover rounded-xl border border-gray-100">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 bg-white rounded-2xl border border-dashed border-gray-300">
                        <p class="text-gray-500 font-semibold text-xs">Belum ada ulasan yang kamu berikan bolo</p>
                        <a href="{{ route('transactions.index') }}" class="inline-block mt-4 bg-emerald-600 text-white px-6 py-2 rounded-xl text-xs font-semibold">Lihat Riwayat Belanja</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>

