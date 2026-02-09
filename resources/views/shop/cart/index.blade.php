<x-admin-layout>
    {{-- Script Midtrans Snap --}}
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <div class="max-w-7xl mx-auto">
        {{-- Modern Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10" data-aos="fade-right">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tighter uppercase">Riwayat <span class="text-emerald-600">Belanja</span></h2>
                    <p class="text-xs font-bold text-gray-400 mt-1">Pantau status & detail semua transaksi belanja Anda</p>
                </div>

                {{-- Horizontal Status Chips (Tokopedia Style) --}}
                <div class="flex items-center gap-2 overflow-x-auto pb-4 md:pb-0 scrollbar-hide">
                    @php 
                        $currentStatus = request('status', 'Semua'); 
                        $statusOptions = [
                            'Semua' => 'Semua',
                            'Berlangsung' => 'pending',
                            'Berhasil' => 'success',
                            'Selesai' => 'completed',
                        ];
                    @endphp
                    @foreach($statusOptions as $label => $slug)
                        <a href="{{ $label == 'Semua' ? route('transactions.index') : route('transactions.index', ['status' => $slug]) }}" 
                           class="whitespace-nowrap px-6 py-2.5 rounded-full text-xs font-semibold transition-all duration-300
                           {{ ($currentStatus == $slug || ($currentStatus == 'Semua' && $label == 'Semua')) 
                              ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-100' 
                              : 'bg-white text-gray-500 border border-gray-100 hover:border-emerald-200 hover:bg-emerald-50' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6">
                {{-- Search Bar --}}
                <div class="relative group mb-8">
                    <input type="text" placeholder="Cari transaksi atau nama produk..." class="w-full bg-white border-none rounded-2xl py-4 pl-14 pr-6 text-xs font-bold text-gray-600 shadow-sm focus:ring-4 focus:ring-emerald-500/10 transition-all">
                    <svg class="w-5 h-5 text-gray-300 absolute left-6 top-1/2 -translate-y-1/2 group-focus-within:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                @forelse($transactions as $trx)
                    @php
                        // Unified Status Logic Bolo!
                        $clientStatusLabel = match($trx->status) {
                            'pending', 'waiting_confirmation' => 'MENUNGGU KONFIRMASI',
                            'paid', 'success' => 'PEMBAYARAN BERHASIL',
                            'shipped' => 'SEDANG DIKIRIM',
                            'delivered' => 'SAMPAI DI TUJUAN',
                            'completed' => 'PESANAN SELESAI',
                            'failed' => 'TRANSAKSI GAGAL',
                            'canceled' => 'PESANAN DIBATALKAN',
                            default => strtoupper(str_replace('_', ' ', $trx->status))
                        };
                        $clientStatusColor = match($trx->status) {
                            'pending', 'waiting_confirmation' => 'text-amber-600 bg-amber-50 border-amber-100',
                            'paid', 'success' => 'text-green-600 bg-green-50 border-green-100',
                            'shipped', 'delivered' => 'text-blue-600 bg-blue-50 border-blue-100',
                            'completed' => 'text-green-600 bg-green-50 border-green-100',
                            'failed', 'canceled' => 'text-rose-600 bg-rose-50 border-rose-100',
                            default => 'text-slate-500 bg-slate-50 border-slate-100'
                        };
                        
                        $items = $trx->items_details;
                        $firstItem = collect($items)->first();
                        $otherCount = count($items) - 1;
                        $seller = $trx->seller;
                    @endphp

                    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm hover:shadow-xl hover:shadow-emerald-500/5 border border-transparent hover:border-emerald-100 transition-all group relative overflow-hidden">
                        {{-- Card Header: Invoice & Status --}}
                        <div class="flex flex-wrap items-center justify-between gap-4 mb-6 pb-6 border-b border-gray-50">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500 shadow-inner">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                </div>
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                    <span class="text-xs font-bold text-gray-900 uppercase">Belanja</span>
                                    <span class="text-xs font-bold text-gray-400">{{ $trx->created_at->translatedFormat('d M Y') }}</span>
                                    <span class="px-3 py-1 rounded-lg {{ $clientStatusColor }} text-[11px] font-semibold border border-current/10">
                                        {{ $clientStatusLabel }}
                                    </span>
                                    <span class="text-xs font-bold text-gray-300">{{ $trx->invoice_number }}</span>
                                </div>
                            </div>
                            
                            {{-- Seller Badge --}}
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 rounded-full border border-gray-100">
                                <svg class="w-3 h-3 text-emerald-500 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="text-xs font-bold text-gray-800 tracking-tight uppercase line-clamp-1">{{ $seller->nama_toko ?? $seller->name ?? "Toko Bolo" }}</span>
                            </div>
                        </div>

                        {{-- Card Body: Product Info --}}
                        <div class="flex flex-col md:flex-row gap-8 items-start md:items-center">
                            <div class="flex-1 flex gap-6">
                                <div class="w-16 h-16 md:w-20 md:h-20 bg-slate-100 rounded-2xl border border-white shadow-inner overflow-hidden shrink-0 group-hover:scale-110 transition-transform duration-500">
                                    <img src="{{ asset($firstItem['gambar'] ?? 'image/default-product.jpg') }}" class="w-full h-full object-cover">
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm md:text-base font-bold text-gray-900 line-clamp-1 group-hover:text-emerald-600 transition-colors">
                                        {{ $firstItem['nama_barang'] ?? ($firstItem['name'] ?? "Produk Bolo") }}
                                    </h3>
                                    <p class="text-xs font-bold text-gray-400 mt-1">
                                        {{ $firstItem['quantity'] ?? 1 }} Barang x Rp{{ number_format($firstItem['harga'] ?? 0, 0, ",", ".") }}
                                    </p>
                                    @if($otherCount > 0)
                                        <p class="text-[11px] font-semibold text-emerald-600 mt-3 inline-block bg-emerald-50 px-2 py-1 rounded-md border border-emerald-100/50">
                                            +{{ $otherCount }} Produk Lainnya
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Price column --}}
                            <div class="flex flex-col items-end md:pl-10 md:border-l border-gray-50 min-w-[150px]">
                                <span class="text-[11px] font-medium text-gray-400 mb-1">Total Belanja</span>
                                <span class="text-xl font-semibold text-emerald-600 tracking-tighter">Rp{{ number_format($trx->total_price, 0, ",", ".") }}</span>
                            </div>
                        </div>

                        {{-- Card Footer: Actions --}}
                        <div class="mt-8 pt-6 border-t border-gray-50 flex flex-wrap items-center justify-between gap-4">
                            <a href="{{ route('transactions.show', $trx->id) }}" class="text-xs font-bold text-emerald-500 hover:text-emerald-700 flex items-center gap-2 transition-all">
                                <span>Lihat Detail Transaksi</span>
                                <svg class="w-3 h-3 translate-y-[-1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </a>

                            <div class="flex items-center gap-3">
                                {{-- Pay Now for Pending --}}
                                @if($trx->status == "pending" && $trx->snap_token)
                                    <button onclick="payNow('{{ $trx->snap_token }}')" class="px-8 py-3.5 bg-emerald-500 text-white rounded-xl text-xs font-semibold shadow-lg shadow-emerald-100 hover:bg-emerald-600 transition-all">
                                        Bayar Sekarang
                                    </button>
                                @endif

                                {{-- Confirm Receipt --}}
                                @if(in_array($trx->status, ["shipped", "delivered"]))
                                    <form action="{{ route('transactions.confirm', $trx->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Sudah terima barangnya bolo?')" class="px-8 py-3.5 bg-emerald-600 text-white rounded-xl text-xs font-semibold shadow-lg shadow-emerald-100 hover:bg-slate-900 transition-all">
                                            Konfirmasi Selesai
                                        </button>
                                    </form>
                                @endif

                                {{-- Review Button --}}
                                @if($trx->status == "completed")
                                    <button onclick="showReviewModal('{{ $trx->invoice_number }}', {{ json_encode($trx->items_details) }})" class="px-6 py-3 bg-white border-2 border-amber-500 text-amber-500 rounded-xl text-xs font-semibold hover:bg-amber-50 transition-all">
                                        Beri Ulasan
                                    </button>

                                    {{-- Seller Review Button --}}
                                    @php $hasSellerReview = \App\Models\SellerReview::where('transaction_id', $trx->id)->where('buyer_id', auth()->id())->exists(); @endphp
                                    @if(!$hasSellerReview)
                                        <button onclick="showSellerReviewModal({{ $trx->id }}, '{{ $seller->nama_toko ?? $seller->name ?? 'Toko' }}', '{{ $seller->avatar ? asset($seller->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($seller->nama_toko ?? $seller->name).'&background=059669&color=fff' }}')" 
                                            class="px-6 py-3 bg-white border-2 border-emerald-500 text-emerald-500 rounded-xl text-xs font-semibold hover:bg-emerald-50 transition-all flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            Rating Seller
                                        </button>
                                    @else
                                        <span class="px-6 py-3 bg-emerald-50 text-emerald-400 rounded-xl text-xs font-semibold flex items-center gap-2 border border-emerald-100">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            Sudah Dirating
                                        </span>
                                    @endif
                                @endif
                                
                                {{-- Buy Again (Mockup for now) --}}
                                <a href="{{ route('shop.public') }}" class="px-8 py-3.5 bg-gray-900 text-white rounded-xl text-xs font-semibold hover:bg-black transition-all">
                                    Beli Lagi
                                </a>

                                <button class="p-3 bg-gray-50 text-gray-400 rounded-xl hover:bg-gray-100 transition-all border border-gray-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Progress Bar Decor --}}
                        @if(in_array($trx->status, ["pending", "paid", "success", "shipped"]))
                        <div class="absolute bottom-0 left-0 w-full h-[2px] bg-gray-50">
                            <div class="h-full bg-green-500 {{ $trx->status == 'shipped' ? 'w-2/3' : 'w-1/3' }} animate-pulse"></div>
                        </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-24 bg-white rounded-2xl border border-gray-100 shadow-sm">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 uppercase">Kosong, Bolo!</h3>
                        <p class="text-xs text-gray-400 font-bold mt-2">Daftar belanjamu masih kosong nih bolo.</p>
                        <a href="{{ route('shop.public') }}" class="inline-block mt-8 px-10 py-4 bg-emerald-600 text-white rounded-2xl text-xs font-semibold shadow-xl shadow-emerald-100 hover:-translate-y-1 transition-all">Mulai Belanja</a>
                    </div>
                @endforelse

                <div class="pb-20">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Review Modal Script & Helper --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        function payNow(snapToken) {
            window.snap.pay(snapToken, {
                onSuccess: function(result) { window.location.reload(); },
                onPending: function(result) { window.location.reload(); },
                onError: function(result) { alert("Pembayaran gagal bolo!"); }
            });
        }

        function showReviewModal(invoice, items) {
            let itemsHtml = items.map(item => {
                let img = item.gambar ? "/" + item.gambar : "/image/default-product.jpg";
                return `
                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl mb-3 border border-slate-100 group hover:border-emerald-300 transition-all">
                    <img src="${img}" class="w-12 h-12 rounded-xl object-cover shadow-sm bg-gray-50">
                    <div class="flex-1 text-left">
                        <p class="text-[11px] font-bold text-gray-900 uppercase line-clamp-1">${item.nama_barang || item.name}</p>
                        <p class="text-[11px] text-gray-400 font-bold">Beri rating produk bolo</p>
                    </div>
                    <button onclick="openProductRateModal(${item.id}, '${item.nama_barang || item.name}', '${img}')" class="bg-emerald-600 text-white px-4 py-2.5 rounded-xl text-[11px] font-semibold hover:bg-slate-900 transition-all">
                        Ulas
                    </button>
                </div>
            `}).join("");

            Swal.fire({
                title: '<span class="text-xl font-semibold">Beri <span class="text-emerald-600">Ulasan</span> Bolo!</span>',
                html: `
                    <div class="mt-4">
                        <p class="text-xs text-gray-500 mb-6 font-bold">Pilih produk dari invoice <span class="text-emerald-600 font-bold">${invoice}</span>:</p>
                        <div class="max-h-[350px] overflow-y-auto px-1 scrollbar-hide">
                            ${itemsHtml}
                        </div>
                    </div>
                `,
                showConfirmButton: false,
                showCloseButton: true,
                customClass: {
                    popup: 'rounded-2xl border-none shadow-lg',
                }
            });
        }

        function showSellerReviewModal(transactionId, sellerName, sellerAvatar) {
            let selectedRating = 0;

            function starHtml(count) {
                let html = '';
                for (let i = 1; i <= 5; i++) {
                    html += `<button type="button" onclick="setSellerRating(${i})" class="seller-star transition-all hover:scale-125 focus:outline-none" data-star="${i}">
                        <svg class="w-10 h-10 ${i <= count ? 'text-amber-400' : 'text-gray-200'}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </button>`;
                }
                return html;
            }

            const ratingLabels = ['', 'Sangat Buruk', 'Buruk', 'Cukup', 'Baik', 'Sangat Baik'];

            Swal.fire({
                html: `
                    <div class="text-center">
                        <img src="${sellerAvatar}" class="w-16 h-16 rounded-full mx-auto border-2 border-emerald-100 shadow-sm object-cover">
                        <h3 class="text-lg font-bold text-gray-900 mt-4">${sellerName}</h3>
                        <p class="text-xs text-gray-400 font-bold mt-1">Beri rating untuk seller ini bolo!</p>
                        
                        <form id="seller-review-form" action="/transactions/${transactionId}/seller-review" method="POST" class="mt-6">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]')?.content || '{{ csrf_token() }}'}">
                            <input type="hidden" name="rating" id="seller-rating-input" value="0">
                            
                            <div class="flex items-center justify-center gap-2 mb-2" id="seller-stars-container">
                                ${starHtml(0)}
                            </div>
                            <p id="seller-rating-label" class="text-xs font-semibold text-emerald-600 h-4 mb-4"></p>

                            <textarea name="comment" rows="3" maxlength="500" placeholder="Tulis komentar untuk seller (opsional)..." 
                                class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 text-xs font-bold text-gray-600 focus:border-emerald-300 focus:ring-0 transition-all resize-none"></textarea>
                            
                            <button type="submit" id="seller-review-submit" disabled
                                class="w-full mt-4 bg-emerald-600 text-white py-3.5 rounded-xl text-xs font-semibold hover:bg-slate-900 transition-all disabled:opacity-40 disabled:cursor-not-allowed shadow-lg shadow-emerald-100">
                                Kirim Rating
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

            // Expose rating setter globally for onclick
            window.setSellerRating = function(rating) {
                selectedRating = rating;
                document.getElementById('seller-rating-input').value = rating;
                document.getElementById('seller-review-submit').disabled = false;
                document.getElementById('seller-rating-label').textContent = ratingLabels[rating];

                // Update stars visually
                document.querySelectorAll('.seller-star svg').forEach((svg, idx) => {
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
                        <img src="${itemImage}" class="w-20 h-20 rounded-2xl mx-auto border-2 border-emerald-100 shadow-sm object-cover">
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
                                class="w-full mt-4 bg-emerald-600 text-white py-4 rounded-xl text-xs font-bold hover:bg-slate-900 transition-all disabled:opacity-40 disabled:cursor-not-allowed shadow-lg shadow-emerald-100">
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
                selectedRating = rating;
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
    </script>
</x-admin-layout>

