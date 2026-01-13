<div x-data="{ showCart: false }" 
     @open-cart.window="showCart = true"
     x-show="showCart" 
     class="fixed inset-0 z-[100] overflow-hidden" 
     x-cloak>
    
    {{-- Overlay --}}
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" 
         x-show="showCart"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         @click="showCart = false"></div>

    {{-- Sliding Panel --}}
    <div class="fixed inset-y-0 right-0 max-w-full flex">
        <div class="w-screen max-w-md"
             x-show="showCart"
             x-transition:enter="transform transition ease-in-out duration-500"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in-out duration-500"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full">
            
            <div class="h-full flex flex-col bg-white shadow-2xl rounded-l-[3rem]">
                {{-- Header --}}
                <div class="px-8 py-10 flex items-center justify-between border-b border-gray-50">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 leading-none">Your Cart</h2>
                        <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-[0.2em] mt-2">Ready to Checkout?</p>
                    </div>
                    <button @click="showCart = false" class="p-3 hover:bg-gray-50 rounded-2xl transition-colors">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>

                {{-- List Items --}}
                <div class="flex-1 overflow-y-auto px-8 py-6 space-y-6 no-scrollbar">
                    {{-- Loop Item Keranjang --}}
                    <template x-for="(item, index) in cart" :key="index">
                        <div class="flex items-center gap-4 group">
                            <div class="w-20 h-20 bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 flex-shrink-0">
                                <img :src="item.gambar" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-black text-gray-900 text-sm truncate" x-text="item.nama_barang"></h4>
                                <p class="text-indigo-600 font-bold text-xs mt-1" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.harga)"></p>
                            </div>
                            {{-- PERBAIKAN DI SINI: Event name disamakan dengan Home --}}
                            <button @click="$dispatch('remove-from-cart', { index: index })" 
                                    class="p-2 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </template>

                    {{-- Empty State di Dalam Modal --}}
                    <template x-if="cart.length === 0">
                        <div class="h-full flex flex-col items-center justify-center text-center py-20">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <p class="text-gray-400 font-black uppercase tracking-widest text-[10px]">Keranjang Kosong Bolo</p>
                        </div>
                    </template>
                </div>

                {{-- Footer --}}
                <div class="p-8 border-t border-gray-50 bg-gray-50/50 rounded-bl-[3rem]">
                    <div class="flex justify-between items-end mb-6">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Total Payment</span>
                        <span class="text-2xl font-black text-gray-900 leading-none" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(totalPrice)"></span>
                    </div>
                    
                    <button @click="window.location.href='/checkout'" 
                            :disabled="cart.length === 0"
                            :class="cart.length === 0 ? 'bg-gray-200 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700 shadow-xl shadow-indigo-200'"
                            class="w-full py-5 text-white rounded-[1.5rem] font-black uppercase tracking-widest text-xs transition-all active:scale-95">
                        Checkout Now
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>