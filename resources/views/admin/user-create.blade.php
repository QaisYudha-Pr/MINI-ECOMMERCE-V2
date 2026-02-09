<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- HEADER --}}
        <div class="mb-10" data-aos="fade-down">
            <h1 class="text-4xl font-bold text-gray-900">Add <span class="text-emerald-600">New User</span></h1>
            <p class="text-gray-500 mt-2 font-medium">Buat akun baru dan tentukan hak aksesnya.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden" data-aos="zoom-in-up">
            <form action="{{ route('users.store') }}" method="POST" class="p-8 sm:p-12 space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- NAME --}}
                    <div class="group">
                        <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe"
                            class="w-full px-5 py-4 bg-gray-50 border-gray-200 rounded-2xl focus:ring-2 focus:ring-emerald-600 outline-none transition" required>
                        @error('name') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>

                    {{-- EMAIL --}}
                    <div class="group">
                        <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="john@example.com"
                            class="w-full px-5 py-4 bg-gray-50 border-gray-200 rounded-2xl focus:ring-2 focus:ring-emerald-600 outline-none transition" required>
                        @error('email') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>

                    {{-- ROLE (Spatie) --}}
                    <div class="group">
                        <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Role Akses</label>
                        <select name="role" class="w-full px-5 py-4 bg-gray-50 border-gray-200 rounded-2xl focus:ring-2 focus:ring-emerald-600 outline-none transition font-bold text-gray-700 cursor-pointer">
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User / Buyer</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                        @error('role') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-gray-50 pt-8">
                    {{-- PASSWORD --}}
                    <div class="group">
                        <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Password</label>
                        <input type="password" name="password" class="w-full px-5 py-4 bg-gray-50 border-gray-200 rounded-2xl focus:ring-2 focus:ring-emerald-600 outline-none transition" required>
                    </div>

                    {{-- CONFIRM PASSWORD --}}
                    <div class="group">
                        <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="w-full px-5 py-4 bg-gray-50 border-gray-200 rounded-2xl focus:ring-2 focus:ring-emerald-600 outline-none transition" required>
                    </div>
                </div>

                {{-- BUTTONS --}}
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <a href="{{ route('users.index') }}" class="flex-1 text-center px-8 py-4 border-2 border-gray-100 text-gray-500 rounded-2xl font-semibold text-xs hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="flex-[2] px-8 py-4 bg-gradient-to-r from-emerald-600 to-purple-600 text-white rounded-2xl shadow-xl font-semibold text-xs active:scale-95 transition-all">
                        Simpan User Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

