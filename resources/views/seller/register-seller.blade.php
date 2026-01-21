<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden flex flex-col md:flex-row">
                <!-- Left Side: Hero/Image -->
                <div class="w-full md:w-1/2 bg-slate-900 p-12 text-white flex flex-col justify-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-indigo-600/20 to-transparent"></div>
                    <div class="relative z-10">
                        <h2 class="text-3xl font-bold mb-4 tracking-tight">Become a Seller</h2>
                        <p class="text-slate-300 text-sm leading-relaxed mb-8">
                            Join our exclusive network of creators and merchants. Unlock the ability to list products, manage inventory, and grow your business with our premium tools.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-center gap-3 text-sm font-medium">
                                <span class="bg-indigo-500/20 p-1 rounded-full text-indigo-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                Unlimited Product Listings
                            </li>
                            <li class="flex items-center gap-3 text-sm font-medium">
                                <span class="bg-indigo-500/20 p-1 rounded-full text-indigo-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                Advanced Analytics
                            </li>
                            <li class="flex items-center gap-3 text-sm font-medium">
                                <span class="bg-indigo-500/20 p-1 rounded-full text-indigo-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                Secure Payments
                            </li>
                        </ul>
                    </div>
                    <!-- Decorative Circles -->
                    <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-indigo-600 rounded-full blur-3xl opacity-20"></div>
                </div>

                <!-- Right Side: Action -->
                <div class="w-full md:w-1/2 p-12 flex flex-col justify-center bg-white">
                    <div class="mb-8">
                        <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Get Started</span>
                        <h3 class="text-2xl font-bold text-slate-900 mt-2">Activate Seller Account</h3>
                    </div>

                    <form action="{{ route('seller.store') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="nama_toko" class="block text-sm font-semibold text-slate-700 mb-2">Shop Name (Nama Toko)</label>
                            <input type="text" name="nama_toko" id="nama_toko" required
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none text-slate-900 placeholder-slate-400"
                                placeholder="e.g., Qais Digital Store">
                            @error('nama_toko')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-indigo-50 p-6 rounded-2xl border border-indigo-100 mb-8">
                            <div class="flex items-start gap-4">
                                <div class="shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-slate-900">Confirmation Required</h4>
                                    <p class="text-xs text-slate-500 mt-1">
                                        By clicking the button below, you agree to our Seller Terms of Service and Privacy Policy. Your account will be immediately upgraded.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-4 px-6 rounded-xl transition-all shadow-lg shadow-indigo-200 active:scale-95 flex items-center justify-center gap-2">
                            <span>Register as Seller</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
