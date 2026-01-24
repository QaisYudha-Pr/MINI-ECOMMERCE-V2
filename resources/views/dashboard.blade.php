<x-admin-layout>
    <div class="space-y-6">
        {{-- Welcome Section --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-8 border border-slate-100 relative">
             <div class="relative z-10">
                <h3 class="text-2xl font-bold text-slate-900">Welcome, {{ Auth::user()->name }} 👋</h3>
                <p class="text-slate-500 mt-2">Here is what's happening with your account today.</p>
            </div>
            <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-indigo-50 to-transparent hidden md:block"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Metrics/Stats or Action Cards --}}
            @if(Auth::user()->hasRole('seller') || Auth::user()->hasRole('admin'))
                <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                     <!-- Card: Total Products -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 flex items-center justify-between">
                        <div>
                            <div class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Total Products</div>
                            <div class="text-2xl font-black text-slate-900">{{ $totalItem }}</div>
                        </div>
                        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                    </div>

                    <!-- Card: Total Earnings (Status Pendapatan) -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 flex items-center justify-between transition-transform hover:scale-[1.02]">
                        <div>
                            <div class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Total Pendapatan</div>
                            <div class="text-2xl font-black text-indigo-600">RP {{ number_format($totalEarnings, 0, ',', '.') }}</div>
                        </div>
                        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>

                    @if(Auth::user()->hasRole('admin'))
                        <!-- Card: Pending Sellers (Admin Only) -->
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 flex items-center justify-between group hover:border-[#00AA5B] transition-all cursor-pointer" onclick="window.location.href='{{ route('admin.sellers.index') }}'">
                            <div>
                                <div class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1 text-[#00AA5B]">Request Seller</div>
                                <div class="text-2xl font-black text-slate-900">{{ \App\Models\User::where('seller_status', 'pending')->count() }}</div>
                            </div>
                            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-[#00AA5B] group-hover:bg-[#00AA5B] group-hover:text-white transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                            </div>
                        </div>
                    @else
                        <!-- Card: Orders (Seller Only) -->
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 flex items-center justify-between">
                            <div>
                                <div class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Pesanan Sukses</div>
                                <div class="text-2xl font-black text-slate-900">{{ \App\Models\Transaction::where('status', 'success')->count() }}</div>
                            </div>
                            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Quick Action (Responsive adjustment) --}}
                <div class="bg-indigo-600 rounded-2xl p-6 text-white flex flex-col justify-between relative overflow-hidden h-full">
                     <div class="relative z-10">
                        <h4 class="font-bold text-lg mb-2">New Product</h4>
                        <p class="text-indigo-100 text-sm mb-6">Tambahkan Produk baru dalam tokomu.</p>
                        <a href="{{ route('item-shop.create') }}" class="inline-flex w-full items-center justify-center px-4 py-3 bg-white text-indigo-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-colors shadow-lg">
                            + Add Item
                        </a>
                     </div>
                     <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
                </div>
            @endif
        </div>

        @if(Auth::user()->hasRole('admin'))
        {{-- Revenue Dashboard Detailed Widgets --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pb-12">
            
            {{-- Widget: Revenue Timeline --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="font-bold text-slate-900">Garis Waktu Pendapatan</h4>
                    <span class="text-[10px] font-black uppercase text-slate-400">7 Hari Terakhir</span>
                </div>
                <div class="space-y-4">
                    @forelse($revenueTimeline as $item)
                        <div class="flex items-center justify-between p-3 bg-slate-50/50 rounded-xl border border-slate-100/50">
                            <div class="text-sm font-bold text-slate-700">{{ $item->date }}</div>
                            <div class="text-sm font-black text-indigo-600">RP {{ number_format($item->total, 0, ',', '.') }}</div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400 text-xs italic">Belum ada data pendapatan.</div>
                    @endforelse
                </div>
            </div>

            {{-- Widget: Top Best Sell In Market --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm transition-all hover:shadow-md">
                 <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="font-black text-slate-900 text-lg tracking-tight">Top Best Sell</h4>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Market Performance</p>
                    </div>
                    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 rounded-lg">
                        <span class="text-[10px] font-black uppercase text-orange-600 tracking-wider">🔥 Trending</span>
                    </div>
                </div>

                {{-- Enhanced Category Filter Bar --}}
                <div class="flex gap-3 overflow-x-auto no-scrollbar mb-8">
                    <a href="{{ route('dashboard', ['category' => 'all']) }}" 
                       class="px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-[0.15em] transition-all border-2 {{ $selectedCategory == 'all' ? 'bg-indigo-600 border-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-white border-slate-100 text-slate-400 hover:border-indigo-200 hover:text-indigo-500' }}">
                        All Items
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('dashboard', ['category' => $cat]) }}" 
                           class="px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-[0.15em] transition-all border-2 {{ strtolower($selectedCategory) == strtolower($cat) ? 'bg-indigo-600 border-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-white border-slate-100 text-slate-400 hover:border-indigo-200 hover:text-indigo-500' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>

                <div class="space-y-6">
                    @forelse($revenueByOffer as $name => $data)
                        <div class="group">
                            <div class="flex flex-col mb-2">
                                <div class="flex justify-between items-end">
                                    <div class="flex flex-col">
                                        @if($data['kategori'])
                                            <span class="text-[8px] font-black text-indigo-500 uppercase tracking-[0.2em] mb-1">{{ $data['kategori'] }}</span>
                                        @endif
                                        <span class="text-sm font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">{{ $name }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-black text-slate-900 block">RP {{ number_format($data['total'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="w-full bg-slate-50 rounded-full h-2 mt-3 overflow-hidden border border-slate-100/50">
                                    <div class="bg-indigo-600 h-full rounded-full transition-all duration-1000" 
                                         style="width: {{ ($data['total'] / $maxRevenue) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                         <div class="py-12 flex flex-col items-center justify-center bg-slate-50/50 rounded-3xl border-2 border-dashed border-slate-100">
                             <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center text-slate-300 mb-3">
                                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                             </div>
                             <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Belum ada penjualan<br>di kategori ini</p>
                         </div>
                    @endforelse
                </div>
            </div>

            {{-- Widget: Revenue by Customer --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                 <div class="flex items-center justify-between mb-6">
                    <h4 class="font-bold text-slate-900">Pendapatan oleh Pelanggan</h4>
                    <span class="text-[10px] font-black uppercase text-green-500">Loyal Customers</span>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($revenueByCustomer as $customer)
                        <div class="py-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold">
                                    {{ substr($customer->user->name, 0, 1) }}
                                </div>
                                <span class="text-xs font-bold text-slate-700">{{ $customer->user->name }}</span>
                            </div>
                            <span class="text-xs font-black text-slate-900">RP {{ number_format($customer->total, 0, ',', '.') }}</span>
                        </div>
                    @empty
                         <div class="py-8 text-center text-slate-400 text-xs italic">Belum ada data pelanggan.</div>
                    @endforelse
                </div>
            </div>

            {{-- Widget: Geographical (Placeholder/Status) --}}
             <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                 <div class="flex items-center justify-between mb-6">
                    <h4 class="font-bold text-slate-900">Penyebaran Geografis</h4>
                    <span class="text-[10px] font-black uppercase text-slate-400">Status Pengiriman</span>
                </div>
                <div class="flex flex-col items-center justify-center py-6">
                    <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600 mb-4 animate-pulse">
                         <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    </div>
                    <p class="text-xs font-bold text-slate-600">Layanan Aktif: Mojokerto</p>
                    <p class="text-[10px] text-slate-400 mt-1">Sistem Sedang Menganalisis Titik Pengiriman...</p>
                </div>
            </div>

        </div>
        @endif
    </div>
</x-admin-layout>
