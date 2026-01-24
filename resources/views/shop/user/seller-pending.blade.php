<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-[2.5rem] shadow-xl border border-gray-100 text-center">
            <div class="flex justify-center">
                <div class="w-24 h-24 bg-yellow-50 rounded-full flex items-center justify-center text-yellow-500 animate-pulse">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            
            <div>
                <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tight">Pendaftaran Diproses</h2>
                <p class="mt-4 text-sm text-gray-500 font-medium leading-relaxed">
                    Halo, <span class="font-bold text-gray-800">{{ Auth::user()->name }}</span>! Pendaftaran toko <span class="text-[#00AA5B] font-bold">"{{ Auth::user()->nama_toko }}"</span> sedang kami validasi.
                </p>
                <div class="mt-6 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Estimasi Waktu</p>
                    <p class="text-xs font-bold text-gray-700 mt-1">1x24 Jam Kerja</p>
                </div>
            </div>

            <div class="pt-6">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-black text-gray-400 uppercase tracking-widest hover:text-[#00AA5B] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali Ke Beranda
                </a>
            </div>
        </div>
    </div>
</x-app-layout>