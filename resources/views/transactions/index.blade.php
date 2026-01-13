<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl font-black text-gray-900 mb-8 uppercase tracking-widest">Order <span class="text-indigo-600">History</span></h2>
            
            <div class="space-y-6">
                @foreach($transactions as $trx)
                <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm hover:shadow-md transition-all">
                    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
                        <div>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Invoice</span>
                            <h3 class="text-lg font-black text-gray-900">{{ $trx->invoice_number }}</h3>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $trx->status == 'pending' ? 'bg-yellow-100 text-yellow-600' : 'bg-green-100 text-green-600' }}">
                                {{ $trx->status }}
                            </span>
                        </div>
                    </div>

                    <div class="border-t border-b border-gray-50 py-6 my-6">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Items Purchased</p>
                        @foreach($trx->items_details as $item)
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-bold text-gray-700">{{ $item['nama_barang'] }}</span>
                            <span class="text-sm font-black text-gray-900">Rp {{ number_format($item['harga'], 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Amount</span>
                            <p class="text-xl font-black text-indigo-600 leading-none mt-1">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</p>
                        </div>
                        <a href="#" class="px-6 py-3 bg-gray-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-colors">Details</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>