<x-admin-layout>
    {{-- Menambahkan script Midtrans Snap --}}
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl font-black text-gray-900 mb-8 uppercase tracking-widest">
                Order <span class="text-indigo-600">History</span>
            </h2>

            <div class="space-y-6">
                @forelse ($transactions as $trx)
                    <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm hover:shadow-md transition-all">
                        <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
                            <div>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Invoice</span>
                                <h3 class="text-lg font-black text-gray-900">{{ $trx->invoice_number }}</h3>
                                <p class="text-[10px] font-bold text-gray-400 mt-1">{{ $trx->created_at->translatedFormat('d F Y, H.i') }} WIB</p>
                            </div>
                            <div class="text-right">
                                {{-- Warna Status yang lebih lengkap --}}
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-600',
                                        'success' => 'bg-green-100 text-green-600',
                                        'failed' => 'bg-red-100 text-red-600',
                                        'expired' => 'bg-gray-100 text-gray-600',
                                    ];
                                    $currentClass = $statusClasses[$trx->status] ?? 'bg-blue-100 text-blue-600';
                                @endphp
                                <span class="inline-block px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $currentClass }}">
                                    {{ $trx->status }}
                                </span>
                            </div>
                        </div>

                        <div class="border-t border-b border-gray-50 py-6 my-6">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Items Purchased</p>
                            @if(is_array($trx->items_details))
                                @foreach ($trx->items_details as $item)
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-bold text-gray-700">
                                            <span class="text-indigo-600 font-black mr-1">{{ $item['quantity'] ?? 1 }}x</span>
                                            {{ $item['nama_barang'] ?? ($item['name'] ?? 'Item') }}
                                        </span>
                                        <span class="text-sm font-black text-gray-900">
                                            Rp {{ number_format($item['harga'] ?? ($item['price'] ?? 0), 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Amount</span>
                                <p class="text-xl font-black text-indigo-600 leading-none mt-1">
                                    Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="flex gap-3">
                                {{-- Tombol Bayar Sekarang (Muncul hanya jika pending) --}}
                                @if($trx->status == 'pending' && $trx->snap_token)
                                    <button onclick="payNow('{{ $trx->snap_token }}')" 
                                        class="px-6 py-3 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                                        Pay Now
                                    </button>
                                @endif
                                
                                <a href="#" class="px-6 py-3 bg-gray-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-700 transition-colors">
                                    Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 bg-white rounded-[2rem] border border-dashed border-gray-300">
                        <p class="text-gray-500 font-black uppercase tracking-widest text-xs">Belum ada transaksi</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Script untuk memicu Popup Midtrans --}}
    <script type="text/javascript">
        function payNow(snapToken) {
            window.snap.pay(snapToken, {
                onSuccess: function(result) {
                    window.location.reload();
                },
                onPending: function(result) {
                    window.location.reload();
                },
                onError: function(result) {
                    alert("Pembayaran gagal!");
                }
            });
        }
    </script>
</x-admin-layout>