<x-admin-layout>
    {{-- Menambahkan script Midtrans Snap --}}
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-6">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-600 font-bold text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-center gap-3 text-red-600 font-bold text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Back Button --}}
            <a href="{{ route('transactions.index') }}" class="inline-flex items-center gap-2 text-indigo-600 font-black text-[10px] uppercase tracking-widest mb-8 hover:gap-3 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Riwayat
            </a>

            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white overflow-hidden">
                {{-- Detail Header --}}
                <div class="p-10 border-b border-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] block mb-1">Detail Transaksi</span>
                        <h1 class="text-3xl font-black text-gray-900 tracking-tighter">{{ $transaction->invoice_number }}</h1>
                        <p class="text-xs font-bold text-slate-400 mt-2">{{ $transaction->created_at->translatedFormat('d F Y, H.i') }} WIB</p>
                    </div>
                    
                    @php
                        $statusClasses = [
                            'pending' => 'bg-yellow-100 text-yellow-600',
                            'success' => 'bg-green-100 text-green-600',
                            'failed' => 'bg-red-100 text-red-600',
                            'expired' => 'bg-gray-100 text-gray-600',
                            'waiting_confirmation' => 'bg-orange-100 text-orange-600',
                        ];
                        $statusLabels = [
                            'pending' => 'Menunggu Pembayaran',
                            'success' => 'Berhasil',
                            'failed' => 'Gagal',
                            'expired' => 'Kedaluwarsa',
                            'waiting_confirmation' => 'Menunggu Konfirmasi COD',
                        ];
                        $currentClass = $statusClasses[$transaction->status] ?? 'bg-blue-100 text-blue-600';
                        $currentLabel = $statusLabels[$transaction->status] ?? $transaction->status;
                    @endphp
                    <div class="px-6 py-2 rounded-2xl {{ $currentClass }} text-xs font-black uppercase tracking-widest">
                        {{ $currentLabel }}
                    </div>
                </div>

                {{-- Order Items --}}
                <div class="p-10 border-b border-gray-50">
                    <h2 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6 flex items-center gap-3">
                        <span class="w-8 h-1 bg-indigo-600 rounded-full"></span>
                        Produk yang Dibeli
                    </h2>
                    
                    <div class="space-y-6">
                        @if(is_array($transaction->items_details))
                            @foreach ($transaction->items_details as $item)
                                <div class="flex items-center gap-6 group">
                                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex-shrink-0 border border-slate-100 overflow-hidden">
                                        @if(isset($item['gambar']))
                                            <img src="{{ asset($item['gambar']) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-black text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $item['nama_barang'] ?? ($item['name'] ?? 'Item') }}</h3>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                                            {{ $item['quantity'] ?? 1 }}x @ Rp{{ number_format($item['harga'] ?? ($item['price'] ?? 0), 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-black text-slate-900">Rp{{ number_format(($item['harga'] ?? ($item['price'] ?? 0)) * ($item['quantity'] ?? 1), 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Address & Payment Detail --}}
                <div class="grid md:grid-cols-2">
                    <div class="p-10 border-r border-gray-50">
                        <h2 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6">Alamat Pengiriman</h2>
                        <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-2xl">
                            <svg class="w-5 h-5 text-slate-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <p class="text-xs font-bold text-slate-600 leading-relaxed">
                                {{ $transaction->alamat ?: 'Tidak ada detail alamat' }}
                            </p>
                        </div>
                    </div>

                    <div class="p-10">
                        <h2 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6">Metode Pembayaran</h2>
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 {{ $transaction->payment_method == 'midtrans' ? 'bg-indigo-50 text-indigo-600' : 'bg-orange-50 text-orange-600' }} rounded-2xl flex items-center justify-center shadow-sm">
                                @if($transaction->payment_method == 'midtrans')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-black text-slate-800 uppercase">{{ $transaction->payment_method == 'midtrans' ? 'Midtrans (Transfer/VA)' : 'Cash on Delivery (COD)' }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Metode Aktif</p>
                            </div>
                        </div>

                        {{-- Change Payment Button --}}
                        @if($transaction->status == 'pending' || $transaction->status == 'waiting_confirmation')
                            <button onclick="toggleChangePayment()" class="text-[10px] font-black text-indigo-600 hover:text-indigo-800 uppercase tracking-widest underline decoration-wavy transition-all">
                                Gant Metode Pembayaran?
                            </button>

                            <div id="change-payment-form" class="mt-4 hidden animate-in fade-in slide-in-from-top-2 duration-300">
                                <form action="{{ route('transactions.changePayment', $transaction->id) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <select name="payment_method" class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl text-xs font-black uppercase tracking-widest p-3 outline-none focus:border-indigo-600 transition-all">
                                        <option value="midtrans" {{ $transaction->payment_method == 'midtrans' ? 'selected' : '' }}>Midtrans (Transfer/VA)</option>
                                        <option value="cod" {{ $transaction->payment_method == 'cod' ? 'selected' : '' }}>COD (Bayar Ditempat)</option>
                                    </select>
                                    <button type="submit" class="w-full bg-slate-900 text-white py-3 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-black transition-all">
                                        Simpan Perubahan
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Footer Summary --}}
                <div class="p-10 bg-slate-900 text-white">
                    <div class="max-w-xs ml-auto space-y-4">
                        <div class="flex justify-between text-xs font-bold text-slate-400 uppercase tracking-widest">
                            <span>Subtotal</span>
                            <span>Rp{{ number_format($transaction->total_price - ($transaction->shipping_fee ?? 0) - 2500, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs font-bold text-slate-400 uppercase tracking-widest">
                            <span>Ongkir</span>
                            <span>Rp{{ number_format($transaction->shipping_fee ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs font-bold text-slate-400 uppercase tracking-widest">
                            <span>Biaya Layanan</span>
                            <span>Rp2.500</span>
                        </div>
                        <div class="pt-4 border-t border-white/10 flex justify-between items-end">
                            <div>
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Total Bayar</p>
                                <p class="text-3xl font-black tracking-tighter">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        {{-- Action Button --}}
                        @if($transaction->status == 'pending' && $transaction->snap_token)
                            <button onclick="payNow('{{ $transaction->snap_token }}')" 
                                class="w-full bg-indigo-600 hover:bg-indigo-500 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-indigo-500/20 transition-all active:scale-95 mt-6">
                                Bayar Sekarang
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // Auto pay if redirected from change payment
        @if(session('auto_pay'))
            document.addEventListener('DOMContentLoaded', function() {
                // Beri jeda sedikit agar library Snap.js benar-benar siap
                setTimeout(() => {
                    payNow('{{ session('auto_pay') }}');
                }, 500);
            });
        @endif

        function toggleChangePayment() {
            const form = document.getElementById('change-payment-form');
            form.classList.toggle('hidden');
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
                    Swal.fire('Gagal', 'Pembayaran gagal!', 'error');
                }
            });
        }
    </script>
</x-admin-layout>