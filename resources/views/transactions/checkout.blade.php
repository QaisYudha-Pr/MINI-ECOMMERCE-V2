<x-app-layout>
    {{-- CSS Leaflet & Custom Styling --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        #leaflet-map { z-index: 1; border: 2px solid #F1F5F9; }
        .qty-btn { @apply w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 hover:bg-indigo-50 hover:border-indigo-200 transition-all text-gray-600 font-bold; }
    </style>

    {{-- Midtrans Snap.js --}}
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <div class="bg-[#F8FAFC] min-h-screen pb-20">
        <div class="max-w-7xl mx-auto pt-10 px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="flex items-center gap-4 mb-10">
                <a href="/" class="p-3 bg-white rounded-2xl shadow-sm hover:shadow-md transition-all">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">Checkout</h1>
                    <p class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em]">Selesaikan pesananmu bolo</p>
                </div>
            </div>

            <form id="checkout-form" class="grid lg:grid-cols-12 gap-8 items-start">
                @csrf
                <input type="hidden" name="lat" id="lat">
                <input type="hidden" name="lng" id="lng">

                {{-- LEFT COLUMN: Lokasi & Produk --}}
                <div class="lg:col-span-8 space-y-8">
                    
                    {{-- 1. Lokasi Section --}}
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">Alamat Pengiriman</h2>
                        </div>
                        
                        <div id="leaflet-map" class="h-[250px] rounded-[2rem] mb-6 overflow-hidden shadow-inner"></div>
                        
                        <div class="relative">
                            <textarea name="alamat" id="alamat" rows="3" 
                                class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm focus:ring-2 focus:ring-indigo-500 transition-all placeholder:text-gray-400"
                                placeholder="Klik/Geser marker di peta untuk mengisi alamat..." required></textarea>
                            <div class="absolute right-4 top-4">
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-600 text-[9px] font-black rounded-full uppercase tracking-wider">Auto-Detect</span>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Daftar Produk Section --}}
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">Pesanan Anda</h2>
                        </div>

                        <div id="product-list" class="space-y-6">
                            </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Payment & Summary --}}
                <div class="lg:col-span-4 space-y-8 sticky top-28">
                    
                    {{-- 3. Payment Method --}}
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
                        <h2 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-6">Metode Pembayaran</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="payment_method" value="midtrans" class="peer hidden" checked>
                                <div class="p-4 rounded-2xl border-2 border-gray-100 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition-all text-center">
                                    <span class="block text-[10px] font-black text-gray-400 peer-checked:text-indigo-600 uppercase">Transfer / VA</span>
                                    <span class="text-sm font-black text-gray-900">Midtrans</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="payment_method" value="cod" class="peer hidden">
                                <div class="p-4 rounded-2xl border-2 border-gray-100 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition-all text-center">
                                    <span class="block text-[10px] font-black text-gray-400 peer-checked:text-indigo-600 uppercase">Bayar Ditempat</span>
                                    <span class="text-sm font-black text-gray-900">COD</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- 4. Ringkasan Belanja --}}
                    <div class="bg-[#0F172A] rounded-[2.5rem] p-8 text-white shadow-2xl">
                        <h2 class="text-lg font-black tracking-tighter mb-6 uppercase">Ringkasan Pesanan</h2>
                        <div class="space-y-4 text-sm font-medium opacity-80">
                            <div class="flex justify-between">
                                <span>Subtotal Produk</span>
                                <span id="summary-subtotal">Rp0</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Biaya Layanan</span>
                                <span>Rp2.500</span>
                            </div>
                        </div>
                        <div class="my-6 border-t border-white/10 pt-6 flex justify-between items-end">
                            <div>
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Total Pembayaran</p>
                                <p id="total-price-display" class="text-3xl font-black tracking-tighter">Rp0</p>
                            </div>
                        </div>
                        <button type="submit" id="btn-pay" 
                            class="w-full bg-indigo-600 hover:bg-indigo-700 py-5 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-indigo-900/20 transition-all active:scale-95">
                            Konfirmasi Pesanan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Script Section --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 1. DATA INITIALIZATION
        let checkoutItems = JSON.parse(localStorage.getItem('checkout_items') || '[]');
        // Pastikan setiap item punya property quantity minimal 1
        checkoutItems = checkoutItems.map(item => ({ ...item, quantity: item.quantity || 1 }));

        // 2. RENDER SUMMARY & ITEMS
        function renderCheckout() {
            if (checkoutItems.length === 0) return window.location.href = '/';
            
            let subtotal = 0;
            const productContainer = document.getElementById('product-list');
            
            productContainer.innerHTML = checkoutItems.map((item, index) => {
                const itemTotal = item.harga * item.quantity;
                subtotal += itemTotal;
                
                return `
                <div class="flex flex-col sm:flex-row items-center gap-6 p-4 rounded-3xl border border-gray-50 hover:bg-gray-50 transition-all">
                    <img src="${item.gambar}" class="w-24 h-24 rounded-2xl object-cover shadow-sm">
                    <div class="flex-1 text-center sm:text-left">
                        <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mb-1">${item.seller_name || 'Official Store'}</p>
                        <h3 class="font-black text-gray-900 text-base mb-1 truncate">${item.nama_barang}</h3>
                        <p class="text-gray-400 font-bold text-xs uppercase">Harga: Rp${item.harga.toLocaleString('id-ID')}</p>
                    </div>
                    <div class="flex items-center gap-4 bg-white px-4 py-2 rounded-2xl border border-gray-100 shadow-sm">
                        <button type="button" onclick="updateQty(${index}, -1)" class="qty-btn">-</button>
                        <span class="font-black text-gray-900 min-w-[20px] text-center">${item.quantity}</span>
                        <button type="button" onclick="updateQty(${index}, 1)" class="qty-btn">+</button>
                    </div>
                    <div class="text-right min-w-[120px]">
                        <p class="font-black text-gray-900">Rp${itemTotal.toLocaleString('id-ID')}</p>
                    </div>
                </div>`;
            }).join('');

            const grandTotal = subtotal + 2500;
            document.getElementById('summary-subtotal').innerText = `Rp${subtotal.toLocaleString('id-ID')}`;
            document.getElementById('total-price-display').innerText = `Rp${grandTotal.toLocaleString('id-ID')}`;
        }

        // 3. QUANTITY LOGIC
        window.updateQty = (index, change) => {
            const item = checkoutItems[index];
            const newQty = item.quantity + change;

            if (newQty > item.stok) {
                return Swal.fire('Waduh!', `Stok cuma ada ${item.stok} bolo!`, 'warning');
            }
            if (newQty < 1) {
                return Swal.fire({
                    title: 'Hapus item?',
                    text: "Item ini akan dihapus dari checkout",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!'
                }).then((res) => {
                    if(res.isConfirmed) {
                        checkoutItems.splice(index, 1);
                        saveAndRefresh();
                    }
                });
            }

            checkoutItems[index].quantity = newQty;
            saveAndRefresh();
        };

        function saveAndRefresh() {
            localStorage.setItem('checkout_items', JSON.stringify(checkoutItems));
            renderCheckout();
        }

        // 4. MAP SETUP
        let map = L.map('leaflet-map').setView([-6.20, 106.81], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        let marker = L.marker([-6.20, 106.81], {draggable: true}).addTo(map);

        marker.on('dragend', async () => {
            let pos = marker.getLatLng();
            document.getElementById('lat').value = pos.lat;
            document.getElementById('lng').value = pos.lng;
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${pos.lat}&lon=${pos.lng}`);
            const data = await res.json();
            document.getElementById('alamat').value = data.display_name || "Alamat tidak ditemukan";
        });

        // Fungsi helper untuk menghapus item dari cart utama
        function clearPurchasedFromCart() {
            try {
                const mainCart = JSON.parse(localStorage.getItem('minie_cart') || '[]');
                
                // Gunakan filter yang lebih agresif (cek ID dan Nama sebagai fallback)
                const updatedCart = mainCart.filter(item => {
                    return !checkoutItems.some(co => 
                        String(co.id) === String(item.id) || 
                        (co.nama_barang === item.nama_barang && String(co.harga) === String(item.harga))
                    );
                });

                localStorage.setItem('minie_cart', JSON.stringify(updatedCart));
                
                // Dispatch event untuk update UI (navigasi, dll)
                window.dispatchEvent(new CustomEvent('cart-updated'));
                window.dispatchEvent(new CustomEvent('notify', { detail: 'Keranjang diperbarui' }));
            } catch (e) {
                console.error('Gagal update cart:', e);
            }
        }

        // 5. SUBMIT HANDLER
        document.getElementById('checkout-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-pay');
            const alamat = document.getElementById('alamat').value;

            if(!alamat) return Swal.fire('Error', 'Tentukan lokasi dulu bolo!', 'warning');

            btn.disabled = true;
            btn.innerHTML = '<span class="animate-pulse">SINKRONISASI STOK...</span>';

            const payload = {
                alamat: alamat,
                payment_method: document.querySelector('input[name="payment_method"]:checked').value,
                cart: checkoutItems
            };

            try {
                const response = await fetch("{{ route('checkout.process') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (result.success) {
                    // LANGSUNG HAPUS DARI CART BEGITU ORDER BERHASIL DIBUAT
                    clearPurchasedFromCart();

                    if (payload.payment_method === 'midtrans') {
                        window.snap.pay(result.snap_token, {
                            onSuccess: () => finishCheckout(),
                            onPending: () => finishCheckout(),
                            onError: () => Swal.fire('Gagal', 'Pembayaran gagal!', 'error')
                        });
                    } else {
                        finishCheckout();
                    }
                } else {
                    Swal.fire('Waduh!', result.message, 'error').then(() => window.location.reload());
                }
            } catch (err) {
                Swal.fire('Error', 'Koneksi bermasalah bolo!', 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Konfirmasi Pesanan';
            }
        });

        function finishCheckout() {
            localStorage.removeItem('checkout_items');
            Swal.fire('Sukses!', 'Pesanan diproses bolo!', 'success').then(() => {
                window.location.href = '/transactions';
            });
        }

        // Jalankan render pertama kali
        renderCheckout();
    </script>
</x-app-layout>