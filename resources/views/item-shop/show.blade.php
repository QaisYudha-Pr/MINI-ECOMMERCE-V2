<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3" data-aos="fade-right">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 118 0m-4 5v2a3 3 0 01-3 3h-6a3 3 0 01-3-3V7a3 3 0 013-3h6a3 3 0 013 3V7" />
                </svg>
            </div>
            <h2 class="font-black text-xl text-gray-800 leading-tight uppercase tracking-widest">
                Detail <span class="text-indigo-600">Produk</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f8fafc]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur-md overflow-hidden shadow-2xl shadow-gray-200/50 sm:rounded-[3rem] p-6 md:p-12 border border-white" data-aos="fade-up">
                
                <div class="grid md:grid-cols-2 gap-16 items-start">
                    
                    <div class="relative group">
                        <div class="absolute -inset-6 bg-gradient-to-tr from-indigo-50 to-purple-50 rounded-[4rem] opacity-50 blur-2xl group-hover:opacity-100 transition-opacity duration-700 -z-10"></div>
                        
                        <div class="relative overflow-hidden rounded-[2.5rem] shadow-2xl shadow-indigo-100 border-[12px] border-white group-hover:border-indigo-50 transition-colors duration-500" data-aos="zoom-in" data-aos-delay="200">
                            <img src="{{ $itemShop->gambar ?? 'https://via.placeholder.com/800' }}"
                                 alt="{{ $itemShop->nama_barang }}"
                                 class="w-full h-[550px] object-cover transform group-hover:scale-105 transition-transform duration-1000">
                            
                            <div class="absolute top-6 left-6">
                                <div class="bg-white/80 backdrop-blur-md px-5 py-2.5 rounded-2xl shadow-xl border border-white/50 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-indigo-600 rounded-full animate-pulse"></span>
                                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-800">Premium Item</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col h-full py-4">
                        <nav class="flex items-center gap-3 mb-8" data-aos="fade-left" data-aos-delay="300">
                            <a href="{{ route('home') }}" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-indigo-600 transition-colors">Store</a>
                            <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="3"/></svg>
                            <span class="text-[10px] font-black uppercase tracking-widest text-indigo-600">Product Detail</span>
                        </nav>
                        
                        <div class="mb-8" data-aos="fade-left" data-aos-delay="400">
                            <span class="inline-block px-4 py-1.5 bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest rounded-lg mb-4">Official Product</span>
                            <h1 class="text-5xl font-black text-gray-900 leading-[1.1] tracking-tighter">
                                {{ $itemShop->nama_barang }}
                            </h1>
                        </div>

                        <div class="mb-10 p-6 bg-gray-50 rounded-[2rem] border border-gray-100 inline-block self-start" data-aos="fade-left" data-aos-delay="500">
                            <div class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-1">Current Price</div>
                            <div class="text-4xl font-black text-indigo-600 flex items-start gap-1">
                                <span class="text-lg mt-1">Rp</span>
                                {{ number_format($itemShop->harga, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="space-y-4 mb-12" data-aos="fade-left" data-aos-delay="600">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-[2px] bg-indigo-600 rounded-full"></div>
                                <h3 class="text-xs font-black uppercase tracking-widest text-gray-900">Product Story</h3>
                            </div>
                            <p class="text-gray-500 leading-relaxed text-xl font-medium italic">
                                "{{ $itemShop->deskripsi }}"
                            </p>
                        </div>

                        <div class="mt-auto border-t border-gray-100 pt-10">
                            @auth
                                <div class="flex gap-5" data-aos="fade-up" data-aos-delay="700">
                                    <button class="flex-[3] px-10 py-6 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-3xl font-black uppercase tracking-[0.15em] text-xs shadow-2xl shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-1.5 active:scale-95 transition-all duration-300 flex items-center justify-center gap-3 group">
                                        <span>Checkout Now</span>
                                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                    
                                    <button class="flex-1 px-8 py-6 bg-white text-gray-400 border-2 border-gray-50 rounded-3xl hover:text-red-500 hover:bg-red-50 hover:border-red-100 transition-all duration-300 flex items-center justify-center shadow-sm">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    </button>
                                </div>
                            @else
                                <div class="p-8 bg-gradient-to-br from-gray-900 to-gray-800 rounded-[2.5rem] shadow-2xl relative overflow-hidden group" data-aos="fade-up">
                                    <div class="absolute inset-0 opacity-10 group-hover:opacity-20 transition-opacity">
                                        <svg class="h-full w-full" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0 0l100 100M100 0L0 100" stroke="white" stroke-width="1"/></svg>
                                    </div>
                                    
                                    <div class="relative z-10 flex flex-col items-center text-center">
                                        <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white mb-4 shadow-xl rotate-3 group-hover:rotate-0 transition-transform duration-500">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m11-3V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h11l4 4V12z"/></svg>
                                        </div>
                                        <h4 class="text-white font-black uppercase tracking-[0.2em] text-xs mb-2">Ready to purchase?</h4>
                                        <p class="text-gray-400 text-sm mb-6 max-w-[200px] font-medium leading-relaxed">Join our community to unlock exclusive pricing.</p>
                                        <a href="{{ route('login') }}" class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] transition-all shadow-lg shadow-indigo-900/20">
                                            Sign In to Store →
                                        </a>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    console.log('Produk detail page loaded');
</script>