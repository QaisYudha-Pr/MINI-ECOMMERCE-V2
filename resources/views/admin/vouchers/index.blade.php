<x-admin-layout>
    <div class="space-y-6">
        {{-- Header Section (Bolo Style) --}}
        <div class="relative bg-slate-900 overflow-hidden shadow-lg sm:rounded-2xl p-10 group transition-all duration-500">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div>
                    <span class="inline-block px-4 py-1.5 rounded-full bg-emerald-500/20 backdrop-blur-md text-emerald-400 text-xs font-semibold mb-4 border border-emerald-500/20">
                        Promo Center
                    </span>
                    <h1 class="text-4xl lg:text-5xl font-bold text-white tracking-tighter leading-none mb-4">
                        Kelola <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-emerald-200">Voucher</span> 🎟️
                    </h1>
                    <p class="text-slate-400 text-sm font-medium leading-relaxed max-w-md">
                        Buat dan kelola voucher diskon untuk menarik lebih banyak pembeli bolo!
                    </p>
                </div>
                <a href="{{ route('admin.vouchers.create') }}" 
                    class="px-10 py-5 bg-emerald-600 text-white rounded-2xl font-bold text-[11px] hover:bg-white hover:text-slate-900 transition-all shadow-lg shadow-emerald-500/20 active:scale-95 flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                    Buat Voucher
                </a>
            </div>
            
            {{-- Abstract Graphics --}}
            <div class="absolute right-[-10%] top-[-10%] w-96 h-96 bg-emerald-600/20 rounded-full blur-[120px] group-hover:bg-emerald-600/30 transition-all duration-1000"></div>
            <div class="absolute left-[20%] bottom-[-20%] w-72 h-72 bg-emerald-400/10 rounded-full blur-[100px]"></div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-lg">
                <div class="text-emerald-500 text-xs font-semibold mb-2">Total Voucher</div>
                <div class="text-3xl font-bold text-slate-900">{{ $vouchers->total() }}</div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-lg">
                <div class="text-green-500 text-xs font-semibold mb-2">Aktif</div>
                <div class="text-3xl font-bold text-slate-900">{{ $vouchers->where('is_active', true)->count() }}</div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-lg">
                <div class="text-amber-500 text-xs font-semibold mb-2">Total Penggunaan</div>
                <div class="text-3xl font-bold text-slate-900">{{ $vouchers->sum('usage_count') }}</div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-lg">
                <div class="text-rose-500 text-xs font-semibold mb-2">Kadaluarsa</div>
                <div class="text-3xl font-bold text-slate-900">{{ $vouchers->filter(fn($v) => $v->expires_at && $v->expires_at < now())->count() }}</div>
            </div>
        </div>

        {{-- Vouchers Table --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-lg shadow-slate-200/50 overflow-hidden">
            <div class="p-8 border-b border-slate-50">
                <h4 class="text-xl font-bold text-slate-900">Daftar Voucher</h4>
                <p class="text-xs font-medium text-slate-400 mt-1">Kelola semua voucher promo bolo!</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-5 text-left text-xs font-medium text-slate-400">Kode</th>
                            <th class="px-8 py-5 text-left text-xs font-medium text-slate-400">Nama</th>
                            <th class="px-8 py-5 text-left text-xs font-medium text-slate-400">Tipe</th>
                            <th class="px-8 py-5 text-left text-xs font-medium text-slate-400">Nilai</th>
                            <th class="px-8 py-5 text-center text-xs font-medium text-slate-400">Penggunaan</th>
                            <th class="px-8 py-5 text-center text-xs font-medium text-slate-400">Status</th>
                            <th class="px-8 py-5 text-center text-xs font-medium text-slate-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($vouchers as $voucher)
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <span class="font-mono font-semibold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg text-xs">{{ $voucher->code }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="font-bold text-slate-900">{{ $voucher->name }}</div>
                                @if($voucher->description)
                                <div class="text-xs text-slate-400 mt-1 truncate max-w-xs">{{ $voucher->description }}</div>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                @if($voucher->type === 'percentage')
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-xs font-semibold uppercase rounded-full">Persentase</span>
                                @elseif($voucher->type === 'fixed')
                                    <span class="px-3 py-1 bg-amber-50 text-amber-600 text-xs font-semibold uppercase rounded-full">Potongan</span>
                                @else
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-xs font-semibold uppercase rounded-full">Gratis Ongkir</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 font-bold text-slate-900">
                                @if($voucher->type === 'percentage')
                                    {{ $voucher->value }}%
                                @elseif($voucher->type === 'free_shipping')
                                    <span class="text-emerald-600">FREE</span>
                                @else
                                    Rp {{ number_format($voucher->value, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="font-bold text-slate-900">{{ $voucher->usage_count }}</span>
                                <span class="text-slate-400">/</span>
                                <span class="text-slate-500">{{ $voucher->usage_limit ?? '∞' }}</span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                @if($voucher->isValid())
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-600 text-xs font-semibold uppercase rounded-full">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-slate-100 text-slate-500 text-xs font-semibold uppercase rounded-full">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ route('admin.vouchers.toggle', $voucher) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 {{ $voucher->is_active ? 'text-green-500 hover:bg-green-50' : 'text-slate-400 hover:bg-slate-100' }} rounded-xl transition-colors" title="{{ $voucher->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $voucher->is_active ? 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z' : 'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21' }}" /></svg>
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="p-2 text-emerald-500 hover:bg-emerald-50 rounded-xl transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                    <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" class="inline" onsubmit="return confirm('Hapus voucher ini bolo?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-8 py-16 text-center">
                                <div class="text-6xl mb-4">🎟️</div>
                                <div class="text-slate-400 font-bold">Belum ada voucher bolo!</div>
                                <a href="{{ route('admin.vouchers.create') }}" class="inline-block mt-4 px-6 py-3 bg-emerald-600 text-white rounded-2xl font-bold text-sm hover:bg-emerald-700 transition-colors">Buat Voucher Pertama</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($vouchers->hasPages())
            <div class="p-8 border-t border-slate-50">
                {{ $vouchers->links() }}
            </div>
            @endif
        </div>
    </div>
</x-admin-layout>

