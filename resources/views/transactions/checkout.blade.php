<x-app-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}">
    </script>

    <div class="max-w-6xl mx-auto py-12 px-4">
        <form id="checkout-form">
            @csrf
            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">

            <div class="grid lg:grid-cols-3 gap-10">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-6 rounded-3xl shadow">
                        <h2 class="font-bold mb-4">Lokasi Pengiriman</h2>
                        <div id="leaflet-map" style="height: 300px;" class="rounded-2xl mb-4"></div>
                        <textarea name="alamat" id="alamat" class="w-full border-gray-200 rounded-xl" placeholder="Alamat lengkap..." required></textarea>
                    </div>

                    <div class="bg-white p-6 rounded-3xl shadow">
                        <h2 class="font-bold mb-4">Metode Pembayaran</h2>
                        <div class="flex gap-4">
                            <label><input type="radio" name="payment_method" value="midtrans" checked> Midtrans</label>
                            <label><input type="radio" name="payment_method" value="cod"> COD</label>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-950 p-8 rounded-[2rem] text-white">
                    <h2 class="text-xl font-bold mb-6">Ringkasan</h2>
                    <div id="cart-summary" class="space-y-3 mb-6"></div>
                    <hr class="opacity-20 mb-4">
                    <p class="text-sm opacity-60">Total Bayar</p>
                    <h3 id="total-price-display" class="text-2xl font-bold text-indigo-400">Rp0</h3>
                    <button type="submit" id="btn-pay" class="w-full bg-indigo-600 py-4 rounded-xl mt-6 font-bold uppercase">Konfirmasi Pesanan</button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const checkoutItems = JSON.parse(localStorage.getItem('checkout_items') || '[]');
        
        // 1. Render Summary
        function renderSummary() {
            if(checkoutItems.length === 0) return window.location.href = '/';
            let total = 0;
            const container = document.getElementById('cart-summary');
            container.innerHTML = checkoutItems.map(item => {
                total += item.harga;
                return `<div class="flex justify-between text-xs"><span>${item.nama_barang}</span><span>Rp${item.harga.toLocaleString()}</span></div>`;
            }).join('');
            document.getElementById('total-price-display').innerText = `Rp${total.toLocaleString('id-ID')}`;
        }

        // 2. Leaflet Map
        let map = L.map('leaflet-map').setView([-6.20, 106.81], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        let marker = L.marker([-6.20, 106.81], {draggable: true}).addTo(map);

        marker.on('dragend', async () => {
            let pos = marker.getLatLng();
            document.getElementById('lat').value = pos.lat;
            document.getElementById('lng').value = pos.lng;
            const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${pos.lat}&lon=${pos.lng}`);
            const data = await res.json();
            document.getElementById('alamat').value = data.display_name;
        });

        // 3. Submit Form (FETCH)
        document.getElementById('checkout-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-pay');
            btn.disabled = true;
            btn.innerText = 'MEMPROSES...';

            const payload = {
                alamat: document.getElementById('alamat').value,
                payment_method: document.querySelector('input[name="payment_method"]:checked').value,
                cart: checkoutItems
            };

            try {
                const response = await fetch("{{ route('checkout.process') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (result.success) {
                    if (payload.payment_method === 'midtrans') {
                        window.snap.pay(result.snap_token, {
                            onSuccess: () => finish(),
                            onPending: () => finish(),
                            onError: () => alert('Pembayaran Gagal')
                        });
                    } else { finish(); }
                } else {
                    Swal.fire('Gagal', result.message, 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Cek koneksi atau login kembali', 'error');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Konfirmasi Pesanan';
            }
        });

        function finish() {
            localStorage.removeItem('checkout_items');
            Swal.fire('Sukses', 'Pesanan diproses!', 'success').then(() => window.location.href = '/transactions');
        }

        renderSummary();
    </script>
</x-app-layout>