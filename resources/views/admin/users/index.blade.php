<x-admin-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 overflow-hidden">
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12">
            <div data-aos="fade-right" data-aos-duration="1000">
                <span class="inline-block px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-wider mb-3">
                    Admin Panel
                </span>
                <h1 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight">
                    Manage <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Users</span>
                </h1>
                <p class="text-gray-500 text-lg mt-2 font-medium">Atur hak akses dan akun pengguna MiniE-Store.</p>
            </div>

            <div data-aos="fade-left" data-aos-duration="1000">
                <a href="{{ route('users.create') }}"
                    class="group relative inline-flex items-center justify-center px-8 py-4 font-bold text-white transition-all duration-200 bg-indigo-600 rounded-2xl shadow-xl shadow-indigo-200 active:scale-95">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Tambah User Baru
                </a>
            </div>
        </div>

        {{-- TABLE AREA --}}
        <div data-aos="zoom-in-up" data-aos-duration="1000" class="bg-white rounded-[2.5rem] shadow-2xl shadow-gray-200/50 overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50/80 border-b border-gray-100">
                            <th class="px-8 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">IDENTITAS USER</th>
                            <th class="px-8 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">AKSES & ROLE</th>
                            <th class="hidden lg:table-cell px-8 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">BERGABUNG PADA</th>
                            <th class="px-8 py-6 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">PENGATURAN</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($users as $user)
                        <tr class="hover:bg-slate-50 transition-all duration-300 group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl overflow-hidden border-2 border-white shadow-md">
                                        @if($user->avatar)
                                            <img src="{{ asset($user->avatar) }}" class="w-full h-full object-cover">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=00AA5B&background=EBF4FF" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900 group-hover:text-[#00AA5B] transition-colors leading-none mb-1">{{ $user->name }}</h4>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-wrap gap-2">
                                    @php $roles = $user->getRoleNames(); @endphp
                                    @if($roles->isEmpty())
                                        <span class="px-3 py-1 bg-gray-50 text-gray-400 rounded-lg text-[9px] font-black uppercase tracking-widest border border-gray-100">User</span>
                                    @else
                                        @foreach($roles as $role)
                                        <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest shadow-sm
                                            {{ $role == 'admin' ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : 
                                               ($role == 'seller' ? 'bg-green-50 text-[#00AA5B] border border-green-100' : 'bg-slate-50 text-slate-600 border border-slate-100') }}">
                                            {{ $role }}
                                        </span>
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                            <td class="hidden lg:table-cell px-8 py-6">
                                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.1em]">{{ $user->created_at->translatedFormat('d M Y') }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex justify-end gap-2 translate-x-4 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300">
                                    <a href="{{ route('users.edit', $user->id) }}" class="p-2.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-xl transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    
                                    @if($user->id !== Auth::id())
                                    <form method="POST" action="{{ route('users.destroy', $user->id) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus user ini?')" class="p-2.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-10" data-aos="fade-up">
            {{ $users->links() }}
        </div>
    </div>
</x-admin-layout>