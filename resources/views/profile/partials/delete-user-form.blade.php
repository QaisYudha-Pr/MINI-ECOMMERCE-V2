<section class="space-y-6">
    <div class="p-6 bg-red-50 rounded-2xl border border-red-100">
        <p class="text-[11px] font-bold text-red-600 leading-relaxed">
            {{ __('PERINGATAN: Sekali akun dihapus, semua data (transaksi, produk, ulasan) akan hilang selamanya bolo. Pastikan kamu sudah yakin.') }}
        </p>
    </div>

    <button type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-8 py-4 bg-red-600 text-white rounded-2xl text-xs font-semibold hover:bg-red-700 transition-all shadow-xl shadow-red-100"
    >
        {{ __('HAPUS AKUN SAYA') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-10">
            @csrf
            @method('delete')

            <h2 class="text-xl font-bold text-gray-900">
                {{ __('KONFIRMASI HAPUS') }}
            </h2>

            <p class="mt-4 text-sm text-gray-500 font-medium">
                {{ __('Silakan masukkan password akun kamu untuk mengonfirmasi penghapusan akun secara permanen.') }}
            </p>

            <div class="mt-8">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full bg-gray-50 border-2 border-gray-50 focus:border-red-500 focus:bg-white rounded-2xl px-5 py-3.5 text-sm font-bold transition-all outline-none"
                    placeholder="{{ __('Masukkan Password Kamu') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-10 flex gap-4">
                <button type="button" x-on:click="$dispatch('close')" class="flex-1 py-4 bg-gray-100 text-gray-400 rounded-2xl font-bold uppercase text-xs tracking-widest hover:bg-gray-200 transition-all">
                    {{ __('BATAL') }}
                </button>

                <button type="submit" class="flex-1 py-4 bg-red-600 text-white rounded-2xl font-semibold uppercase text-xs tracking-widest hover:bg-red-700 transition-all shadow-lg shadow-red-100">
                    {{ __('IYA, HAPUS PERMANEN') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>

