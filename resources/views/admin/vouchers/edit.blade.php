<x-admin-layout>
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.vouchers.index') }}" class="p-3 bg-white border border-slate-200 rounded-2xl hover:bg-slate-50 transition-colors">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Voucher 🎟️</h1>
                <p class="text-slate-500 text-sm">Update voucher <span class="font-mono font-bold text-emerald-600">{{ $voucher->code }}</span></p>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST" class="bg-white rounded-2xl border border-slate-100 shadow-xl p-8 space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Kode Voucher --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2">Kode Voucher *</label>
                    <input type="text" name="code" value="{{ old('code', $voucher->code) }}" placeholder="DISKON50" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-mono uppercase focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all @error('code') border-rose-500 @enderror" required>
                    @error('code')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Nama --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2">Nama Voucher *</label>
                    <input type="text" name="name" value="{{ old('name', $voucher->name) }}" placeholder="Diskon Spesial 50%" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all @error('name') border-rose-500 @enderror" required>
                    @error('name')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-2">Deskripsi</label>
                <textarea name="description" rows="2" placeholder="Deskripsi singkat voucher..." class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all resize-none">{{ old('description', $voucher->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Tipe --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2">Tipe Voucher *</label>
                    <select name="type" id="voucher-type" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" required>
                        <option value="percentage" {{ old('type', $voucher->type) === 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                        <option value="fixed" {{ old('type', $voucher->type) === 'fixed' ? 'selected' : '' }}>Potongan Harga (Rp)</option>
                        <option value="free_shipping" {{ old('type', $voucher->type) === 'free_shipping' ? 'selected' : '' }}>Gratis Ongkir</option>
                    </select>
                </div>

                {{-- Nilai --}}
                <div id="value-container">
                    <label class="block text-xs font-bold text-slate-500 mb-2"><span id="value-label">Nilai</span> *</label>
                    <input type="number" name="value" value="{{ old('value', $voucher->value) }}" placeholder="50" step="0.01" min="0" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all @error('value') border-rose-500 @enderror" required>
                    @error('value')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Maks Diskon (for percentage) --}}
                <div id="max-discount-container">
                    <label class="block text-xs font-bold text-slate-500 mb-2">Maks. Diskon (Rp)</label>
                    <input type="number" name="max_discount" value="{{ old('max_discount', $voucher->max_discount) }}" placeholder="100000" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Min Purchase --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2">Min. Pembelian (Rp)</label>
                    <input type="number" name="min_purchase" value="{{ old('min_purchase', $voucher->min_purchase) }}" placeholder="50000" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                </div>

                {{-- Usage Limit --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2">Batas Penggunaan</label>
                    <input type="number" name="usage_limit" value="{{ old('usage_limit', $voucher->usage_limit) }}" placeholder="100 (kosong = unlimited)" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                </div>

                {{-- Per User Limit --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2">Per User *</label>
                    <input type="number" name="per_user_limit" value="{{ old('per_user_limit', $voucher->per_user_limit) }}" placeholder="1" min="1" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Start Date --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2">Mulai Berlaku</label>
                    <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $voucher->starts_at?->format('Y-m-d\TH:i')) }}" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                </div>

                {{-- End Date --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2">Berakhir</label>
                    <input type="datetime-local" name="expires_at" value="{{ old('expires_at', $voucher->expires_at?->format('Y-m-d\TH:i')) }}" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                </div>
            </div>

            {{-- Status --}}
            <div class="flex items-center gap-4 p-6 bg-slate-50 rounded-2xl">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $voucher->is_active) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                </label>
                <div>
                    <span class="font-bold text-slate-900">Voucher Aktif</span>
                    <p class="text-xs text-slate-500">Nonaktifkan jika ingin pause sementara</p>
                </div>
            </div>

            {{-- Usage Stats --}}
            <div class="p-6 bg-emerald-50 rounded-2xl">
                <div class="text-xs font-bold text-emerald-500 mb-2">Statistik Penggunaan</div>
                <div class="flex gap-8">
                    <div>
                        <span class="text-2xl font-semibold text-emerald-600">{{ $voucher->usage_count }}</span>
                        <span class="text-emerald-400">x digunakan</span>
                    </div>
                    @if($voucher->usage_limit)
                    <div>
                        <span class="text-slate-500">dari batas</span>
                        <span class="text-2xl font-bold text-slate-900">{{ $voucher->usage_limit }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end gap-4 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.vouchers.index') }}" class="px-8 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition-colors">Batal</a>
                <button type="submit" class="px-8 py-4 bg-emerald-600 text-white rounded-2xl font-bold hover:bg-emerald-700 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Update Voucher
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        const typeSelect = document.getElementById('voucher-type');
        const valueLabel = document.getElementById('value-label');
        const maxDiscountContainer = document.getElementById('max-discount-container');
        const valueContainer = document.getElementById('value-container');

        function updateForm() {
            const type = typeSelect.value;
            if (type === 'percentage') {
                valueLabel.textContent = 'Persentase (%)';
                maxDiscountContainer.style.display = 'block';
                valueContainer.style.display = 'block';
            } else if (type === 'fixed') {
                valueLabel.textContent = 'Jumlah Potongan (Rp)';
                maxDiscountContainer.style.display = 'none';
                valueContainer.style.display = 'block';
            } else {
                maxDiscountContainer.style.display = 'none';
                valueContainer.style.display = 'none';
            }
        }

        typeSelect.addEventListener('change', updateForm);
        updateForm();
    </script>
    @endpush
</x-admin-layout>

