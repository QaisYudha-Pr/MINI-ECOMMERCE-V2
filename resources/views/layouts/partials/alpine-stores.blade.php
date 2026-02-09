<script>
    document.addEventListener('alpine:init', () => {
        // Global Store
        Alpine.store('global', {
            search: '',
            category: 'all',
            setSearch(val) { this.search = val },
            setCategory(val) { this.category = val }
        });

        // Cart Store (Lazy-loaded: only reads localStorage when needed)
        Alpine.store('cart', {
            _loaded: false,
            _items: [],
            show: false,

            get items() {
                if (!this._loaded) {
                    this._items = JSON.parse(localStorage.getItem('minie_cart') || '[]');
                    this._loaded = true;
                }
                return this._items;
            },

            set items(val) {
                this._items = val;
                this._loaded = true;
            },

            init() {
                window.addEventListener('storage', () => {
                    this._items = JSON.parse(localStorage.getItem('minie_cart') || '[]');
                    this._loaded = true;
                });
            },

            add(item, qty = 1, silent = false) {
                const exists = this.items.find(i => i.id === item.id);
                const numericQty = parseInt(qty) || 1;
                const numericPrice = Number(item.harga) || 0;

                if (exists) {
                    exists.quantity = (parseInt(exists.quantity) || 0) + numericQty;
                    
                    if (item.stok && exists.quantity > item.stok) {
                        exists.quantity = item.stok;
                    }

                    this.save();
                    
                    if (!silent) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Jumlah diperbarui di keranjang!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                    return;
                }
                
                this.items.push({ 
                    ...item, 
                    harga: numericPrice, 
                    quantity: numericQty, 
                    selected: true 
                });
                this.save();
                
                if (!silent) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: item.nama_barang + ' masuk keranjang!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
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
                // Optimization: Use requestIdleCallback if available for non-blocking save
                const persist = () => {
                    localStorage.setItem('minie_cart', JSON.stringify(this.items));
                    window.dispatchEvent(new CustomEvent('cart-updated'));
                };

                if (window.requestIdleCallback) {
                    window.requestIdleCallback(persist);
                } else {
                    setTimeout(persist, 0);
                }
            },

            get total() {
                // Faster reduction without too many parseInt calls if already clean
                return this.items
                    .filter(i => i.selected)
                    .reduce((sum, item) => sum + (Number(item.harga) * (Number(item.quantity) || 1)), 0);
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
            customClass: { popup: 'rounded-2xl' }
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
            confirmButtonColor: 'emerald-600',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl px-6 py-3 text-xs font-semibold'
            }
        });
    @endif

    // ===== GLOBAL TOGGLE FAVORITE (used by product-card component) =====
    const __isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
    window.toggleFavorite = async function(itemId, btn) {
        if (!__isLoggedIn) {
            Swal.fire({
                icon: 'info',
                title: 'Login dulu bolo!',
                text: 'Kamu harus login untuk menyimpan produk favorit.',
                confirmButtonText: 'LOGIN',
                confirmButtonColor: getComputedStyle(document.documentElement).getPropertyValue('--brand-600').trim() || '#059669',
                showCancelButton: true,
                cancelButtonText: 'Nanti',
                customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl px-6 py-3 text-xs font-semibold' }
            }).then(r => { if (r.isConfirmed) window.location.href = '{{ route("login") }}'; });
            return;
        }
        try {
            const response = await fetch(`/favorite/${itemId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();

            if (response.ok) {
                const icon = btn.querySelector('i');
                if (data.status) {
                    btn.classList.remove('text-gray-400');
                    btn.classList.add('text-rose-500');
                    if (icon) { icon.classList.remove('fa-regular'); icon.classList.add('fa-solid'); }
                } else {
                    btn.classList.remove('text-rose-500');
                    btn.classList.add('text-gray-400');
                    if (icon) { icon.classList.remove('fa-solid'); icon.classList.add('fa-regular'); }
                }

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: data.message || (data.status ? 'Ditambahkan ke wishlist!' : 'Dihapus dari wishlist!'),
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Waduh...', text: 'Gagal mengubah favorit bolo.' });
        }
    };
</script>

