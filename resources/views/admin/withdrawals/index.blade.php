<x-admin-layout>
    <div class="space-y-6">
        {{-- Header Section (Bolo Style) --}}
        <div class="relative bg-slate-900 overflow-hidden shadow-2xl sm:rounded-[3rem] p-10 group transition-all duration-500">
             <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div>
                    <span class="inline-block px-4 py-1.5 rounded-full bg-indigo-500/20 backdrop-blur-md text-indigo-400 text-[10px] font-black uppercase tracking-[0.2em] mb-4 border border-indigo-500/20">
                        Keuangan Bolo
                    </span>
                    <h1 class="text-4xl lg:text-5xl font-black text-white tracking-tighter leading-none mb-4">
                        @if(Auth::user()->hasRole('admin'))
                            Manajemen <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-indigo-200">Payout</span> 💸
                        @else
                            Penarikan <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-indigo-200">Saldo</span> 💸
                        @endif
                    </h1>
                    <p class="text-slate-400 text-sm font-medium leading-relaxed max-w-md">
                        @if(Auth::user()->hasRole('admin'))
                            Verifikasi dan proses permintaan pencairan dana seller tepat waktu bolo!
                        @else
                            Kelola hasil jualanmu dengan bijak bolo. Jangan lupa sedekah biar makin berkah!
                        @endif
                    </p>
                </div>
                @if(Auth::user()->hasRole('seller'))
                <button onclick="document.getElementById('modal-withdraw').classList.remove('hidden')" 
                    class="px-10 py-5 bg-indigo-600 text-white rounded-[2rem] font-black text-[11px] uppercase tracking-[0.2em] hover:bg-white hover:text-slate-900 transition-all shadow-2xl shadow-indigo-500/20 active:scale-95 flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Tarik Saldo Baru
                </button>
                @endif
            </div>
            
            {{-- Abstract Graphics --}}
            <div class="absolute right-[-10%] top-[-10%] w-96 h-96 bg-indigo-600/20 rounded-full blur-[120px] group-hover:bg-indigo-600/30 transition-all duration-1000"></div>
            <div class="absolute left-[20%] bottom-[-20%] w-72 h-72 bg-indigo-400/10 rounded-full blur-[100px]"></div>
        </div>

        {{-- Stats for Seller (Bolo Style) --}}
        @if(Auth::user()->hasRole('seller'))
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Available Balance Card (The Star) --}}
            <div class="relative bg-slate-900 p-8 rounded-[2.5rem] border border-white/5 shadow-2xl overflow-hidden group hover:-translate-y-1 transition-all">
                <div class="relative z-10">
                    <div class="text-indigo-400 text-[10px] font-black uppercase tracking-[0.2em] mb-2 flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></div>
                        Saldo Tersedia
                    </div>
                    <div class="text-3xl font-black text-white leading-none">RP {{ number_format(Auth::user()->balance, 0, ',', '.') }}</div>
                    <p class="mt-4 text-[9px] font-bold text-slate-500 uppercase tracking-widest leading-relaxed">Dana siap ditarik ke rekeningmu kapan saja bolo.</p>
                </div>
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-indigo-600/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 flex flex-col justify-center group hover:shadow-2xl transition-all">
                <div class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Total Telah Cair</div>
                <div class="text-3xl font-black text-slate-900 leading-none">RP {{ number_format($withdrawals->where('status', 'completed')->sum('amount'), 0, ',', '.') }}</div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 flex flex-col justify-center group hover:shadow-2xl transition-all">
                <div class="text-amber-500 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Sedang Diproses</div>
                <div class="text-3xl font-black text-slate-900 leading-none">RP {{ number_format($withdrawals->where('status', 'pending')->sum('amount'), 0, ',', '.') }}</div>
            </div>
        </div>
        @endif

        {{-- Requests Table (Bolo Style) --}}
        <div class="bg-white rounded-[3rem] border border-slate-100 shadow-2xl shadow-slate-200/50 overflow-hidden">
            <div class="p-10 border-b border-slate-50 flex items-center justify-between">
                <div>
                    <h4 class="text-xl font-black text-slate-900 uppercase tracking-tighter">
                        @if(Auth::user()->hasRole('admin'))
                            Daftar Request Payout
                        @else
                            Riwayat Penarikan
                        @endif
                    </h4>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">
                        @if(Auth::user()->hasRole('admin'))
                            Kelola semua permintaan pencairan dana dari seller bolo.
                        @else
                            Pantau status pencairan danamu bolo.
                        @endif
                    </p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu & Kode</th>
                            <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Nominal</th>
                            <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tujuan Bank</th>
                            <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                            @if(Auth::user()->hasRole('admin'))
                            <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($withdrawals as $w)
                        <tr class="hover:bg-slate-50/80 transition-all group">
                            <td class="px-10 py-8">
                                <div class="text-sm font-black text-slate-900">{{ $w->created_at->format('d M, Y') }}</div>
                                <div class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mt-1">#WD-{{ str_pad($w->id, 5, '0', STR_PAD_LEFT) }}</div>
                                @if(Auth::user()->hasRole('admin'))
                                <div class="mt-2 inline-flex items-center px-2 py-1 bg-slate-100 rounded-lg text-[10px] text-slate-600 font-black uppercase tracking-tighter">{{ $w->user->name }}</div>
                                @endif
                            </td>
                            <td class="px-10 py-8 text-right">
                                <div class="text-md font-black text-slate-900">RP {{ number_format($w->amount, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-10 py-8">
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-slate-800 uppercase tracking-tight">{{ $w->bank_name }}</span>
                                    <span class="text-[11px] font-bold text-slate-500 font-mono tracking-tighter">{{ $w->account_number }}</span>
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">{{ $w->account_name }}</span>
                                </div>
                            </td>
                            <td class="px-10 py-8 text-center">
                                @php
                                    $colors = [
                                        'pending' => 'bg-amber-100 text-amber-700 ring-amber-200/50',
                                        'approved' => 'bg-blue-100 text-blue-700 ring-blue-200/50',
                                        'rejected' => 'bg-rose-100 text-rose-700 ring-rose-200/50',
                                        'completed' => 'bg-emerald-100 text-emerald-700 ring-emerald-200/50',
                                    ];
                                @endphp
                                <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest ring-4 {{ $colors[$w->status] ?? 'bg-slate-100' }}">
                                    {{ $w->status }}
                                </span>
                                @if($w->admin_note)
                                <p class="mt-3 text-[9px] font-bold text-slate-400 leading-tight bg-slate-50 p-2 rounded-lg italic">"{{ $w->admin_note }}"</p>
                                @endif
                            </td>
                            @if(Auth::user()->hasRole('admin'))
                            <td class="px-10 py-8 text-center">
                                <button onclick="openAdminModal('{{ $w->id }}', '{{ $w->status }}', '{{ $w->admin_note }}')" 
                                    class="px-5 py-2.5 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-lg active:scale-95">
                                    Review
                                </button>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-10 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center text-slate-200 mb-4">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p class="text-slate-400 font-black text-[10px] uppercase tracking-[0.2em]">
                                        @if(Auth::user()->hasRole('admin'))
                                            Belum ada permintaan penarikan yang masuk bolo.
                                        @else
                                            Belum ada riwayat penarikan bolo.
                                        @endif
                                    </p>
                                </div>
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
            <div class="inline-block align-bottom bg-white rounded-[3rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.withdrawals.store') }}" method="POST">
                    @csrf
                    <div class="p-10">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-indigo-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <h3 class="text-2xl font-black text-slate-900 tracking-tighter uppercase">Tarik Dana <span class="text-indigo-600">Bolo</span> 🚀</h3>
                        </div>
                        
                        <div class="space-y-6">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-3">Nominal Tarik</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-slate-400">RP</span>
                                    <input type="number" name="amount" min="10000" max="{{ Auth::user()->balance }}" required 
                                        class="w-full bg-slate-50 border-2 border-slate-50 rounded-[1.5rem] py-5 pl-12 pr-6 text-lg font-black text-slate-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all" placeholder="0">
                                </div>
                                <div class="mt-3 flex items-center justify-between px-2">
                                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Saldo Tersedia</p>
                                    <p class="text-[10px] text-indigo-600 font-black uppercase tracking-widest">RP {{ number_format(Auth::user()->balance, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-2">Nama Bank / E-Wallet</label>
                                    <input type="text" name="bank_name" required class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-4 text-sm font-black text-slate-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all uppercase" placeholder="Contoh: BCA / DANA / MANDIRI">
                                </div>
                                <div class="col-span-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-2">No. Rekening</label>
                                    <input type="text" name="account_number" required class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-4 text-sm font-black text-slate-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-mono" placeholder="xxx-xxx-xxx">
                                </div>
                                <div class="col-span-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-2">Atas Nama</label>
                                    <input type="text" name="account_name" required class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-4 text-sm font-black text-slate-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all uppercase" placeholder="Nama asli bolo">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50/50 p-10 flex gap-4">
                        <button type="button" onclick="document.getElementById('modal-withdraw').classList.add('hidden')" class="flex-1 py-5 bg-white border-2 border-slate-100 text-slate-400 rounded-[1.5rem] font-black text-[10px] uppercase tracking-[0.2em] hover:bg-slate-100 hover:text-slate-600 transition-all">Batal</button>
                        <button type="submit" class="flex-1 py-5 bg-indigo-600 text-white rounded-[1.5rem] font-black text-[10px] uppercase tracking-[0.2em] shadow-2xl shadow-indigo-200 hover:bg-slate-900 transition-all">Kirim Permintaan</button>
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

                            <div x-data="{ hasProof: false, preview: '' }">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Bukti Transfer (Jika Selesai)</label>
                                <div class="relative">
                                    <input type="file" name="reference_proof" id="reference_proof_input" 
                                        class="hidden" accept="image/*" 
                                        onchange="initWithdrawCrop(this)">
                                    
                                    <template x-if="!hasProof">
                                        <button type="button" onclick="document.getElementById('reference_proof_input').click()" 
                                            class="w-full py-6 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition-all flex items-center justify-center gap-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            <span>Pilih Bukti Transfer</span>
                                        </button>
                                    </template>

                                    <template x-if="hasProof">
                                        <div class="relative w-full h-40 rounded-2xl overflow-hidden group">
                                            <img :src="preview" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                                <button type="button" onclick="document.getElementById('reference_proof_input').click()" class="p-2 bg-white text-indigo-600 rounded-xl">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                </button>
                                                <button type="button" @click="hasProof = false; preview = ''; document.getElementById('reference_proof_input').value = ''" class="p-2 bg-red-500 text-white rounded-xl">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
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

    {{-- MODAL CROP WITHDRAWAL --}}
    <div id="withdrawCropModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-lg overflow-hidden shadow-2xl relative animate-fade-in-up uppercase">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-black text-gray-900 tracking-tight">POTONG <span class="text-indigo-600">BUKTI TRANSFER</span></h3>
                <button type="button" onclick="closeWithdrawCrop()" class="text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6">
                <div class="max-h-[50vh] overflow-hidden rounded-2xl border-2 border-dashed border-gray-200">
                    <img id="withdrawImageToCrop" src="" class="max-w-full block">
                </div>
            </div>
            <div class="p-6 bg-gray-50/50 border-t border-gray-50 flex gap-4">
                <button type="button" onclick="closeWithdrawCrop()" class="flex-1 py-4 bg-white text-gray-500 rounded-2xl font-black uppercase text-[10px] tracking-widest border border-gray-100">Batal</button>
                <button type="button" onclick="applyWithdrawCrop()" class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-indigo-100">Simpan Bukti</button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let withdrawCropper = null;

        function initWithdrawCrop(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const image = document.getElementById('withdrawImageToCrop');
                    image.src = e.target.result;
                    
                    document.getElementById('withdrawCropModal').classList.remove('hidden');
                    document.getElementById('withdrawCropModal').classList.add('flex');
                    
                    if (withdrawCropper) withdrawCropper.destroy();
                    
                    setTimeout(() => {
                        withdrawCropper = new Cropper(image, {
                            aspectRatio: 1,
                            viewMode: 1,
                            dragMode: 'move',
                            autoCropArea: 0.8,
                            background: true,
                            responsive: true,
                            restore: true,
                            checkOrientation: true,
                            modal: true,
                            guides: true,
                            center: true,
                            highlight: true,
                            cropBoxMovable: true,
                            cropBoxResizable: true,
                            toggleDragModeOnDblclick: true,
                            movable: true,
                            zoomable: true,
                        });
                    }, 500); 
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function closeWithdrawCrop() {
            document.getElementById('withdrawCropModal').classList.add('hidden');
            document.getElementById('withdrawCropModal').classList.remove('flex');
            if (withdrawCropper) {
                withdrawCropper.destroy();
                withdrawCropper = null;
            }
        }

        function applyWithdrawCrop() {
            if (!withdrawCropper) return;

            const canvas = withdrawCropper.getCroppedCanvas({ width: 800, height: 800 });
            canvas.toBlob(blob => {
                const file = new File([blob], 'receipt.jpg', { type: 'image/jpeg' });
                const container = new DataTransfer();
                container.items.add(file);
                
                const input = document.getElementById('reference_proof_input');
                input.files = container.files;

                // Update UI using Alpine
                const alpineEl = input.closest('[x-data]');
                if (alpineEl && alpineEl.__x) {
                    alpineEl.__x.$data.hasProof = true;
                    alpineEl.__x.$data.preview = canvas.toDataURL('image/jpeg');
                }

                closeWithdrawCrop();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Bukti Siap Bolo!',
                    text: 'Silahkan update status penarikan.',
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }, 'image/jpeg');
        }
    </script>
    @endpush
</x-admin-layout>
