<x-admin-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-black text-gray-900 uppercase tracking-widest">
                    Manage <span class="text-indigo-600">Transactions</span>
                </h2>
            </div>

            <div class="bg-white rounded-[2rem] overflow-hidden shadow-sm border border-gray-100">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-900 text-white">
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em]">Invoice / Date</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em]">Customer</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em]">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em]">Amount</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em]">Courier / Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($transactions as $trx)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm">
                                <span class="font-black text-gray-900 block">{{ $trx->invoice_number }}</span>
                                <span class="text-[10px] text-gray-400 font-bold uppercase">{{ $trx->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="font-bold text-gray-600 block">{{ $trx->user->name }}</span>
                                <span class="text-[10px] text-gray-400 font-medium">{{ $trx->alamat }}</span>
                                
                                {{-- Detail Barang --}}
                                <div class="mt-2 pt-2 border-t border-gray-50">
                                    <span class="text-[9px] font-black text-indigo-400 uppercase">
                                        Barang Toko Saya:
                                    </span>
                                    @foreach($trx->items_details as $item)
                                        @php 
                                            // Cek keberadaan produk milik user yang login di dalam invoice ini
                                            $isMyItem = \App\Models\ItemShop::where('id', $item['id'])
                                                          ->where('user_id', auth()->id())->exists();
                                        @endphp
                                        @if($isMyItem)
                                            <div class="text-[10px] font-bold text-gray-700">
                                                • {{ $item['nama_barang'] ?? ($item['name'] ?? 'Item') }} ({{ $item['quantity'] ?? 1 }}x)
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-600',
                                        'success' => 'bg-green-100 text-green-600',
                                        'shipped' => 'bg-blue-100 text-blue-600',
                                        'completed' => 'bg-indigo-100 text-indigo-600',
                                        'failed' => 'bg-red-100 text-red-600',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $statusClasses[$trx->status] ?? 'bg-gray-100' }}">
                                    {{ $trx->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-black text-indigo-600">
                                Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($trx->status == 'success')
                                    <div class="flex flex-col gap-4">
                                        {{-- Assign Internal Courier --}}
                                        <form action="{{ route('admin.transactions.assign', $trx->id) }}" method="POST" class="flex flex-col gap-2">
                                            @csrf
                                            <label class="text-[9px] font-black text-gray-400 uppercase">Assign Internal Courier</label>
                                            <div class="flex gap-2">
                                                <select name="courier_id" required class="text-xs border-gray-100 rounded-lg focus:ring-indigo-500 py-1">
                                                    <option value="">Pilih Kurir...</option>
                                                    @foreach($couriers as $courier)
                                                        <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="bg-indigo-600 text-white px-3 py-1 rounded-lg text-[10px] font-black uppercase hover:bg-indigo-700 transition-colors">
                                                    Assign
                                                </button>
                                            </div>
                                        </form>

                                        <div class="relative">
                                            <div class="absolute inset-0 flex items-center"><span class="w-full border-t border-gray-100"></span></div>
                                            <div class="relative flex justify-center text-[8px] uppercase font-black text-gray-300"><span class="bg-white px-2">Atau Manual Resi</span></div>
                                        </div>

                                        {{-- Input Resi Manual --}}
                                        <form action="{{ route('admin.transactions.resi', $trx->id) }}" method="POST" class="flex flex-col gap-2">
                                            @csrf
                                            <div class="flex gap-2">
                                                <input type="text" name="resi" placeholder="Input Ekspedisi Resi..." required
                                                    class="text-xs border-gray-100 rounded-lg focus:ring-indigo-500 py-1">
                                                <button type="submit" class="bg-gray-900 text-white px-3 py-1 rounded-lg text-[10px] font-black uppercase hover:bg-gray-700 transition-colors">
                                                    Ship
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @elseif($trx->status == 'shipped' || $trx->status == 'completed')
                                    <div class="flex flex-col gap-1">
                                        @if($trx->courier)
                                            <span class="text-[10px] font-black text-indigo-600 uppercase">KURIR: {{ $trx->courier->name }}</span>
                                        @endif
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">RESI:</span>
                                        <span class="text-xs font-black text-gray-900">{{ $trx->resi }}</span>
                                    </div>
                                @else
                                    <span class="text-[10px] text-gray-300 font-black uppercase italic">N/A</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-6">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
