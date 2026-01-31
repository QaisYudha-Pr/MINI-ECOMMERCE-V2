<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Courier Panel - {{ config('app.name') }}</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800,900" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-16 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
                </div>
                <span class="font-black uppercase tracking-tighter text-lg">Courier<span class="text-indigo-600">Panel</span></span>
            </div>
            
            <div class="flex items-center gap-4">
                {{-- Global Notifications --}}
                <div x-data="{ 
                    open: false,
                    unreadCount: {{ auth()->user()->notifications()->where('is_read', false)->count() }},
                    readAll() {
                        if(this.unreadCount > 0) {
                            fetch('{{ route('notifications.read-all') }}', { 
                                method: 'POST', 
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } 
                            }).then(() => this.unreadCount = 0);
                        }
                    }
                }">
                    <div class="relative">
                        <button @click="open = !open; if(open) readAll()"
                            class="relative p-2.5 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all group">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <template x-if="unreadCount > 0">
                                <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-rose-500 border-2 border-white rounded-full animate-pulse"></span>
                            </template>
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="absolute right-0 mt-3 w-80 bg-white border border-gray-100 shadow-2xl rounded-[2rem] overflow-hidden z-[100]">
                            <div class="p-5 border-b border-gray-50 bg-gray-50/50">
                                <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-900">Notifikasi</h3>
                            </div>
                            <div class="max-h-96 overflow-y-auto no-scrollbar p-2">
                                @forelse(auth()->user()->notifications()->latest()->limit(5)->get() as $notif)
                                    <div class="p-4 rounded-2xl hover:bg-gray-50 transition-colors {{ !$notif->is_read ? 'bg-indigo-50/30' : '' }}">
                                        <h4 class="text-[10px] font-black text-gray-900 leading-tight uppercase tracking-tight mb-1">{{ $notif->title }}</h4>
                                        <p class="text-[9px] text-gray-500 font-bold leading-relaxed">{{ $notif->message }}</p>
                                        <span class="text-[8px] font-black text-gray-400 uppercase tracking-tighter mt-2 block">{{ $notif->created_at->diffForHumans() }}</span>
                                    </div>
                                @empty
                                    <div class="py-10 text-center">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tidak ada kabar bolo</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Account Settings Link --}}
                <a href="{{ route('profile.edit') }}" class="p-2.5 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all group" title="Kelola Profil">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs font-black uppercase text-red-500 hover:text-red-700 transition-colors">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    @stack('scripts')

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000,
            customClass: { popup: 'rounded-[1.5rem]' }
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Waduh!',
            text: "{{ session('error') }}",
            showConfirmButton: false,
            timer: 2500,
            customClass: { popup: 'rounded-[1.5rem]' }
        });
    </script>
    @endif
</body>
</html>
