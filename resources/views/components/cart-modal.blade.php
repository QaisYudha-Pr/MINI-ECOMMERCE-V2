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
                        <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-[0.2em] mt-2">MiniE-Store Terminal</p>
                    </div>
                    <button @click="showCart = false" class="p-3 hover:bg-gray-50 rounded-2xl">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2"/></svg>
                    </button>
                </div>

                {{-- List Items --}}
                <div class="flex-1 overflow-y-auto px-8 py-6 space-y-6 no-scrollbar">
                    <template x-for="(item, index) in cart" :key="index">
                        <div class="flex items-center gap-4 group">
                            <div class="w-20 h-20 bg-gray-50 rounded-2xl overflow-hidden border border-gray-100">
                                <img :src="item.gambar" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1">
                                <h4 class="font-black text-gray-900 text-sm" x-text="item.nama_barang"></h4>
                                <p class="text-indigo-600 font-bold text-xs" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.harga)"></p>
                            </div>
                            <button @click="$dispatch('remove-item', { index: index })" class="p-2 text-gray-300 hover:text-red-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2"/></svg>
                            </button>
                        </div>
                    </template>
                </div>

                {{-- Footer --}}
                <div class="p-8 border-t border-gray-50 bg-gray-50/50 rounded-bl-[3rem]">
                    <div class="flex justify-between items-end mb-6">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Payment</span>
                        <span class="text-2xl font-black text-gray-900" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(totalPrice)"></span>
                    </div>
                    <button @click="$dispatch('do-checkout')" class="w-full py-5 bg-indigo-600 text-white rounded-[1.5rem] font-black uppercase tracking-widest text-xs shadow-xl shadow-indigo-200">
                        Checkout Now
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>