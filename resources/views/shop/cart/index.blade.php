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
                                        'shipped' => 'bg-blue-100 text-blue-600',
                                        'delivered' => 'bg-purple-100 text-purple-600',
                                        'completed' => 'bg-indigo-100 text-indigo-600',
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

                        {{-- Resi Display --}}
                        @if($trx->resi)
                            <div class="mb-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Tracking Number (Resi)</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-black text-gray-900">{{ $trx->resi }}</span>
                                    <span class="text-[10px] bg-indigo-100 text-indigo-600 px-2 py-0.5 rounded-md font-bold uppercase">{{ $trx->courier_name }}</span>
                                </div>
                            </div>
                        @endif

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
                                {{-- Tombol Konfirmasi Barang Diterima --}}
                                @if(in_array($trx->status, ['shipped', 'delivered']))
                                    <form action="{{ route('transactions.confirm', $trx->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Apakah barang sudah diterima dengan baik bolo?')"
                                            class="px-6 py-3 {{ $trx->status == 'delivered' ? 'bg-green-600 animate-pulse' : 'bg-gray-800' }} text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-lg {{ $trx->status == 'delivered' ? 'shadow-green-200' : 'shadow-gray-200' }}">
                                            @if($trx->status == 'delivered')
                                                ✅ Pesanan Diterima
                                            @else
                                                Konfirmasi Diterima
                                            @endif
                                        </button>
                                    </form>
                                @endif

                                {{-- Tombol Bayar Sekarang (Muncul hanya jika pending) --}}
                                @if($trx->status == 'pending' && $trx->snap_token)
                                    @php
                                        $expiryTime = $trx->created_at->addMinutes(15);
                                        $isExpired = now()->greaterThan($expiryTime);
                                        $remainingSeconds = now()->diffInSeconds($expiryTime, false);
                                    @endphp

                                    @if(!$isExpired)
                                        <div class="flex flex-col items-end gap-2" x-data="{ 
                                            timeLeft: {{ $remainingSeconds }},
                                            timer: null,
                                            formatTime(seconds) {
                                                const mins = Math.floor(seconds / 60);
                                                const secs = seconds % 60;
                                                return `${mins}:${secs.toString().padStart(2, '0')}`;
                                            }
                                        }" x-init="timer = setInterval(() => { if(timeLeft > 0) timeLeft--; else { clearInterval(timer); window.location.reload(); } }, 1000)">
                                            <button onclick="payNow('{{ $trx->snap_token }}')" 
                                                class="px-6 py-3 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                                                Pay Now
                                            </button>
                                            <span class="text-[9px] font-black text-rose-500 uppercase tracking-widest animate-pulse">
                                                Sisa Waktu: <span x-text="formatTime(timeLeft)"></span>
                                            </span>
                                        </div>
                                    @else
                                        <span class="px-6 py-3 bg-gray-100 text-gray-400 rounded-xl text-[10px] font-black uppercase tracking-widest cursor-not-allowed">
                                            Expired
                                        </span>
                                    @endif
                                @endif

                                {{-- Tombol Beri Ulasan (Jika Selesai) --}}
                                @if($trx->status == 'completed')
                                    <button onclick="showReviewModal('{{ $trx->invoice_number }}', {{ json_encode($trx->items_details) }})" 
                                        class="px-6 py-3 bg-yellow-400 text-gray-900 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-yellow-500 transition-colors shadow-lg shadow-yellow-100 flex items-center gap-2">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        Beri Ulasan
                                    </button>
                                @endif
                                
                                <a href="{{ route('transactions.show', $trx->id) }}" class="px-6 py-3 bg-gray-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-700 transition-colors">
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
        function showReviewModal(invoice, items) {
            let itemsHtml = items.map(item => `
                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl mb-3 border border-slate-100">
                    <div class="flex-1 text-left">
                        <p class="text-xs font-black text-gray-900">${item.nama_barang || item.name}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Beri rating produk ini</p>
                    </div>
                    <a href="/shop/items/${item.id}" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition-all">
                        Rating Sekarang
                    </a>
                </div>
            `).join('');

            Swal.fire({
                title: '<span class="text-xl font-black uppercase tracking-widest">Beri Ulasan Bolo!</span>',
                html: `
                    <div class="mt-4">
                        <p class="text-xs text-gray-500 mb-6 font-bold uppercase">Pilih produk dari invoice <span class="text-indigo-600">${invoice}</span> untuk diulas:</p>
                        <div class="max-h-[300px] overflow-y-auto px-1">
                            ${itemsHtml}
                        </div>
                    </div>
                `,
                showConfirmButton: false,
                showCloseButton: true,
                customClass: {
                    popup: 'rounded-[2.5rem]',
                }
            });
        }

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