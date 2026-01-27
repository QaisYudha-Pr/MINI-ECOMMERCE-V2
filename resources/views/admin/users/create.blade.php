<x-admin-layout title="Tambah User">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- BREADCRUMB --}}
        <nav class="flex mb-4" data-aos="fade-down">
            <ol class="inline-flex items-center space-x-3 text-xs font-bold uppercase tracking-widest text-gray-400">
                <li><a href="{{ route('users.index') }}" class="hover:text-indigo-600">Users</a></li>
                <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                    </svg></li>
                <li class="text-indigo-600">Tambah User</li>
            </ol>
        </nav>

        {{-- HEADER --}}
        <div class="mb-10" data-aos="fade-down">
            <h1 class="text-4xl font-black text-gray-900 tracking-tight">Create <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">New User Account</span></h1>
            <p class="text-gray-500 mt-2 font-medium">Buat akun baru dan tentukan hak akses secara spesifik.</p>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-2xl border border-gray-100 overflow-hidden" data-aos="zoom-in-up">
            <form action="{{ route('users.store') }}" method="POST" class="p-8 sm:p-12 space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- NAME --}}
                    <div class="group">
                        <label class="block text-sm font-black text-gray-700 uppercase tracking-widest mb-2 ml-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe"
                            class="w-full px-5 py-4 bg-gray-50 border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-600 outline-none transition" required>
                        @error('name') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>

                    {{-- EMAIL --}}
                    <div class="group">
                        <label class="block text-sm font-black text-gray-700 uppercase tracking-widest mb-2 ml-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="john@example.com"
                            class="w-full px-5 py-4 bg-gray-50 border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-600 outline-none transition" required>
                        @error('email') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>

                    {{-- ROLE (Spatie) --}}
                    <div class="group">
                        <label class="block text-sm font-black text-gray-700 uppercase tracking-widest mb-2 ml-1">Role Akses</label>
                        <select name="role" id="role_select" class="w-full px-5 py-4 bg-gray-50 border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-600 outline-none transition font-bold text-gray-700 cursor-pointer">
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User / Buyer</option>
                            <option value="seller" {{ old('role') == 'seller' ? 'selected' : '' }}>Seller</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                        @error('role') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- PERMISSIONS CHECKLIST --}}
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-xl font-black text-gray-900 flex items-center gap-3">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Permissions Management
                        </h3>
                        <p class="text-sm text-gray-500 mt-2 ml-9">Role yang dipilih akan otomatis mencentang permission yang sesuai di bawah.</p>
                    </div>

                    <div class="bg-gray-50 rounded-[2rem] p-8 border border-gray-100">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @php
                            $allPermissions = \Spatie\Permission\Models\Permission::all();
                            $userPermissions = old('permissions', []);
                            @endphp

                            @foreach($allPermissions as $permission)
                            <label class="permission-item flex items-center gap-4 p-5 bg-white rounded-2xl border-2 border-transparent hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-50/50 transition-all cursor-pointer group shadow-sm">
                                <div class="relative flex items-center">
                                    <input type="checkbox"
                                        name="permissions[]"
                                        value="{{ $permission->name }}"
                                        data-permission="{{ $permission->name }}"
                                        {{ in_array($permission->name, $userPermissions) ? 'checked' : '' }}
                                        class="permission-checkbox w-6 h-6 text-indigo-600 border-gray-200 rounded-lg focus:ring-indigo-500 cursor-pointer transition-all">
                                </div>
                                <div class="flex-1">
                                    <div class="font-black text-[11px] uppercase tracking-widest text-gray-800 transition-colors group-hover:text-indigo-600">
                                        {{ str_replace('-', ' ', $permission->name) }}
                                    </div>
                                    <div class="text-[9px] font-bold text-gray-400 mt-0.5 group-hover:text-indigo-300">{{ $permission->name }}</div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-gray-50 pt-8">
                    {{-- PASSWORD --}}
                    <div class="group">
                        <label class="block text-sm font-black text-gray-700 uppercase tracking-widest mb-2 ml-1">Password</label>
                        <input type="password" name="password" class="w-full px-5 py-4 bg-gray-50 border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-600 outline-none transition" required>
                    </div>

                    {{-- CONFIRM PASSWORD --}}
                    <div class="group">
                        <label class="block text-sm font-black text-gray-700 uppercase tracking-widest mb-2 ml-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="w-full px-5 py-4 bg-gray-50 border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-600 outline-none transition" required>
                    </div>
                </div>

                {{-- BUTTONS --}}
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <a href="{{ route('users.index') }}" class="flex-1 text-center px-8 py-4 border-2 border-gray-100 text-gray-500 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="flex-[2] px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl shadow-xl font-black uppercase tracking-widest text-xs active:scale-95 transition-all">
                        Simpan User Baru
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role_select');
            const checkboxes = document.querySelectorAll('.permission-checkbox');

            const rolePermissions = {
                'admin': ['tambah-user', 'edit-user', 'hapus-user', 'lihat-user', 'tambah-produk', 'edit-produk', 'hapus-produk', 'lihat-produk', 'edit-permissions', 'lihat-permissions', 'membeli-produk'],
                'seller': ['tambah-produk', 'edit-produk', 'hapus-produk', 'lihat-produk', 'membeli-produk'],
                'user': ['lihat-produk', 'membeli-produk']
            };

            function updatePermissions() {
                const selectedRole = roleSelect.value;
                const allowed = rolePermissions[selectedRole] || [];

                checkboxes.forEach(cb => {
                    const name = cb.getAttribute('data-permission');
                    if (allowed.includes(name)) {
                        cb.checked = true;
                        cb.closest('.permission-item').classList.add('border-indigo-600', 'bg-indigo-50/30');
                        cb.closest('.permission-item').classList.remove('border-transparent', 'bg-white');
                    } else {
                        cb.checked = false;
                        cb.closest('.permission-item').classList.remove('border-indigo-600', 'bg-indigo-50/30');
                        cb.closest('.permission-item').classList.add('border-transparent', 'bg-white');
                    }
                });
            }

            roleSelect.addEventListener('change', updatePermissions);
            
            // Trigger on initial load if role is already selected (e.g., from old input)
            if (roleSelect.value) {
                updatePermissions();
            }
        });
    </script>
    @endpush
</x-app-layout>