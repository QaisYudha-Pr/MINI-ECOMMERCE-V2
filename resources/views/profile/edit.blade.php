<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3" data-aos="fade-right">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <h2 class="font-black text-xl text-gray-800 leading-tight uppercase tracking-widest">
                Account <span class="text-indigo-600">Settings</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f8fafc]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- KIRI: PROFILE CARD & AVATAR --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white/80 backdrop-blur-md p-8 rounded-[2.5rem] shadow-2xl shadow-gray-200/50 border border-white text-center" data-aos="fade-up">
                        <div class="relative inline-block group">
                            <div class="w-40 h-40 rounded-[2.5rem] overflow-hidden border-4 border-white shadow-xl bg-gray-100 mx-auto">
                                <img id="avatar-preview" 
                                     src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=4f46e5&color=fff' }}" 
                                     class="w-full h-full object-cover transition-transform group-hover:scale-110">
                            </div>
                            
                            <label for="avatar-input" class="absolute bottom-2 right-2 w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center cursor-pointer shadow-lg hover:bg-indigo-700 transition-all border-2 border-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" stroke-width="2"/><path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"/></svg>
                            </label>
                        </div>

                        <div class="mt-6">
                            <h3 class="text-xl font-black text-gray-900 tracking-tight">{{ auth()->user()->name }}</h3>
                            <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest mt-1">{{ auth()->user()->roles->first()->name ?? 'Member' }}</p>
                        </div>

                        <div class="mt-8 pt-8 border-t border-gray-50 flex justify-around">
                            <div class="text-center">
                                <span class="block text-lg font-black text-gray-900 leading-none">12</span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Orders</span>
                            </div>
                            <div class="text-center">
                                <span class="block text-lg font-black text-gray-900 leading-none">05</span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Reviews</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KANAN: FORMS --}}
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white/80 backdrop-blur-md p-6 sm:p-10 rounded-[2.5rem] shadow-2xl shadow-gray-200/50 border border-white" data-aos="fade-up" data-aos-delay="100">
                        <div class="max-w-2xl">
                            <div class="mb-8">
                                <h3 class="text-lg font-black text-gray-900 uppercase tracking-widest">Personal <span class="text-indigo-600">Information</span></h3>
                                <p class="text-sm text-gray-500 mt-1 font-medium">Update data diri dan alamat email anda.</p>
                            </div>
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur-md p-6 sm:p-10 rounded-[2.5rem] shadow-2xl shadow-gray-200/50 border border-white" data-aos="fade-up" data-aos-delay="200">
                        <div class="max-w-2xl">
                            <div class="mb-8">
                                <h3 class="text-lg font-black text-gray-900 uppercase tracking-widest">Secure <span class="text-indigo-600">Password</span></h3>
                                <p class="text-sm text-gray-500 mt-1 font-medium">Gunakan password yang kuat untuk keamanan akun.</p>
                            </div>
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <div class="bg-red-50/50 backdrop-blur-md p-6 sm:p-10 rounded-[2.5rem] border border-red-100 shadow-xl shadow-red-100/20" data-aos="fade-up" data-aos-delay="300">
                        <div class="max-w-2xl">
                            <div class="mb-8">
                                <h3 class="text-lg font-black text-red-600 uppercase tracking-widest">Danger <span class="text-red-900">Zone</span></h3>
                                <p class="text-sm text-red-400 mt-1 font-medium">Sekali dihapus, data akun tidak dapat dikembalikan.</p>
                            </div>
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="avatar-form" action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
        @csrf
        @method('PATCH')
        <input type="file" name="avatar" id="avatar-input" accept="image/*" onchange="submitAvatar()">
    </form>

    <script>
        function submitAvatar() {
            document.getElementById('avatar-form').submit();
        }
    </script>
</x-app-layout> 