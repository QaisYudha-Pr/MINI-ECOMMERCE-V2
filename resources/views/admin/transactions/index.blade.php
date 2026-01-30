<x-admin-layout>
    <div class="py-12 bg-[#F8FAFC] min-h-screen">
        <div class="max-w-7xl mx-auto px-6">
            {{-- Modern Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                <div>
                    <h2 class="text-4xl font-black text-gray-900 tracking-tighter uppercase mb-2">
                        Order <span class="text-indigo-600">Management</span>
                    </h2>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.3em]">Kelola semua pesanan masuk bolo</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="px-5 py-3 bg-white rounded-2xl border border-gray-100 shadow-sm flex items-center gap-3">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">System Online</span>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                @if($transactions->isEmpty())
                <div class="py-20 flex flex-col items-center justify-center bg-white rounded-[3rem] border-2 border-dashed border-slate-200 shadow-xl shadow-slate-100">
                    <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center text-slate-300 mb-6 group hover:scale-110 transition-transform duration-500">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Belum Ada Pesanan, Bolo</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] mt-2">Semua pesanan yang masuk akan muncul di sini.</p>
                </div>
                @endif

                @foreach($transactions as $trx)
                <div class="group bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white overflow-hidden hover:border-indigo-100 transition-all duration-500">
                    {{-- Transaction Header Strip --}}
                    <div class="bg-gray-50/50 border-b border-gray-50 px-8 py-6 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex items-center gap-6">
                            <div class="w-14 h-14 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center text-indigo-600 rotate-3 group-hover:rotate-0 transition-transform">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-2 py-0.5 rounded-md">Invoice</span>
                                    <span class="text-lg font-black text-gray-900 tracking-tight">{{ $trx->invoice_number }}</span>
                                </div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Dipesan pada {{ $trx->created_at->format('d M Y • H:i') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-amber-50 text-amber-600 border-amber-200 shadow-sm shadow-amber-100 animate-pulse-slow',
                                    'success' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'shipped' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'completed' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                    'failed' => 'bg-rose-50 text-rose-600 border-rose-100',
                                    'waiting_confirmation' => 'bg-purple-50 text-purple-600 border-purple-100',
                                ];
                                
                                $statusIcons = [
                                    'pending' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                    'success' => 'M5 13l4 4L19 7',
                                    'shipped' => 'M13 10V3L4 14h7v7l9-11h-7z',
                                    'completed' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                    'waiting_confirmation' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                ];
                            @endphp
                            <span class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest border {{ $statusClasses[$trx->status] ?? 'bg-gray-50 text-gray-500 border-gray-100' }}">
                                @if(isset($statusIcons[$trx->status]))
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $statusIcons[$trx->status] }}"></path></svg>
                                @endif
                                {{ str_replace('_', ' ', $trx->status) }}
                            </span>
                            <div class="h-8 w-px bg-gray-200 hidden md:block"></div>
                            <div class="text-right">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Total Pembayaran</p>
                                <p class="text-xl font-black text-gray-900 tracking-tighter">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 grid lg:grid-cols-12 gap-10">
                        {{-- Customer & Items --}}
                        <div class="lg:col-span-8 space-y-8">
                            {{-- Info Customer --}}
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-slate-100 border border-white shadow-sm flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-tight">{{ $trx->user->name }}</h4>
                                    <p class="text-[11px] text-gray-500 font-medium mt-1 leading-relaxed max-w-md italic">{{ $trx->alamat }}</p>
                                </div>
                            </div>

                            {{-- Grouped Items --}}
                            <div class="grid sm:grid-cols-2 gap-4">
                                @php
                                    $groupedItems = collect($trx->items_details)->groupBy(fn($i) => $i['seller_id'] ?? 'unknown');
                                @endphp
                                @foreach($groupedItems as $sellerId => $items)
                                    @php
                                        $isMyShop = $sellerId == auth()->id();
                                        $firstItem = $items->first();
                                        $shopName = $firstItem['seller_name'] ?? 'Toko';
                                    @endphp
                                    <div class="relative bg-white rounded-3xl p-5 border-2 {{ $isMyShop ? 'border-indigo-600 bg-indigo-50/10' : 'border-gray-50' }} transition-colors">
                                        @if($isMyShop)
                                            <div class="absolute -top-3 left-6 px-3 py-1 bg-indigo-600 text-white rounded-full text-[8px] font-black uppercase tracking-widest shadow-lg shadow-indigo-100">Barang Toko Saya</div>
                                        @endif
                                        <div class="flex items-center gap-2 mb-4">
                                            <div class="w-2 h-2 rounded-full {{ $isMyShop ? 'bg-indigo-600' : 'bg-gray-300' }}"></div>
                                            <span class="text-[10px] font-black uppercase text-gray-400 tracking-tighter line-clamp-1 italic">{{ $shopName }}</span>
                                        </div>
                                        
                                        <div class="space-y-3">
                                            @foreach($items as $item)
                                                <div class="flex items-center gap-3">
                                                    <img src="{{ asset($item['gambar'] ?? 'image/default-product.jpg') }}" class="w-12 h-12 rounded-xl object-cover shadow-sm bg-gray-50 border border-white">
                                                    <div class="min-w-0">
                                                        <p class="text-[11px] font-black text-gray-800 line-clamp-1 lowercase first-letter:uppercase">{{ $item['nama_barang'] ?? $item['name'] ?? 'Product' }}</p>
                                                        <p class="text-[10px] font-bold text-gray-400 uppercase mt-0.5">Rp {{ number_format($item['harga'] ?? $item['price'] ?? 0, 0, ',', '.') }} <span class="text-indigo-600">x{{ $item['quantity'] ?? $item['qty'] ?? 1 }}</span></p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Final Column: Courier & Action --}}
                        <div class="lg:col-span-4 bg-gray-50/50 rounded-3xl p-8 border border-gray-100 flex flex-col justify-between">
                            @if($trx->status == 'success')
                                <div class="space-y-6">
                                    <div class="text-center">
                                        <h5 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Pilih Opsi Pengiriman</h5>
                                        
                                        <form action="{{ route('admin.transactions.assign', $trx->id) }}" method="POST" class="space-y-3">
                                            @csrf
                                            <select name="courier_id" required class="w-full bg-white border-gray-200 rounded-2xl py-4 px-6 text-[11px] font-black uppercase tracking-widest focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all cursor-pointer">
                                                <option value="">PILIH KURIR ({{ $trx->courierService->name ?? 'SEMUA' }})...</option>
                                                @foreach($couriers->where('courier_agency_id', $trx->courier_service_id) as $courier)
                                                    <option value="{{ $courier->id }}">{{ strmax($courier->name, 20) }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">Assign Kurir</button>
                                        </form>
                                    </div>

                                    <div class="relative flex items-center justify-center">
                                        <div class="absolute inset-0 flex items-center"><span class="w-full border-t border-gray-200"></span></div>
                                        <span class="relative bg-[#fdfdfe] px-4 text-[8px] font-black text-gray-300 uppercase tracking-widest">Atau Manual</span>
                                    </div>

                                    <form action="{{ route('admin.transactions.resi', $trx->id) }}" method="POST" class="space-y-3">
                                        @csrf
                                        <input type="text" name="resi" placeholder="NOMOR RESI EKSPEDISI..." required
                                            class="w-full bg-white border-gray-200 rounded-2xl py-4 px-6 text-[11px] font-black uppercase tracking-widest focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-center">
                                        <button type="submit" class="w-full py-4 bg-gray-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-black transition-all">Submit Resi</button>
                                    </form>
                                </div>
                            @elseif(in_array($trx->status, ['shipped', 'delivered', 'completed']))
                                <div class="space-y-4">
                                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group">
                                        <div class="absolute -right-4 -top-4 w-12 h-12 bg-emerald-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 relative">Logistik Terpilih</p>
                                        <p class="text-sm font-black text-gray-900 uppercase relative">{{ $trx->courier->name ?? 'Ekspedisi Luar' }}</p>
                                        <div class="mt-4 pt-4 border-t border-gray-50">
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 relative">Nomor Resi / AWB</p>
                                            <p class="text-base font-black text-indigo-600 font-mono tracking-tighter relative">{{ $trx->resi }}</p>
                                        </div>
                                    </div>
                                    @if($trx->status == 'shipped')
                                        <p class="text-[9px] font-bold text-slate-400 italic text-center leading-relaxed">Menunggu konfirmasi penerimaan oleh pembeli bolo.</p>
                                    @endif
                                </div>
                            @else
                                <div class="h-full flex flex-col items-center justify-center text-center p-6 bg-white/50 rounded-2xl border border-dashed border-gray-200">
                                    <svg class="w-10 h-10 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest leading-loose">Menunggu Pembayaran<br>dari Bolo Customer</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
