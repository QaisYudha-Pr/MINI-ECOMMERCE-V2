<div x-data="{ showCart: false }" @open-cart.window="showCart = true" x-show="showCart"
    class="fixed inset-0 z-[100] overflow-hidden" x-cloak>

    {{-- Overlay --}}
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" x-show="showCart"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" @click="showCart = false"></div>

    {{-- Sliding Panel --}}
    <div class="fixed inset-y-0 right-0 max-w-full flex">
        <div class="w-full sm:max-w-md" x-show="showCart"
            x-transition:enter="transform transition ease-in-out duration-500"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-500" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full">

            <div class="h-full flex flex-col bg-white shadow-2xl rounded-l-[3rem]">
                {{-- Header --}}
                <div class="px-8 py-10 flex items-center justify-between border-b border-gray-50">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 leading-none">Your Cart</h2>
                        <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-[0.2em] mt-2">Ready to
                            Checkout?</p>
                    </div>
                    <button @click="showCart = false" class="p-3 hover:bg-gray-50 rounded-2xl transition-colors">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>

                {{-- List Items --}}
                <div class="flex-1 overflow-y-auto px-8 py-6 space-y-6 no-scrollbar">
                    <div class="flex items-center gap-2 mb-2">
                        <input type="checkbox"
                            :checked="cart.length > 0 && cart.every(i => i.selected)"
                            @change="$dispatch('select-all-cart', { selected: !cart.every(i => i.selected) })"
                            class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-xs font-bold text-gray-500">Pilih Semua</span>
                    </div>
                    {{-- Loop Item Keranjang --}}
                    <template x-for="(item, index) in cart" :key="index">
                        <div class="flex items-center gap-4 p-4 border-b">
                            <input type="checkbox" x-model="item.selected"
                                   @change="$dispatch('update-cart', { index: index, selected: item.selected })"
                                   class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">

                            <img :src="item.gambar"
                                class="w-16 h-16 rounded-xl object-cover">

                            <div class="flex-grow min-w-0">
                                <h4 class="font-black text-gray-900 text-sm truncate" x-text="item.nama_barang"></h4>
                                <p class="text-indigo-600 font-bold text-xs mt-1"
                                   x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.harga)"></p>
                            </div>

                            <button @click="$dispatch('remove-from-cart', { index: index })"
                                class="p-2 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </template>

                    {{-- Empty State di Dalam Modal --}}
                    <template x-if="cart.length === 0">
                        <div class="h-full flex flex-col items-center justify-center text-center py-20">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <p class="text-gray-400 font-black uppercase tracking-widest text-[10px]">Keranjang Kosong
                                Bolo</p>
                        </div>
                    </template>
                </div>

                {{-- Footer --}}
                <div class="p-8 border-t border-gray-50 bg-gray-50/50 rounded-bl-[3rem]">
                    

                    <div class="mt-6">
                        <div class="flex gap-3 mb-4">
                            <button @click="$dispatch('remove-selected')"
                                :disabled="cart.filter(i => i.selected).length === 0"
                                class="flex-1 py-3 bg-red-100 text-red-600 rounded-2xl font-black uppercase text-xs disabled:opacity-50 disabled:cursor-not-allowed hover:bg-red-200 transition-all">
                                Hapus Terpilih
                            </button>

                            <button @click="$dispatch('checkout-selected')"
                                :disabled="cart.filter(i => i.selected).length === 0"
                                class="flex-1 py-3 bg-indigo-600 text-white rounded-2xl font-black uppercase text-xs disabled:opacity-50 disabled:cursor-not-allowed hover:bg-indigo-700 transition-all">
                                Checkout Terpilih
                            </button>
                        </div>

                        <div>
                            <button @click="processCheckout()" :disabled="cart.length === 0"
                                class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-lg shadow-indigo-200">
                                <div class="flex items-center justify-center gap-2">
                                    <span>Checkout Now</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
