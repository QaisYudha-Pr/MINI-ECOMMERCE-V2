<script>
    document.addEventListener('alpine:init', () => {
        // Global Store
        Alpine.store('global', {
            search: '',
            category: 'all',
            setSearch(val) { this.search = val },
            setCategory(val) { this.category = val }
        });

        // Cart Store
        Alpine.store('cart', {
            items: JSON.parse(localStorage.getItem('minie_cart') || '[]'),
            show: false,

            init() {
                window.addEventListener('storage', () => {
                    this.items = JSON.parse(localStorage.getItem('minie_cart') || '[]');
                });
            },

            add(item, qty = 1) {
                const exists = this.items.find(i => i.id === item.id);
                if (exists) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Sudah Ada!',
                        text: item.nama_barang + ' sudah ada di keranjang bolo!',
                        customClass: { popup: 'rounded-[2rem]' }
                    });
                    return;
                }
                this.items.push({ 
                    ...item, 
                    quantity: qty, 
                    selected: true 
                });
                this.save();
                this.show = true;
                
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                Toast.fire({
                    icon: 'success',
                    title: item.nama_barang + ' masuk keranjang!'
                });
            },

            remove(index) {
                this.items.splice(index, 1);
                this.save();
            },

            toggleAll(selected) {
                this.items.forEach(i => i.selected = selected);
                this.save();
            },

            save() {
                localStorage.setItem('minie_cart', JSON.stringify(this.items));
                window.dispatchEvent(new CustomEvent('cart-updated'));
            },

            get total() {
                return this.items
                    .filter(i => i.selected)
                    .reduce((sum, item) => sum + (item.harga * (item.quantity || 1)), 0);
            },

            get selectedCount() {
                return this.items.filter(i => i.selected).length;
            },

            checkout(onlySelected = false) {
                const toCheckout = onlySelected ? this.items.filter(i => i.selected) : this.items;
                if (toCheckout.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Wah Pelan-pelan bolo!',
                        text: 'Pilih dulu barang yang mau dibeli ya!',
                        customClass: { popup: 'rounded-[1.5rem]' }
                    });
                    return;
                }
                localStorage.setItem('checkout_items', JSON.stringify(toCheckout));
                window.location.href = '{{ route('checkout.index') }}';
            }
        });
    });

    // Alert Handling
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'MANTAP BOLO!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000,
            customClass: { popup: 'rounded-[2rem]' }
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'WADUH ADA ERROR!',
            html: `
                <div class="text-left font-medium text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            `,
            confirmButtonText: 'SAYA PERBAIKI',
            confirmButtonColor: '#00AA5B',
            customClass: {
                popup: 'rounded-[2rem]',
                confirmButton: 'rounded-xl px-6 py-3 text-xs font-black uppercase tracking-widest'
            }
        });
    @endif
</script>
