<x-admin-layout>
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Validasi Seller Baru</h2>
                    <p class="text-sm text-gray-500 font-medium mt-1">Terdapat {{ $pendingSellers->count() }} Permohonan Menunggu</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-8 py-6 text-xs font-semibold text-gray-400">User & Toko</th>
                            <th class="px-8 py-6 text-xs font-semibold text-gray-400">Dokumen KTP</th>
                            <th class="px-8 py-6 text-xs font-semibold text-gray-400">Tanggal Daftar</th>
                            <th class="px-8 py-6 text-xs font-semibold text-gray-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($pendingSellers as $seller)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl overflow-hidden border-2 border-white shadow-md">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($seller->name) }}&color=00AA5B&background=EBF4FF" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ $seller->name }}</p>
                                            <p class="text-xs font-semibold text-emerald-600 mt-0.5">"{{ $seller->nama_toko }}"</p>
                                            <div class="flex flex-col gap-0.5 mt-1">
                                                <p class="text-xs text-gray-400">{{ $seller->email }}</p>
                                                <p class="text-xs font-bold text-slate-500">{{ $seller->phone ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    @if($seller->seller_document)
                                        <a href="{{ asset($seller->seller_document) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl text-xs font-semibold hover:bg-emerald-100 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat Dokumen
                                        </a>
                                    @else
                                        <span class="text-xs font-bold text-red-400">Tidak Ada Foto</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-xs font-bold text-gray-600">{{ $seller->created_at->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $seller->created_at->format('H:i') }} WIB</p>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <form action="{{ route('admin.sellers.approve', $seller->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.sellers.reject', $seller->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Tolak pendaftaran ini?')" class="p-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-24 text-center">
                                    <div class="max-w-xs mx-auto text-gray-400">
                                        <svg class="w-16 h-16 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-sm font-bold">Belum ada permohonan baru</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>

