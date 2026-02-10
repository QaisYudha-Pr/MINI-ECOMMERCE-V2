<x-app-layout>
<div class="max-w-6xl mx-auto px-4 py-8" x-data="cartManager()">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Keranjang Belanja</h1>
        <p class="text-slate-500 mt-1"><span x-text="totalItems">{{ $cartItems->sum('quantity') }}</span> item dalam keranjang</p>
    </div>

    <template x-if="isEmpty">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xl p-16 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <h3 class="font-bold text-slate-900 mb-2">Keranjangmu kosong bolo!</h3>
            <p class="text-slate-500 text-sm mb-6">Yuk jelajahi produk-produk keren kami</p>
            <a href="{{ route('shop.public') }}" class="inline-block px-8 py-4 bg-emerald-600 text-white rounded-2xl font-bold hover:bg-emerald-700 transition-colors">
                Mulai Belanja
            </a>
        </div>
    </template>

    <template x-if="!isEmpty">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Cart Items --}}
            <div class="lg:col-span-2 space-y-6">
                @foreach($groupedBySeller as $sellerId => $items)
                    @php $seller = $items->first()->item->user; @endphp
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl overflow-hidden" id="seller-group-{{ $sellerId }}">
                        {{-- Seller Header --}}
                        <div class="p-5 bg-slate-50 border-b border-slate-100 flex items-center gap-3">
                            <x-user-avatar :user="$seller" size="w-9 h-9" shape="rounded-full" textSize="text-[10px]" />
                            <div class="flex-1">
                                <h3 class="text-sm font-bold text-slate-900">{{ $seller->nama_toko ?? $seller->name }}</h3>
                                @if($seller->is_top_seller)
                                <span class="text-[11px] font-bold text-amber-600">Top Seller</span>
                                @endif
                            </div>
                            <a href="{{ route('chat.start', $seller) }}" class="px-3 py-1.5 border border-emerald-200 text-emerald-600 rounded-lg text-xs font-bold hover:bg-emerald-50 transition-colors">
                                Chat
                            </a>
                        </div>

                        {{-- Items --}}
                        <div class="divide-y divide-slate-50">
                            @foreach($items as $cart)
                            <div class="p-5 flex gap-4" id="cart-item-{{ $cart->id }}" x-ref="item{{ $cart->id }}">
                                <a href="{{ route('shop.show', $cart->item) }}" class="shrink-0">
                                    <img src="{{ $cart->item->photo ? asset($cart->item->photo) : asset('image/default-product.jpg') }}" 
                                         class="w-20 h-20 rounded-xl object-cover border border-slate-100 hover:opacity-80 transition-opacity">
                                </a>
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('shop.show', $cart->item) }}" class="text-sm font-bold text-slate-900 truncate block hover:text-emerald-600 transition-colors">{{ $cart->item->name }}</a>
                                    <p class="text-emerald-600 font-bold text-sm mt-1">Rp {{ number_format($cart->item->price, 0, ',', '.') }}</p>
                                    <p class="text-slate-400 text-xs mt-0.5 uppercase font-bold tracking-wider">Stok: {{ $cart->item->stok }}</p>
                                    @if($cart->notes)
                                    <p class="text-slate-500 text-xs mt-1.5 bg-slate-50 px-2 py-1 rounded-lg inline-block">"{{ $cart->notes }}"</p>
                                    @endif
                                </div>
                                <div class="flex flex-col items-end justify-between shrink-0">
                                    {{-- Quantity Control --}}
                                    <div class="flex items-center gap-1.5 bg-slate-50 rounded-xl p-1">
                                        <button @click="updateQty({{ $cart->id }}, -1, {{ $cart->item->stok }}, {{ $cart->item->price }})" 
                                                class="w-7 h-7 bg-white rounded-lg flex items-center justify-center hover:bg-slate-200 transition-colors shadow-sm text-slate-500">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                                        </button>
                                        <span class="w-8 text-center text-sm font-bold text-slate-900" id="qty-{{ $cart->id }}">{{ $cart->quantity }}</span>
                                        <button @click="updateQty({{ $cart->id }}, 1, {{ $cart->item->stok }}, {{ $cart->item->price }})" 
                                                class="w-7 h-7 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center hover:bg-emerald-200 transition-colors shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                        </button>
                                    </div>
                                    {{-- Subtotal & Remove --}}
                                    <div class="text-right mt-2">
                                        <p class="text-sm font-bold text-slate-900" id="subtotal-{{ $cart->id }}">Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</p>
                                        <button @click="removeItem({{ $cart->id }}, {{ $sellerId }})" class="text-rose-400 text-xs font-bold hover:text-rose-600 hover:underline mt-1 transition-colors">Hapus</button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                {{-- Clear Cart --}}
                <div class="flex justify-end">
                    <button @click="clearAll()" class="text-slate-400 text-xs font-bold hover:text-rose-500 transition-colors">
                        Kosongkan Keranjang
                    </button>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-xl p-7 sticky top-4">
                    <h3 class="text-base font-bold text-slate-900 mb-5">Ringkasan</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Subtotal (<span x-text="totalItems">{{ $cartItems->sum('quantity') }}</span> item)</span>
                            <span class="font-bold text-slate-900" id="cart-subtotal" x-text="formatRp(subtotal)">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>

                        {{-- Voucher Input --}}
                        <div class="pt-3 border-t border-slate-100">
                            <label class="text-[11px] font-medium text-slate-400 mb-1.5 block">Kode Voucher</label>
                            <div class="flex gap-2">
                                <input type="text" x-model="voucherCode" placeholder="Masukkan kode" 
                                       @keydown.enter.prevent="applyVoucher()"
                                       class="flex-1 px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono uppercase focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500 transition-all">
                                <button @click="applyVoucher()" :disabled="!voucherCode.trim() || voucherLoading" 
                                        class="px-4 py-2.5 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition-colors disabled:opacity-40">
                                    <span x-show="!voucherLoading">Pakai</span>
                                    <span x-show="voucherLoading" x-cloak>...</span>
                                </button>
                            </div>
                            <p x-show="voucherMsg" x-text="voucherMsg" x-cloak
                               :class="voucherSuccess ? 'text-emerald-600' : 'text-rose-500'"
                               class="text-xs font-bold mt-1.5"></p>
                        </div>

                        {{-- Discount --}}
                        <div x-show="discount > 0" x-cloak class="flex justify-between text-sm text-emerald-600">
                            <span>Diskon</span>
                            <span x-text="'-' + formatRp(discount)">-Rp 0</span>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex justify-between">
                            <span class="text-slate-900 font-bold text-sm">Total</span>
                            <span class="text-lg font-semibold text-emerald-600" x-text="formatRp(Math.max(0, subtotal - discount))">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="mt-5 w-full py-3.5 bg-emerald-600 text-white rounded-2xl font-bold text-sm text-center block hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-100">
                        Checkout Sekarang
                    </a>

                    <p class="text-center text-slate-400 text-xs mt-3 font-bold">Gratis ongkir untuk pembelian di atas Rp 500.000</p>
                </div>
            </div>
        </div>
    </template>
</div>

@push('scripts')
<script>
function cartManager() {
    return {
        subtotal: {{ $subtotal }},
        totalItems: {{ $cartItems->sum('quantity') }},
        isEmpty: {{ $cartItems->isEmpty() ? 'true' : 'false' }},
        discount: 0,
        voucherCode: '',
        voucherMsg: '',
        voucherSuccess: false,
        voucherLoading: false,
        pendingUpdate: null,
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.content || '',

        formatRp(n) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(n);
        },

        updateQty(cartId, delta, maxStok, price) {
            const qtyEl = document.getElementById(`qty-${cartId}`);
            let current = parseInt(qtyEl.textContent);
            let newQty = current + delta;
            if (newQty < 1 || newQty > maxStok) return;

            // Update UI immediately
            qtyEl.textContent = newQty;
            const oldSubtotal = current * price;
            const newSubtotal = newQty * price;
            document.getElementById(`subtotal-${cartId}`).textContent = this.formatRp(newSubtotal);
            this.subtotal += (newSubtotal - oldSubtotal);
            this.totalItems += delta;

            // Debounce the server call
            if (this.pendingUpdate) clearTimeout(this.pendingUpdate);
            this.pendingUpdate = setTimeout(() => {
                fetch(`/cart/${cartId}`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ quantity: newQty })
                }).then(r => r.json()).then(data => {
                    if (data.success) {
                        document.getElementById(`subtotal-${cartId}`).textContent = this.formatRp(data.subtotal);
                        this.subtotal = data.cart_total;
                    }
                }).catch(() => {});
            }, 400);
        },

        async removeItem(cartId, sellerId) {
            const el = document.getElementById(`cart-item-${cartId}`);
            if (!el) return;

            // Animate out
            el.style.transition = 'all 0.3s ease';
            el.style.opacity = '0';
            el.style.maxHeight = el.scrollHeight + 'px';
            setTimeout(() => { el.style.maxHeight = '0'; el.style.padding = '0'; el.style.margin = '0'; }, 50);

            try {
                const res = await fetch(`/cart/${cartId}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    setTimeout(() => {
                        el.remove();
                        // Remove seller group if no items left
                        const group = document.getElementById(`seller-group-${sellerId}`);
                        if (group && group.querySelectorAll('[id^="cart-item-"]').length === 0) {
                            group.style.transition = 'all 0.3s';
                            group.style.opacity = '0';
                            setTimeout(() => group.remove(), 300);
                        }
                        this.subtotal = data.cart_total;
                        this.totalItems = data.cart_count;
                        if (data.is_empty) this.isEmpty = true;
                    }, 350);
                }
            } catch (e) {
                el.style.opacity = '1';
                el.style.maxHeight = '';
                el.style.padding = '';
            }
        },

        async clearAll() {
            if (!confirm('Kosongkan semua item di keranjang?')) return;
            try {
                const res = await fetch('/cart', {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    this.isEmpty = true;
                    this.subtotal = 0;
                    this.totalItems = 0;
                    this.discount = 0;
                }
            } catch (e) {}
        },

        async applyVoucher() {
            const code = this.voucherCode.trim();
            if (!code) return;
            this.voucherLoading = true;
            this.voucherMsg = '';

            try {
                const res = await fetch('/cart/apply-voucher', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ code })
                });
                const data = await res.json();
                if (data.success) {
                    this.voucherMsg = `✓ ${data.voucher.name} diterapkan!`;
                    this.voucherSuccess = true;
                    this.discount = data.voucher.discount;
                    sessionStorage.setItem('applied_voucher', JSON.stringify(data.voucher));
                } else {
                    this.voucherMsg = data.error || 'Voucher tidak valid';
                    this.voucherSuccess = false;
                }
            } catch (e) {
                this.voucherMsg = 'Gagal menerapkan voucher';
                this.voucherSuccess = false;
            }
            this.voucherLoading = false;
        }
    };
}
</script>
@endpush
</x-app-layout>

