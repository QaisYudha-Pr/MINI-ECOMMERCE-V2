<x-app-layout>
    {{-- 1. HEADER & STYLING --}}
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Styling SweetAlert biar matching sama tema minimalist */
        .swal2-popup {
            border-radius: 2.5rem !important;
            padding: 2rem !important;
        }

        .swal2-styled.swal2-confirm {
            background-color: #4f46e5 !important;
            border-radius: 1rem !important;
            padding: 0.8rem 2rem !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            font-size: 0.8rem !important;
            letter-spacing: 0.1em !important;
        }

        .swal2-styled.swal2-cancel {
            border-radius: 1rem !important;
            padding: 0.8rem 2rem !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            font-size: 0.8rem !important;
        }
    </style>

    {{-- 2. Alpine.js Root Container --}}
    <div x-data="{
        search: '',
        activeCategory: 'all',
        cart: JSON.parse(localStorage.getItem('minie_cart') || '[]'),
    
        {{-- Data Items dari Database --}}
        items: {{ $items->filter(fn($i) => !empty(trim($i->kategori)))->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama_barang' => $item->nama_barang,
                    'harga' => $item->harga,
                    'gambar' => asset($item->gambar),
                    'kategori' => strtolower($item->kategori),
                    'stok' => $item->stok,
                    'seller_name' => $item->user->name ?? 'Official Store',
                    'seller_avatar' =>
                        'https://ui-avatars.com/api/?name=' .
                        urlencode($item->user->name ?? 'OS') .
                        '&color=7F9CF5&background=EBF4FF',
                ];
            })->values()->toJson() }},
    
        {{-- Logika Tambah Ke Keranjang --}}
        addToCart(item) {
            if (item.stok <= 0) {
                Swal.fire('Stok Habis', item.nama_barang + ' tidak tersedia', 'warning');
                return;
            }
            const exists = this.cart.find(c => c.id === item.id);
            if (exists) {
                Swal.fire('Sudah Ada', item.nama_barang + ' sudah di keranjang bolo!', 'info');
                return;
            }
            this.cart.push({ ...item, quantity: 1, selected: true });
            this.saveCart();
            $dispatch('notify', item.nama_barang + ' masuk keranjang!');
            window.dispatchEvent(new CustomEvent('cart-updated'));
        },
    
        get selectedTotalPrice() {
            return this.cart
                .filter(item => item.selected)
                .reduce((sum, item) => sum + item.harga, 0);
        },
    
        get selectedCount() {
            return this.cart.filter(item => item.selected).length;
        },
    
        {{-- Logika Hapus Dari Keranjang --}}
        removeFromCart(index) {
            this.cart.splice(index, 1);
            this.saveCart();
            $dispatch('notify', 'Barang dihapus');
        },
    
        {{-- Logika Simpan --}}
        saveCart() { localStorage.setItem('minie_cart', JSON.stringify(this.cart)); },
    
        {{-- ARAHKAN KE HALAMAN CHECKOUT BARU --}}
        processCheckout() {
            const toCheckout = this.cart.filter(i => i.selected);
            if (toCheckout.length === 0) {
                Swal.fire({ icon: 'warning', title: 'Pilih barang dulu bolo!', text: 'Centang barang yang mau dibayar di keranjang.' });
                return;
            }
    
            // Simpan sementara data yang mau di-CO ke localStorage khusus
            localStorage.setItem('checkout_items', JSON.stringify(toCheckout));
    
            // Redirect ke route checkout yang ada form alamat & maps-nya
            window.location.href = '{{ route('checkout.index') }}';
        },
    
        removeSelected() {
            this.cart = this.cart.filter(i => !i.selected);
            this.saveCart();
            $dispatch('notify', 'Item terpilih dihapus');
        },
    
        get categories() {
            const availableItems = this.items.filter(item => item.stok > 0);
            return ['all', ...new Set(availableItems.map(item => item.kategori))];
        },
    
        get filteredItems() {
            return this.items.filter(item => {
                const isAvailable = item.stok > 0;
                const matchSearch = item.nama_barang.toLowerCase().includes(this.search.toLowerCase());
                const matchCategory = this.activeCategory === 'all' || item.kategori === this.activeCategory;
                return isAvailable && matchSearch && matchCategory;
            });
        }
    }" @remove-from-cart.window="removeFromCart($event.detail.index)"
        @update-cart.window="cart[$event.detail.index].selected = $event.detail.selected; saveCart()"
        @remove-selected.window="removeSelected()" @checkout-selected.window="processCheckout()"
        @select-all-cart.window="cart.forEach(i => i.selected = $event.detail.selected); saveCart()"
        class="bg-[#F8FAFC] min-h-screen pb-32">

        {{-- 3. HERO SECTION --}}
        <section class="relative mx-4 sm:mx-8 mt-6 overflow-hidden bg-[#0F172A] rounded-[3rem] shadow-2xl"
            data-aos="zoom-in">
            <div
                class="absolute top-0 right-0 -translate-y-12 translate-x-12 w-96 h-96 bg-indigo-600 rounded-full blur-[120px] opacity-20">
            </div>
            <div class="relative max-w-7xl mx-auto px-8 py-20 flex flex-col items-center text-center">
                <span
                    class="inline-block px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-400/20 text-indigo-400 text-[10px] font-black uppercase tracking-[0.3em] mb-8">
                    Minimalist Shop Mode
                </span>
                <h1 class="text-5xl sm:text-6xl font-black text-white leading-tight tracking-tighter mb-10">
                    Find Your Best <br> <span class="text-indigo-500">Minimalist</span> Gear.
                </h1>
                <div class="relative w-full max-w-2xl">
                    <input x-model="search" type="text" placeholder="Cari barang yang ready bolo..."
                        class="w-full pl-12 pr-8 py-6 bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2.5rem] text-white focus:bg-white focus:text-gray-900 transition-all outline-none">
                </div>
            </div>
        </section>

        {{-- 4. KATEGORI --}}
        <section class="max-w-7xl mx-auto px-8 mt-16" data-aos="fade-up">
            <div class="flex gap-4 overflow-x-auto no-scrollbar pb-4">
                <template x-for="cat in categories" :key="cat">
                    <button @click="activeCategory = cat"
                        :class="activeCategory === cat ? 'bg-indigo-600 text-white shadow-lg' :
                            'bg-white text-gray-500 border-gray-100'"
                        class="px-8 py-4 rounded-[2rem] border transition-all whitespace-nowrap">
                        <span class="text-[10px] font-black uppercase tracking-widest" x-text="cat"></span>
                    </button>
                </template>
            </div>
        </section>

        {{-- 5. PRODUK GRID --}}
        <section class="max-w-7xl mx-auto px-8 mt-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <template x-for="(item, index) in filteredItems" :key="item.id">
                    <div class="group bg-white rounded-[2.5rem] border border-gray-100 p-5 hover:shadow-2xl transition-all duration-500 flex flex-col h-full"
                        data-aos="fade-up" :data-aos-delay="index * 50">

                        <div class="relative aspect-square rounded-[2rem] overflow-hidden bg-gray-50 mb-6">
                            <img :src="item.gambar"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <a :href="'/item-shop/' + item.id"
                                class="absolute inset-0 bg-indigo-900/10 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                                <div
                                    class="bg-white text-gray-900 px-6 py-2 rounded-xl font-black text-[10px] uppercase">
                                    Explore</div>
                            </a>
                        </div>

                        <div class="flex flex-col flex-grow">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <img :src="item.seller_avatar" class="w-6 h-6 rounded-full border border-gray-100">
                                    <span class="text-[9px] font-black text-gray-400 uppercase"
                                        x-text="item.seller_name"></span>
                                </div>
                                <div class="flex items-center gap-1.5 text-green-600">
                                    <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
                                    <span class="text-[9px] font-black uppercase" x-text="'Stok: ' + item.stok"></span>
                                </div>
                            </div>

                            <a :href="'/item-shop/' + item.id" class="block mb-2">
                                <h3 class="font-black text-gray-900 text-base leading-tight group-hover:text-indigo-600 transition-colors"
                                    x-text="item.nama_barang"></h3>
                            </a>

                            <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between">
                                <p class="text-gray-900 font-black text-xl tracking-tighter">
                                    <span class="text-xs mr-0.5">Rp</span><span
                                        x-text="new Intl.NumberFormat('id-ID').format(item.harga)"></span>
                                </p>
                                <button @click="addToCart(item)"
                                    class="w-11 h-11 bg-gray-900 text-white rounded-2xl flex items-center justify-center transition-all hover:bg-indigo-600 shadow-lg shadow-gray-100 group/btn">
                                    <svg class="w-5 h-5 group-hover/btn:rotate-90 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </section>

        {{-- 6. MODAL & FOOTER --}}
        @include('components.cart-modal')
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</x-app-layout>
