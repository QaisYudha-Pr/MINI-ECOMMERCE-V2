<x-admin-layout>
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-8 border border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
             <div>
                <h3 class="text-2xl font-bold text-slate-900">Penarikan Saldo 💸</h3>
                <p class="text-slate-500 mt-2">Kelola permintaan pencairan dana hasil jualanmu bolo.</p>
            </div>
            @if(Auth::user()->hasRole('seller'))
            <button onclick="document.getElementById('modal-withdraw').classList.remove('hidden')" 
                class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">
                Tarik Saldo Baru
            </button>
            @endif
        </div>

        {{-- Stats for Seller --}}
        @if(Auth::user()->hasRole('seller'))
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-slate-900 p-6 rounded-2xl border border-slate-800 shadow-xl">
                <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Saldo Saat Ini</div>
                <div class="text-2xl font-black text-white">RP {{ number_format(Auth::user()->balance, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <div class="text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Total Ditarik</div>
                <div class="text-2xl font-black text-slate-900">RP {{ number_format($withdrawals->where('status', 'completed')->sum('amount'), 0, ',', '.') }}</div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <div class="text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Menunggu Review</div>
                <div class="text-2xl font-black text-amber-600">RP {{ number_format($withdrawals->where('status', 'pending')->sum('amount'), 0, ',', '.') }}</div>
            </div>
        </div>
        @endif

        {{-- Requests Table --}}
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                <h4 class="text-lg font-black text-slate-900 uppercase tracking-tighter">Riwayat Penarikan</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Informasi</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Nominal</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Bank / Rekening</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                            @if(Auth::user()->hasRole('admin'))
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($withdrawals as $w)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <div class="text-sm font-bold text-slate-900">{{ $w->created_at->format('d M Y') }}</div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase">#WD-{{ str_pad($w->id, 5, '0', STR_PAD_LEFT) }}</div>
                                @if(Auth::user()->hasRole('admin'))
                                <div class="mt-1 text-xs text-indigo-600 font-bold">{{ $w->user->name }}</div>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="text-sm font-black text-slate-900">RP {{ number_format($w->amount, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-xs font-bold text-slate-700">{{ $w->bank_name }}</div>
                                <div class="text-[11px] text-slate-500 font-mono">{{ $w->account_number }}</div>
                                <div class="text-[10px] text-slate-400 uppercase font-black">{{ $w->account_name }}</div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                @php
                                    $colors = [
                                        'pending' => 'bg-amber-100 text-amber-700',
                                        'approved' => 'bg-blue-100 text-blue-700',
                                        'rejected' => 'bg-rose-100 text-rose-700',
                                        'completed' => 'bg-emerald-100 text-emerald-700',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tight {{ $colors[$w->status] ?? 'bg-slate-100' }}">
                                    {{ $w->status }}
                                </span>
                                @if($w->admin_note)
                                <p class="mt-1 text-[10px] text-slate-400 leading-tight">Note: {{ $w->admin_note }}</p>
                                @endif
                            </td>
                            @if(Auth::user()->hasRole('admin'))
                            <td class="px-8 py-6 text-center">
                                <button onclick="openAdminModal('{{ $w->id }}', '{{ $w->status }}', '{{ $w->admin_note }}')" 
                                    class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Review</button>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-10 text-center">
                                <p class="text-slate-400 font-bold text-sm italic">Belum ada riwayat penarikan bolo.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Withdraw (Seller) --}}
    <div id="modal-withdraw" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="this.parentElement.parentElement.classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.withdrawals.store') }}" method="POST">
                    @csrf
                    <div class="p-8">
                        <h3 class="text-xl font-black text-slate-900 mb-6">Tarik Dana Bolo 🚀</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Nominal (Min. 10.000)</label>
                                <input type="number" name="amount" min="10000" max="{{ Auth::user()->balance }}" required 
                                    class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-indigo-500" placeholder="Berapa bolo?">
                                <p class="mt-1 text-[10px] text-slate-400 font-bold">Tersedia: RP {{ number_format(Auth::user()->balance, 0, ',', '.') }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Nama Bank</label>
                                    <input type="text" name="bank_name" required class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-indigo-500" placeholder="Contoh: BCA / DANA">
                                </div>
                                <div class="col-span-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">No. Rekening</label>
                                    <input type="text" name="account_number" required class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-indigo-500" placeholder="1234567...">
                                </div>
                                <div class="col-span-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Atas Nama</label>
                                    <input type="text" name="account_name" required class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-indigo-500" placeholder="Nama asli bolo">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 p-8 flex gap-3">
                        <button type="button" onclick="document.getElementById('modal-withdraw').classList.add('hidden')" class="flex-1 py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl font-black text-[10px] uppercase tracking-widest">Batal</button>
                        <button type="submit" class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-indigo-100">Kirim Permintaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Admin (Review) --}}
    @if(Auth::user()->hasRole('admin'))
    <div id="modal-admin" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="this.parentElement.parentElement.classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="admin-form" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="p-8">
                        <h3 class="text-xl font-black text-slate-900 mb-6">Review Withdraw Bolo ✅</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Update Status</label>
                                <select name="status" id="admin-status" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved (Diproses)</option>
                                    <option value="completed">Completed (Sudah Transfer)</option>
                                    <option value="rejected">Rejected (Ditolak)</option>
                                </select>
                            </div>

                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Catatan Admin</label>
                                <textarea name="admin_note" id="admin-note" rows="3" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-sm font-bold" placeholder="Kasih tau alasannya bolo..."></textarea>
                            </div>

                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Bukti Transfer (Jika Selesai)</label>
                                <input type="file" name="reference_proof" class="w-full bg-slate-50 border-0 rounded-2xl p-4 text-xs">
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 p-8 flex gap-3">
                        <button type="button" onclick="document.getElementById('modal-admin').classList.add('hidden')" class="flex-1 py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl font-black text-[10px] uppercase tracking-widest">Tutup</button>
                        <button type="submit" class="flex-1 py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-slate-200">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAdminModal(id, status, note) {
            const modal = document.getElementById('modal-admin');
            const form = document.getElementById('admin-form');
            const statusSelect = document.getElementById('admin-status');
            const noteText = document.getElementById('admin-note');

            form.action = `/admin/withdrawals/${id}`;
            statusSelect.value = status;
            noteText.value = note || '';
            
            modal.classList.remove('hidden');
        }
    </script>
    @endif
</x-admin-layout>
