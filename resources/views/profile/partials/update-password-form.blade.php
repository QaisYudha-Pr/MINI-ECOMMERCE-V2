<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <x-input-label for="update_password_current_password" :value="__('Current Password')" class="ml-1 text-[11px] font-bold uppercase tracking-widest text-gray-400 mb-2" />
                <x-text-input id="update_password_current_password" name="current_password" type="password" class="w-full bg-gray-50 border-2 border-gray-50 focus:border-emerald-500 focus:bg-white rounded-2xl px-5 py-3.5 text-sm font-bold transition-all outline-none" autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="update_password_password" :value="__('New Password')" class="ml-1 text-[11px] font-bold uppercase tracking-widest text-gray-400 mb-2" />
                <x-text-input id="update_password_password" name="password" type="password" class="w-full bg-gray-50 border-2 border-gray-50 focus:border-emerald-500 focus:bg-white rounded-2xl px-5 py-3.5 text-sm font-bold transition-all outline-none" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="ml-1 text-[11px] font-bold uppercase tracking-widest text-gray-400 mb-2" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full bg-gray-50 border-2 border-gray-50 focus:border-emerald-500 focus:bg-white rounded-2xl px-5 py-3.5 text-sm font-bold transition-all outline-none" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="px-8 py-4 bg-slate-900 text-white rounded-2xl text-[11px] font-bold uppercase tracking-[0.2em] hover:bg-black transition-all shadow-xl">
                SIMPAN PASSWORD
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-[11px] font-bold text-emerald-600 uppercase tracking-widest"
                >{{ __('Berhasil Diperbarui.') }}</p>
            @endif
        </div>
    </form>
</section>

