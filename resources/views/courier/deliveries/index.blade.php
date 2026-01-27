<x-courier-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl font-black text-gray-900 mb-8 uppercase tracking-widest">
                Tugas <span class="text-indigo-600">Pengiriman</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse ($deliveries as $delivery)
                    <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Invoice</span>
                                <h3 class="text-lg font-black text-gray-900">{{ $delivery->invoice_number }}</h3>
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $delivery->status == 'completed' ? 'bg-indigo-100 text-indigo-600' : 'bg-blue-100 text-blue-600' }}">
                                    {{ $delivery->status }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Total Tagihan</span>
                                <p class="text-lg font-black text-indigo-600">Rp {{ number_format($delivery->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="space-y-4 mb-8">
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-gray-100 rounded-lg">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Penerima</span>
                                    <p class="text-sm font-bold text-gray-900">{{ $delivery->user->name }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-gray-100 rounded-lg">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Alamat Tujuan</span>
                                    <p class="text-sm font-bold text-gray-900 leading-relaxed">{{ $delivery->alamat }}</p>
                                    @if($delivery->user->latitude && $delivery->user->longitude)
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $delivery->user->latitude }},{{ $delivery->user->longitude }}" 
                                           target="_blank" class="text-indigo-600 text-[10px] font-black uppercase hover:underline mt-1 inline-block">Buka Maps</a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($delivery->status == 'shipped')
                            <form action="{{ route('courier.deliveries.complete', $delivery->id) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Konfirmasi bahwa barang sudah sampai bolo?')"
                                    class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-xs font-black uppercase tracking-[0.2em] hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                                    Konfirmasi Sampai
                                </button>
                            </form>
                        @else
                            <div class="w-full py-4 bg-gray-50 text-gray-400 rounded-2xl text-xs font-black uppercase tracking-[0.2em] text-center border border-dashed border-gray-200">
                                Selesai pada {{ $delivery->completed_at->format('d/m/Y H:i') }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full py-20 bg-white rounded-[2rem] border border-dashed border-gray-300 text-center">
                        <p class="text-gray-500 font-black uppercase tracking-widest text-xs">Belum ada tugas untukmu bolo</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $deliveries->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
