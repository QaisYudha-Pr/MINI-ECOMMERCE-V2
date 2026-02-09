<x-admin-layout>
    <div class="max-w-5xl mx-auto py-10 px-4">
        
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-600 font-bold text-sm shadow-sm animate-fade-in">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Back Button --}}
        <a href="{{ route('admin.transactions.index') }}" class="inline-flex items-center gap-2 text-emerald-600 font-bold text-xs mb-8 hover:gap-3 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Pesanan
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column: Main Detail --}}
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    {{-- Detail Header --}}
                    <div class="p-8 sm:p-10 border-b border-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 bg-slate-50/30">
                        <div>
                            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest block mb-2">Invoice Details</span>
                            <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ $transaction->invoice_number }}</h1>
                            <p class="text-xs font-bold text-slate-400 mt-2">{{ $transaction->created_at->translatedFormat('d F Y, H.i') }} WIB</p>
                        </div>
                        
                        @php
                            $statusClasses = [
                                'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                'paid' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                'shipped' => 'bg-blue-50 text-blue-600 border-blue-100',
                                'success' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                'failed' => 'bg-rose-50 text-rose-600 border-rose-100',
                                'waiting_confirmation' => 'bg-orange-50 text-orange-600 border-orange-100',
                            ];
                            $statusLabels = [
                                'pending' => 'Menunggu Pembayaran',
                                'paid' => 'Siap Dikirim',
                                'shipped' => 'Dalam Pengiriman',
                                'success' => 'Selesai',
                                'failed' => 'Gagal / Dibatalkan',
                                'waiting_confirmation' => 'Menunggu Konfirmasi COD',
                            ];
                            $currentClass = $statusClasses[$transaction->status] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                            $currentLabel = $statusLabels[$transaction->status] ?? $transaction->status;
                        @endphp
                        <div class="px-5 py-2.5 rounded-2xl border {{ $currentClass }} text-[11px] font-black uppercase tracking-tight">
                            {{ $currentLabel }}
                        </div>
                    </div>

                    {{-- Customer Info --}}
                    <div class="p-8 sm:p-10 border-b border-gray-50">
                        <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Informasi Pembeli</h2>
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">{{ $transaction->user->name }}</h3>
                                <p class="text-xs font-bold text-slate-400 mt-0.5">{{ $transaction->user->email }} / {{ $transaction->user->phone ?? 'No Phone' }}</p>
                            </div>
                        </div>
                        <div class="mt-8 p-6 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="flex items-start gap-4">
                                <svg class="w-5 h-5 text-slate-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <div>
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-tighter mb-1">Alamat Pengiriman</p>
                                    <p class="text-sm font-bold text-slate-800 leading-relaxed">{{ $transaction->alamat }}</p>
                                    @if($transaction->catatan_alamat)
                                        <p class="text-xs font-medium text-slate-500 mt-2 italic bg-white p-3 rounded-xl border border-slate-100">"{{ $transaction->catatan_alamat }}"</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Products Table --}}
                    <div class="p-8 sm:p-10">
                        <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Produk Pesanan</h2>
                        <div class="space-y-4">
                            @if(is_array($transaction->items_details))
                                @foreach ($transaction->items_details as $item)
                                    <div class="flex items-center gap-5 p-4 bg-white border border-slate-50 rounded-2xl hover:border-emerald-100 transition-all group">
                                        <div class="w-16 h-16 bg-slate-50 rounded-xl overflow-hidden border border-slate-100 flex-shrink-0">
                                            @if(isset($item['gambar']))
                                                <img src="{{ asset($item['gambar']) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-bold text-slate-900 truncate group-hover:text-emerald-600 transition-colors">{{ $item['nama_barang'] ?? ($item['name'] ?? 'Item') }}</h4>
                                            <p class="text-xs font-bold text-slate-400 mt-1">{{ $item['quantity'] ?? 1 }}x @ Rp{{ number_format($item['harga'] ?? ($item['price'] ?? 0), 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-bold text-slate-900">Rp{{ number_format(($item['harga'] ?? ($item['price'] ?? 0)) * ($item['quantity'] ?? 1), 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Side Info & Actions --}}
            <div class="space-y-8">
                {{-- Payment & Shipping Summary --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                    <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Ringkasan Pembayaran</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-bold text-slate-400">Metode</span>
                            <span class="font-bold text-slate-900 uppercase">{{ $transaction->payment_method }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-bold text-slate-400">Total Harga Barang</span>
                            <span class="font-bold text-slate-900">Rp{{ number_format($transaction->total_price - $transaction->shipping_fee - $transaction->admin_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-bold text-slate-400">Biaya Pengiriman</span>
                            <span class="font-bold text-slate-900">Rp{{ number_format($transaction->shipping_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-bold text-slate-400">Biaya Admin</span>
                            <span class="font-bold text-slate-900">Rp{{ number_format($transaction->admin_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="pt-4 border-t border-slate-50 flex justify-between items-center">
                            <span class="text-sm font-black text-slate-900">Total Akhir</span>
                            <span class="text-xl font-black text-emerald-600">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Action Panel / Shipping Management --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-8 bg-slate-900 text-white">
                        <h2 class="text-xs font-bold text-emerald-400 uppercase tracking-widest mb-1">Manajemen Pengiriman</h2>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">Update status pesanan disini bolo!</p>
                    </div>
                    <div class="p-8 space-y-6">
                        @if(in_array($transaction->status, ['paid', 'success']))
                             @php
                                $isThirdParty = in_array(strtolower($transaction->courier_name), ['jne', 'pos', 'tiki', 'j&t', 'sicepat', 'anteraja']);
                                // Filter kurir internal berdasarkan layanan yang dipilih (biar sinkron bolo!)
                                $matchedCouriers = $couriers->where('courier_agency_id', $transaction->courier_service_id);
                             @endphp

                             @if($isThirdParty)
                                <div class="p-5 bg-emerald-50 rounded-2xl border border-emerald-100">
                                    <p class="text-[11px] font-bold text-emerald-600 uppercase mb-2">Kurir Pilihan User</p>
                                    <p class="text-sm font-black text-slate-900">{{ $transaction->courier_name }} - {{ $transaction->courier_service }}</p>
                                </div>
                                <form action="{{ route('admin.transactions.resi', $transaction->id) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-400 uppercase ml-1 block mb-2">Input Nomor Resi</label>
                                        <input type="text" name="resi" required placeholder="Contoh: JNE123456789"
                                            class="w-full bg-slate-50 border-0 rounded-2xl py-4 px-5 text-sm font-bold text-slate-900 focus:ring-4 focus:ring-emerald-500/10 transition-all">
                                    </div>
                                    <button type="submit" class="w-full py-4 bg-emerald-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-emerald-100 hover:bg-slate-900 transition-all">
                                        Update Resi & Kirim
                                    </button>
                                </form>
                             @else
                                <div class="p-5 bg-blue-50 rounded-2xl border border-blue-100">
                                    <p class="text-[11px] font-bold text-blue-600 uppercase mb-2">Pilihan Pengiriman</p>
                                    <p class="text-sm font-black text-slate-900">{{ $transaction->courier_name ?? 'Ambil Sendiri / Toko' }} ({{ $transaction->courier_service ?? 'Standard' }})</p>
                                </div>
                                
                                @if($matchedCouriers->count() > 0)
                                    <form action="{{ route('admin.transactions.assign', $transaction->id) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label class="text-[10px] font-bold text-slate-400 uppercase ml-1 block mb-2">Pilih Kurir Internal ({{ $transaction->courier_service }})</label>
                                            <select name="courier_id" required class="w-full bg-slate-50 border-0 rounded-2xl py-4 px-5 text-sm font-bold text-slate-900 focus:ring-4 focus:ring-emerald-500/10 transition-all">
                                                <option value="">-- Pilih Kurir --</option>
                                                @foreach($matchedCouriers as $courier)
                                                    <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg hover:bg-emerald-600 transition-all">
                                            Tugaskan Kurir
                                        </button>
                                    </form>
                                @else
                                    <div class="p-5 bg-rose-50 rounded-2xl border border-rose-100">
                                        <p class="text-[10px] font-bold text-rose-600 text-center uppercase leading-tight">
                                            Maaf bolo, tidak ada kurir aktif untuk layanan "{{ $transaction->courier_service }}".
                                        </p>
                                    </div>
                                @endif
                                <p class="text-[10px] text-center text-slate-400 font-bold leading-tight mt-4">Pastikan personel kurir sudah dikonfigurasi layanan "{{ $transaction->courier_service }}" agar muncul di sini.</p>
                             @endif
                        @elseif($transaction->status === 'waiting_confirmation')
                            <form action="{{ route('admin.transactions.confirm-cod', $transaction->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-5 bg-orange-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg hover:bg-slate-900 transition-all">
                                    Konfirmasi Pesanan COD
                                </button>
                            </form>
                        @elseif($transaction->status === 'shipped')
                            <div class="p-6 bg-emerald-50 rounded-3xl border border-emerald-100 text-center">
                                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-emerald-600 mx-auto mb-4 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <h4 class="text-sm font-black text-emerald-700 uppercase">Sedang Dikirim</h4>
                                <p class="text-[11px] font-bold text-emerald-600/70 mt-1 whitespace-pre-wrap">Nomor Resi: {{ $transaction->resi }}</p>
                            </div>
                        @elseif($transaction->status === 'completed' || $transaction->status === 'success')
                            <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 text-center">
                                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-emerald-600 mx-auto mb-4 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <h4 class="text-sm font-black text-slate-900 uppercase">Transaksi Selesai</h4>
                                <p class="text-[11px] font-bold text-slate-400 mt-1">Pesanan telah diterima oleh pembeli.</p>
                            </div>
                        @else
                            <p class="text-center text-slate-400 font-bold text-xs py-10 italic">Belum ada tindakan yang diperlukan bolo.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
