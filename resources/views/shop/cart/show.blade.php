<x-admin-layout>
    {{-- Menambahkan script Midtrans Snap --}}
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <div class="max-w-4xl mx-auto py-10">
        
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
            <a href="{{ auth()->user()->hasRole('seller') || auth()->user()->hasRole('admin') ? route('admin.transactions.index') : route('transactions.index') }}" class="inline-flex items-center gap-2 text-emerald-600 font-bold text-xs mb-8 hover:gap-3 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Riwayat
            </a>

            <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-white overflow-hidden">
                {{-- Detail Header --}}
                <div class="p-10 border-b border-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <span class="text-xs font-medium text-gray-400 block mb-1">Detail Transaksi</span>
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tighter">{{ $transaction->invoice_number }}</h1>
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
                    <div class="px-6 py-2 rounded-2xl {{ $currentClass }} text-xs font-semibold">
                        {{ $currentLabel }}
                    </div>
                </div>

                {{-- Order Items --}}
                <div class="p-10 border-b border-gray-50">
                    <h2 class="text-sm font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <span class="w-8 h-1 bg-emerald-600 rounded-full"></span>
                        Produk yang Dibeli
                    </h2>
                    
                    <div class="space-y-6">
                        @php $seller = $transaction->seller; @endphp
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
                                        <h3 class="text-sm font-bold text-slate-800 group-hover:text-emerald-600 transition-colors">{{ $item['nama_barang'] ?? ($item['name'] ?? 'Item') }}</h3>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[11px] font-bold text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">
                                                Toko: {{ $seller->nama_toko ?? $seller->name }}
                                            </span>
                                            <span class="text-[11px] font-bold text-slate-400">|</span>
                                            <span class="text-xs font-bold text-slate-400">
                                                {{ $item['quantity'] ?? 1 }}x @ Rp{{ number_format($item['harga'] ?? ($item['price'] ?? 0), 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-right flex flex-col items-end gap-2">
                                        <p class="text-sm font-bold text-slate-900">Rp{{ number_format(($item['harga'] ?? ($item['price'] ?? 0)) * ($item['quantity'] ?? 1), 0, ',', '.') }}</p>
                                        
                                        @if($transaction->status === 'completed')
                                            @php 
                                                $hasProductReview = \App\Models\Review::where('item_shop_id', $item['id'] ?? $item['item_id'])
                                                    ->where('user_id', auth()->id())
                                                    ->exists();
                                            @endphp
                                            @if(!$hasProductReview)
                                                <button onclick="openProductRateModal({{ $item['id'] ?? $item['item_id'] }}, '{{ $item['nama_barang'] ?? $item['name'] }}', '{{ isset($item['gambar']) ? asset($item['gambar']) : asset('image/default-product.jpg') }}')" 
                                                    class="px-4 py-1.5 bg-amber-500 text-white rounded-lg text-[10px] font-bold uppercase tracking-wider hover:bg-amber-600 transition-all shadow-lg shadow-amber-100">
                                                    Beri Ulasan
                                                </button>
                                            @else
                                                <span class="px-4 py-1.5 bg-slate-50 text-slate-400 rounded-lg text-[10px] font-bold uppercase border border-slate-100">
                                                    Sudah Diulas
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Address & Payment Detail --}}
                <div class="grid md:grid-cols-2">
                    <div class="p-10 border-r border-gray-50">
                        <h2 class="text-sm font-bold text-gray-900 mb-6">Alamat Pengiriman</h2>
                        <div class="space-y-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="flex items-start gap-4">
                                <svg class="w-5 h-5 text-emerald-500 mt-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <div>
                                    <p class="text-[11px] font-medium text-slate-400 mb-1">Alamat Peta</p>
                                    <p class="text-[11px] font-bold text-slate-600 leading-relaxed">
                                        {{ $transaction->alamat ?: 'Tidak ada detail alamat' }}
                                    </p>
                                </div>
                            </div>

                            @if($transaction->catatan_alamat)
                            <div class="flex items-start gap-4 pt-4 border-t border-slate-200/50">
                                <svg class="w-5 h-5 text-rose-500 mt-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <div>
                                    <p class="text-[11px] font-bold text-rose-400 mb-1">Detail & Patokan (Manual)</p>
                                    <p class="text-xs font-bold text-slate-900 leading-relaxed animate-pulse">
                                        "{{ $transaction->catatan_alamat }}"
                                    </p>
                                </div>
                            </div>
                            @endif

                            {{-- Kurir & Layanan --}}
                            @if($transaction->courier_name)
                            <div class="flex items-start gap-4 pt-4 border-t border-slate-200/50">
                                <svg class="w-5 h-5 text-blue-500 mt-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
                                <div>
                                    <p class="text-[11px] font-bold text-blue-400 mb-0.5">Layanan Pengiriman</p>
                                    <p class="text-xs font-bold text-slate-900 uppercase">
                                        {{ $transaction->courier_name }} - {{ $transaction->courier_service }}
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>

                        @if($transaction->delivery_proof)
                        <div class="mt-8">
                            <h2 class="text-sm font-bold text-gray-900 mb-4">Bukti Pengiriman</h2>
                            <div class="relative w-full h-48 rounded-2xl overflow-hidden group border border-slate-100">
                                <img src="{{ asset($transaction->delivery_proof) }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <a href="{{ asset($transaction->delivery_proof) }}" target="_blank" class="px-6 py-2 bg-white text-slate-900 rounded-full text-xs font-semibold">Perbesar Foto</a>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-400 font-bold mt-2">* Foto ini diambil oleh kurir saat barang sampai bolo.</p>
                        </div>
                        @endif

                        {{-- Store Information --}}
                        <div class="mt-8 pt-8 border-t border-slate-50">
                            <h2 class="text-sm font-bold text-gray-900 mb-4">Informasi Penjual</h2>
                            @php $seller = $transaction->seller; @endphp
                            @if($seller)
                            <div class="flex items-center gap-4 p-4 bg-emerald-50/50 rounded-2xl border border-emerald-100/50 group">
                                <div class="w-12 h-12 rounded-xl border-2 border-white shadow-sm overflow-hidden bg-white">
                                    <img src="{{ $seller->avatar ? asset($seller->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($seller->nama_toko ?? $seller->name).'&background=059669&color=fff' }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-xs font-bold text-slate-900">{{ $seller->nama_toko ?? $seller->name }}</h4>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        @if($seller->seller_rating)
                                            <div class="flex items-center gap-1">
                                                <svg class="w-3 h-3 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                <span class="text-xs font-bold text-amber-600">{{ number_format($seller->seller_rating, 1) }}</span>
                                                <span class="text-[11px] text-slate-400">({{ $seller->seller_rating_count ?? 0 }})</span>
                                            </div>
                                            @if($seller->is_top_seller)
                                                <span class="text-xs font-bold text-white bg-amber-500 px-1.5 py-0.5 rounded">Top Seller</span>
                                            @endif
                                        @else
                                            <p class="text-[11px] font-bold text-emerald-500">{{ $seller->alamat ? Str::limit($seller->alamat, 40) : 'Lokasi tidak diset' }}</p>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('chat.start', $seller) }}" class="p-3 bg-white text-emerald-500 rounded-xl hover:bg-emerald-500 hover:text-white transition-all shadow-sm border border-emerald-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                </a>
                            </div>
                            @endif
                        </div>

                        {{-- Seller Review Section (Only for completed transactions, buyer only) --}}
                        @if($transaction->status === 'completed' && $transaction->user_id === auth()->id())
                            @php $existingSellerReview = \App\Models\SellerReview::where('transaction_id', $transaction->id)->where('buyer_id', auth()->id())->first(); @endphp
                            <div class="mt-8 pt-8 border-t border-slate-50">
                                <h2 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-3">
                                    <span class="w-8 h-1 bg-amber-400 rounded-full"></span>
                                    Rating Seller
                                </h2>

                                @if($existingSellerReview)
                                    {{-- Already reviewed - show the review --}}
                                    <div class="p-6 bg-amber-50/50 rounded-2xl border border-amber-100/50">
                                        <div class="flex items-center gap-1 mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-5 h-5 {{ $i <= $existingSellerReview->rating ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endfor
                                            <span class="text-xs font-bold text-amber-600 ml-2">{{ $existingSellerReview->rating }}/5</span>
                                        </div>
                                        @if($existingSellerReview->comment)
                                            <p class="text-xs text-slate-600 font-bold mt-2">"{{ $existingSellerReview->comment }}"</p>
                                        @endif
                                        <p class="text-[11px] text-slate-400 font-bold mt-3">Dikirim {{ $existingSellerReview->created_at->diffForHumans() }}</p>
                                    </div>
                                @else
                                    {{-- Review form --}}
                                    <form action="{{ route('seller-reviews.store', $transaction) }}" method="POST" class="p-6 bg-slate-50 rounded-2xl border border-slate-100" x-data="{ rating: 0, labels: ['', 'Sangat Buruk', 'Buruk', 'Cukup', 'Baik', 'Sangat Baik'] }">
                                        @csrf
                                        <input type="hidden" name="rating" x-model="rating">
                                        
                                        <p class="text-xs font-bold text-slate-500 mb-4">Bagaimana pengalamanmu dengan {{ $seller->nama_toko ?? $seller->name }}?</p>
                                        
                                        <div class="flex items-center gap-2 mb-2">
                                            <template x-for="star in 5" :key="star">
                                                <button type="button" @click="rating = star" class="transition-all hover:scale-125 focus:outline-none">
                                                    <svg class="w-9 h-9" :class="star <= rating ? 'text-amber-400' : 'text-gray-200'" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                </button>
                                            </template>
                                            <span x-show="rating > 0" x-text="labels[rating]" class="text-xs font-semibold text-emerald-600 ml-2"></span>
                                        </div>

                                        <textarea name="comment" rows="3" maxlength="500" placeholder="Tulis komentar untuk seller (opsional)..." 
                                            class="w-full bg-white border-2 border-slate-100 rounded-xl p-4 text-xs font-bold text-gray-600 focus:border-emerald-300 focus:ring-0 transition-all resize-none mt-4"></textarea>
                                        
                                        <button type="submit" :disabled="rating === 0" 
                                            class="mt-4 px-8 py-3.5 bg-emerald-600 text-white rounded-xl text-xs font-semibold hover:bg-slate-900 transition-all disabled:opacity-40 disabled:cursor-not-allowed shadow-lg shadow-emerald-100">
                                            Kirim Rating Seller
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="p-10">
                        <h2 class="text-sm font-bold text-gray-900 mb-6">Metode Pembayaran</h2>
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 {{ $transaction->payment_method == 'midtrans' ? 'bg-emerald-50 text-emerald-600' : 'bg-orange-50 text-orange-600' }} rounded-2xl flex items-center justify-center shadow-sm">
                                @if($transaction->payment_method == 'midtrans')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800 uppercase">{{ $transaction->payment_method == 'midtrans' ? 'Smart Pay (Otomatis)' : 'Cash on Delivery (COD)' }}</p>
                                <p class="text-xs font-bold text-slate-400 mt-0.5">Metode Aktif</p>
                            </div>
                        </div>

                        {{-- Change Payment Button --}}
                        @if($transaction->status == 'pending' || $transaction->status == 'waiting_confirmation')
                            <button onclick="toggleChangePayment()" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800 underline decoration-wavy transition-all">
                                Gant Metode Pembayaran?
                            </button>

                            <div id="change-payment-form" class="mt-4 hidden animate-in fade-in slide-in-from-top-2 duration-300">
                                <form action="{{ route('transactions.changePayment', $transaction->id) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <select name="payment_method" class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl text-xs font-semibold p-3 outline-none focus:border-emerald-600 transition-all">
                                        <option value="midtrans" {{ $transaction->payment_method == 'midtrans' ? 'selected' : '' }}>Smart Pay (Otomatis)</option>
                                        <option value="cod" {{ $transaction->payment_method == 'cod' ? 'selected' : '' }}>COD (Bayar Ditempat)</option>
                                    </select>
                                    <button type="submit" class="w-full bg-slate-900 text-white py-3 rounded-xl text-xs font-semibold hover:bg-black transition-all">
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
                        <div class="flex justify-between text-xs font-bold text-slate-400">
                            <span>Subtotal</span>
                            <span>Rp{{ number_format($transaction->total_price - ($transaction->shipping_fee ?? 0) - ($transaction->admin_fee ?? 0), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs font-bold text-slate-400">
                            <span>Ongkir</span>
                            <span>Rp{{ number_format($transaction->shipping_fee ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs font-bold text-slate-400">
                            <span>Biaya Layanan</span>
                            <span>Rp{{ number_format($transaction->admin_fee ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="pt-4 border-t border-white/10 flex justify-between items-end">
                            <div>
                                <p class="text-xs font-bold text-emerald-400 mb-1">Total Bayar</p>
                                <p class="text-3xl font-bold tracking-tighter">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        {{-- Action Button --}}
                        @if($transaction->status == 'pending' && $transaction->snap_token)
                            <button onclick="payNow('{{ $transaction->snap_token }}')" 
                                class="w-full bg-emerald-600 hover:bg-emerald-500 py-4 rounded-2xl font-bold text-xs shadow-xl shadow-emerald-500/20 transition-all active:scale-95 mt-6">
                                Bayar Sekarang
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // 1. AUTO REFRESH STATUS (Jika pending)
        @if($transaction->status == 'pending')
            let checkCount = 0;
            const statusInterval = setInterval(async () => {
                checkCount++;
                if(checkCount > 30) clearInterval(statusInterval); // Stop after 5 mins

                try {
                    const res = await fetch("{{ route('transactions.show', $transaction->id) }}", {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await res.json();
                    if(data.status !== 'pending') {
                        clearInterval(statusInterval);
                        window.location.reload();
                    }
                } catch(e) {}
            }, 10000); // Cek tiap 10 detik
        @endif

        // Auto pay if redirected from change payment
        @if(session('auto_pay'))
            document.addEventListener('DOMContentLoaded', function() {
                // Beri jeda sedikit agar library Snap.js benar-benar siap
                setTimeout(() => {
                    payNow('{{ session('auto_pay') }}');
                }, 500);
            });
        @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        function openProductRateModal(itemId, itemName, itemImage) {
            let selectedRating = 0;

            function productStarHtml(count) {
                let html = '';
                for (let i = 1; i <= 5; i++) {
                    html += `<button type="button" onclick="setProductRating(${i})" class="product-star transition-all hover:scale-125 focus:outline-none" data-star="${i}">
                        <svg class="w-10 h-10 ${i <= count ? 'text-amber-400' : 'text-gray-200'}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </button>`;
                }
                return html;
            }

            const ratingLabels = ['', 'Mengecewakan', 'Kurang Baik', 'Biasa Saja', 'Sangat Baik', 'Sempurna Bolo!'];

            Swal.fire({
                html: `
                    <div class="text-center">
                        <img src="${itemImage}" class="w-20 h-20 rounded-2xl mx-auto border-2 border-emerald-100 shadow-sm object-cover bg-white">
                        <h3 class="text-md font-bold text-gray-900 mt-4 line-clamp-2 uppercase tracking-tighter">${itemName}</h3>
                        <p class="text-[11px] text-gray-400 font-bold mt-1">Gimana produknya bolo? Kasih rating yuk!</p>
                        
                        <form id="product-review-form" action="/item-shop/${itemId}/review" method="POST" class="mt-6 text-left">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]')?.content || '{{ csrf_token() }}'}">
                            <input type="hidden" name="rating" id="product-rating-input" value="0">
                            
                            <div class="flex items-center justify-center gap-2 mb-2" id="product-stars-container">
                                ${productStarHtml(0)}
                            </div>
                            <p id="product-rating-label" class="text-center text-xs font-semibold text-emerald-600 h-4 mb-4"></p>

                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-2 block">Komentar Bolo</label>
                            <textarea name="comment" rows="3" required placeholder="Tulis review jujurmu di sini bolo..." 
                                class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 text-xs font-bold text-gray-600 focus:border-emerald-300 focus:ring-0 transition-all resize-none"></textarea>
                            
                            <button type="submit" id="product-review-submit" disabled
                                class="w-full mt-4 bg-emerald-600 text-white py-4 rounded-xl text-xs font-bold hover:bg-slate-900 transition-all disabled:opacity-40 disabled:cursor-not-allowed shadow-lg shadow-emerald-100 uppercase tracking-widest">
                                Submit Review Produk
                            </button>
                        </form>
                    </div>
                `,
                showConfirmButton: false,
                showCloseButton: true,
                customClass: {
                    popup: 'rounded-2xl border-none shadow-lg',
                }
            });

            window.setProductRating = function(rating) {
                document.getElementById('product-rating-input').value = rating;
                document.getElementById('product-review-submit').disabled = false;
                document.getElementById('product-rating-label').textContent = ratingLabels[rating];

                document.querySelectorAll('.product-star svg').forEach((svg, idx) => {
                    if (idx < rating) {
                        svg.classList.remove('text-gray-200');
                        svg.classList.add('text-amber-400');
                    } else {
                        svg.classList.remove('text-amber-400');
                        svg.classList.add('text-gray-200');
                    }
                });
            };
        }

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

