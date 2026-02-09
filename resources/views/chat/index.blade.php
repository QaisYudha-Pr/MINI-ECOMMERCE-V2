<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat | {{ $siteSettings['site_name'] ?? config('app.name', 'QStoress') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800,900" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png/svg" href="{{ asset('LogoQ.svg') }}" />
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #374045; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #8696a0; }
        
        /* WhatsApp-style light theme */
        .chat-wallpaper {
            background-color: #efeae2;
            background-image: 
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200' viewBox='0 0 200 200'%3E%3Cg fill='%23000' fill-opacity='0.03'%3E%3Cpath d='M20 10c0-2.2 1.8-4 4-4s4 1.8 4 4-1.8 4-4 4-4-1.8-4-4z'/%3E%3Cpath d='M60 30l6-6 6 6-6 6z'/%3E%3Cpath d='M100 15c0-1.7 1.3-3 3-3h4c1.7 0 3 1.3 3 3v4c0 1.7-1.3 3-3 3h-4c-1.7 0-3-1.3-3-3v-4z'/%3E%3Cpath d='M150 25l-5 8h10z'/%3E%3Cpath d='M30 70c0-3.3 2.7-6 6-6s6 2.7 6 6-2.7 6-6 6-6-2.7-6-6z'/%3E%3Cpath d='M80 55l4-7h8l4 7-4 7h-8z'/%3E%3Cpath d='M140 65c0-2.8 2.2-5 5-5s5 2.2 5 5-2.2 5-5 5-5-2.2-5-5z'/%3E%3Cpath d='M180 50l-4 4-4-4 4-4z'/%3E%3Cpath d='M15 120c0-1.4 1.1-2.5 2.5-2.5s2.5 1.1 2.5 2.5-1.1 2.5-2.5 2.5-2.5-1.1-2.5-2.5z'/%3E%3Cpath d='M70 110l3-5h6l3 5-3 5h-6z'/%3E%3Cpath d='M120 105c0-2.2 1.8-4 4-4s4 1.8 4 4-1.8 4-4 4-4-1.8-4-4z'/%3E%3Cpath d='M170 115l-6 6-6-6 6-6z'/%3E%3Cpath d='M45 160l5-8h10l5 8-5 8h-10z'/%3E%3Cpath d='M100 155c0-3 2.5-5.5 5.5-5.5s5.5 2.5 5.5 5.5-2.5 5.5-5.5 5.5-5.5-2.5-5.5-5.5z'/%3E%3Cpath d='M155 170c0-2 1.6-3.5 3.5-3.5s3.5 1.6 3.5 3.5-1.6 3.5-3.5 3.5-3.5-1.6-3.5-3.5z'/%3E%3Cpath d='M185 165l-3 5h-6l-3-5 3-5h6z'/%3E%3C/g%3E%3C/svg%3E");
        }
        
        /* Bubble tail for sent messages */
        .bubble-sent { position: relative; border-top-right-radius: 0 !important; }
        .bubble-sent::after {
            content: '';
            position: absolute;
            top: 0; right: -8px;
            width: 0; height: 0;
            border: 8px solid transparent;
            border-left-color: #d9fdd3;
            border-top-color: #d9fdd3;
            border-right: 0;
            border-top-right-radius: 0;
        }
        
        /* Bubble tail for received messages */
        .bubble-received { position: relative; border-top-left-radius: 0 !important; }
        .bubble-received::after {
            content: '';
            position: absolute;
            top: 0; left: -8px;
            width: 0; height: 0;
            border: 8px solid transparent;
            border-right-color: #ffffff;
            border-top-color: #ffffff;
            border-left: 0;
            border-top-left-radius: 0;
        }
        
        /* Smooth message animation */
        .msg-enter {
            animation: msgSlide 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        @keyframes msgSlide {
            from { opacity: 0; transform: translateY(12px) scale(0.96); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
    </style>
</head>
<body class="bg-[#f0f2f5] h-screen overflow-hidden">
<div class="h-screen flex overflow-hidden"
     x-data="chatApp()" x-init="init()">

    {{-- ===== LEFT SIDEBAR: Conversations List ===== --}}
    <aside class="w-full md:w-[420px] flex flex-col shrink-0 bg-white border-r border-slate-200"
           :class="activeConversation ? 'hidden md:flex' : 'flex'">
        
        {{-- Sidebar Header --}}
        <div class="px-5 py-4 bg-[#f0f2f5] shrink-0">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#00a884]/10 flex items-center justify-center">
                        <span class="text-[#00a884] font-bold text-sm">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</span>
                    </div>
                    <div>
                        <h1 class="text-base font-bold text-[#111b21] tracking-tight">Pesan</h1>
                        <span class="text-xs text-[#667781]">{{ auth()->user()->nama_toko ?? auth()->user()->name }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    <a href="{{ route('shop.public') }}" class="p-2.5 text-[#54656f] hover:text-[#111b21] hover:bg-black/5 rounded-full transition-all" title="Kembali ke Shop">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </a>
                    <a href="{{ route('dashboard') }}" class="p-2.5 text-[#54656f] hover:text-[#111b21] hover:bg-black/5 rounded-full transition-all" title="Dashboard">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </a>
                </div>
            </div>
            {{-- Search --}}
            <div class="relative">
                <input type="text" x-model="searchQuery" placeholder="Cari atau mulai chat baru" 
                       class="w-full bg-white border-none rounded-lg py-2 pl-10 pr-4 text-sm text-[#111b21] placeholder-[#667781] focus:ring-1 focus:ring-[#00a884] transition-all shadow-sm">
                <svg class="w-4 h-4 text-[#667781] absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>

        {{-- Conversations --}}
        <div class="flex-1 overflow-y-auto">
            @forelse($conversations as $conversation)
                @php
                    $other = $conversation->getOtherUser(auth()->id());
                    $unread = $conversation->getUnreadCount(auth()->id());
                @endphp
                <a href="{{ route('chat.index', ['c' => $conversation->id]) }}"
                   x-show="!searchQuery || '{{ strtolower($other->name . ' ' . ($other->nama_toko ?? '')) }}'.includes(searchQuery.toLowerCase())"
                   class="flex items-center gap-3 px-4 py-3 hover:bg-[#f5f6f6] transition-all border-b border-[#f0f2f5] cursor-pointer
                          {{ $activeConversation && $activeConversation->id === $conversation->id ? 'bg-[#ebebeb]' : '' }}">
                    <div class="relative shrink-0">
                        <img src="{{ $other->avatar ? (Str::startsWith($other->avatar, ['http://', 'https://']) ? $other->avatar : asset($other->avatar)) : 'https://ui-avatars.com/api/?name='.urlencode($other->name).'&background=00a884&color=fff&bold=true' }}" 
                             class="w-12 h-12 rounded-full object-cover">
                        @if($other->isOnline())
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-[#00a884] border-2 border-white rounded-full"></span>
                        @endif
                        @if($unread > 0)
                        <span class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-[#00a884] text-white text-[11px] font-bold rounded-full flex items-center justify-center">{{ $unread > 9 ? '9+' : $unread }}</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <h3 class="text-sm font-medium text-[#111b21] truncate">{{ $other->nama_toko ?? $other->name }}</h3>
                            @if($conversation->latestMessage)
                            <span class="text-xs {{ $unread > 0 ? 'text-[#00a884]' : 'text-[#667781]' }} whitespace-nowrap">{{ $conversation->latestMessage->created_at->diffForHumans(null, true) }}</span>
                            @endif
                        </div>
                        @if($conversation->latestMessage)
                        <p class="text-[13px] truncate mt-0.5 {{ $unread > 0 ? 'text-[#111b21] font-semibold' : 'text-[#667781]' }}">
                            @if($conversation->latestMessage->sender_id === auth()->id())<span class="text-[#667781]">Anda: </span>@endif
                            {{ $conversation->latestMessage->message }}
                        </p>
                        @endif
                    </div>
                </a>
            @empty
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-[#f0f2f5] rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-[#667781]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <p class="text-sm text-[#667781]">Belum ada percakapan</p>
                    <a href="{{ route('shop.public') }}" class="text-xs text-[#00a884] mt-2 inline-block hover:underline">Jelajahi Produk</a>
                </div>
            @endforelse
        </div>
    </aside>

    {{-- ===== RIGHT PANE: Active Chat ===== --}}
    <main class="flex-1 flex flex-col min-w-0 bg-[#efeae2]"
          :class="activeConversation ? 'flex' : 'hidden md:flex'">

        @if($activeConversation && $otherUser)
        {{-- Chat Header --}}
        <div class="px-4 py-2.5 bg-[#f0f2f5] flex items-center gap-3 shrink-0">
            {{-- Back button (mobile) --}}
            <a href="{{ route('chat.index') }}" class="md:hidden p-2 hover:bg-black/5 rounded-full transition-colors shrink-0">
                <svg class="w-5 h-5 text-[#54656f]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <img src="{{ $otherUser->avatar ? (Str::startsWith($otherUser->avatar, ['http://', 'https://']) ? $otherUser->avatar : asset($otherUser->avatar)) : 'https://ui-avatars.com/api/?name='.urlencode($otherUser->name).'&background=00a884&color=fff&bold=true' }}" 
                 class="w-10 h-10 rounded-full object-cover shrink-0">
            <div class="flex-1 min-w-0">
                <h2 class="text-sm font-medium text-[#111b21] truncate">{{ $otherUser->nama_toko ?? $otherUser->name }}</h2>
                <span class="text-[11px] {{ $otherUser->isOnline() ? 'text-[#00a884]' : 'text-[#667781]' }}">
                    @if($otherUser->isOnline())
                        Online
                    @else
                        {{ $otherUser->lastSeenFormatted() }}
                    @endif
                </span>
            </div>
            <div class="flex items-center gap-1">
                @if($otherUser->seller_rating)
                <span class="text-xs font-bold text-amber-500 flex items-center gap-0.5 mr-2">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    {{ number_format($otherUser->seller_rating, 1) }}
                </span>
                @endif
                @if($otherUser->is_top_seller)
                <span class="text-xs font-bold text-white bg-amber-500 px-1.5 py-0.5 rounded mr-2 uppercase">{{ $otherUser->getMerchantBadge()['icon'] }} {{ $otherUser->getMerchantBadge()['label'] }}</span>
                @endif
                <a href="{{ route('shop.public', ['seller_id' => $otherUser->id]) }}" class="p-2.5 hover:bg-black/5 rounded-full transition-colors shrink-0" title="Kunjungi Toko">
                    <svg class="w-5 h-5 text-[#54656f]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </a>
            </div>
        </div>

        {{-- Linked Product (if any) --}}
        @if($activeConversation->item)
        <div class="px-4 py-2 bg-white border-b border-[#f0f2f5]">
            <div class="flex items-center gap-3 p-2 bg-[#f0f2f5] rounded-lg">
                <img src="{{ $activeConversation->item->photo ? asset($activeConversation->item->photo) : asset('image/default-product.jpg') }}" class="w-10 h-10 rounded-lg object-cover">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-[#111b21] truncate">{{ $activeConversation->item->name }}</p>
                    <p class="text-xs text-[#00a884] font-bold">Rp {{ number_format($activeConversation->item->price, 0, ',', '.') }}</p>
                </div>
                <a href="{{ route('shop.show', $activeConversation->item) }}" class="text-[11px] font-bold text-[#00a884] uppercase hover:underline shrink-0">Lihat</a>
            </div>
        </div>
        @endif

        {{-- Messages Area --}}
        <div id="messages-container" class="flex-1 overflow-y-auto px-4 md:px-12 py-4 space-y-1 scroll-smooth chat-wallpaper">
            @php $lastDate = null; @endphp
            @foreach($messages as $msg)
                @php $msgDate = $msg->created_at->format('Y-m-d'); @endphp
                @if($msgDate !== $lastDate)
                    <div class="flex justify-center my-3">
                        <span class="px-4 py-1.5 bg-white/80 backdrop-blur rounded-lg text-xs font-bold text-[#667781] shadow-sm">
                            {{ $msg->created_at->translatedFormat('d M Y') }}
                        </span>
                    </div>
                    @php $lastDate = $msgDate; @endphp
                @endif

                <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }} group/msg msg-enter" data-msg-id="{{ $msg->id }}">
                    <div class="relative max-w-[70%] {{ $msg->sender_id === auth()->id() ? 'bg-[#d9fdd3] bubble-sent' : 'bg-white bubble-received' }} rounded-lg px-3 py-1.5 shadow-sm"
                         x-data="{ showMenu: false, editing: false, editText: '{{ addslashes($msg->message) }}' }">
                        
                        {{-- Context Menu Toggle (own messages only) --}}
                        @if($msg->sender_id === auth()->id())
                        <button @click.stop="showMenu = !showMenu" 
                                class="absolute top-1 right-1 opacity-0 group-hover/msg:opacity-100 transition-opacity p-1 hover:bg-black/5 rounded">
                            <svg class="w-3.5 h-3.5 text-[#667781]" fill="currentColor" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                        </button>
                        
                        {{-- Dropdown Menu --}}
                        <div x-show="showMenu" @click.away="showMenu = false" x-transition
                             class="absolute top-8 right-0 bg-white rounded-lg shadow-xl border border-slate-100 py-1 z-50 min-w-[140px]">
                            @if($msg->created_at->diffInMinutes(now()) <= 15)
                            <button @click="editing = true; showMenu = false" class="w-full text-left px-4 py-2 text-xs text-slate-700 hover:bg-[#f5f6f6] flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </button>
                            @endif
                            <button @click="deleteMsg({{ $msg->id }}, $el.closest('[data-msg-id]')); showMenu = false" class="w-full text-left px-4 py-2 text-xs text-rose-500 hover:bg-[#f5f6f6] flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus
                            </button>
                        </div>
                        @endif

                        {{-- Message Body --}}
                        <div x-show="!editing">
                            <p class="text-[13px] text-[#111b21] whitespace-pre-wrap leading-relaxed pr-6">{{ $msg->message }}</p>
                            <div class="flex items-center justify-end gap-1 mt-0.5 -mb-0.5">
                                @if($msg->edited_at)<span class="text-[11px] text-[#667781]">diedit</span>@endif
                                <span class="text-xs text-[#667781]">{{ $msg->created_at->format('H:i') }}</span>
                                @if($msg->sender_id === auth()->id())
                                    @if($msg->is_read)
                                        <svg class="w-4 h-4 text-[#53bdeb]" fill="currentColor" viewBox="0 0 24 24"><path d="M18 7l-1.41-1.41-6.34 6.34 1.41 1.41L18 7zm4.24-1.41L11.66 16.17 7.48 12l-1.41 1.41L11.66 19l12-12-1.42-1.41zM.41 13.41L6 19l1.41-1.41L1.83 12 .41 13.41z"/></svg>
                                    @else
                                        <svg class="w-3.5 h-3.5 text-[#667781]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                @endif
                            </div>
                        </div>

                        {{-- Edit Form --}}
                        <div x-show="editing" x-cloak class="min-w-[200px]">
                            <textarea x-model="editText" rows="2" 
                                      class="w-full text-[13px] bg-white text-[#111b21] border border-[#00a884] rounded-lg p-2 focus:ring-1 focus:ring-[#00a884] resize-none"
                                      @keydown.enter.prevent="saveEdit({{ $msg->id }}, editText, $el.closest('[data-msg-id]')); editing = false"
                                      @keydown.escape="editing = false"></textarea>
                            <div class="flex items-center gap-2 mt-1">
                                <button @click="saveEdit({{ $msg->id }}, editText, $el.closest('[data-msg-id]')); editing = false" class="text-xs text-[#00a884] hover:underline">Simpan</button>
                                <button @click="editing = false" class="text-xs text-[#667781] hover:underline">Batal</button>
                                <span class="text-[11px] text-[#667781] ml-auto">Enter = Simpan</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Message Input --}}
        <div class="px-4 py-3 bg-[#f0f2f5] shrink-0 border-t border-slate-200">
            <form @submit.prevent="sendMsg()" class="flex items-end gap-3">
                <div class="flex-1 relative">
                    <textarea x-model="newMessage" rows="1" 
                              placeholder="Ketik pesan..." 
                              @keydown.enter.prevent="if(!$event.shiftKey) sendMsg()"
                              @input="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 120) + 'px'"
                              class="w-full bg-white border-none rounded-lg py-3 px-4 text-sm text-[#111b21] placeholder-[#667781] focus:ring-1 focus:ring-[#00a884] transition-all resize-none shadow-sm"
                              style="min-height: 44px; max-height: 120px;"></textarea>
                </div>
                <button type="submit" :disabled="!newMessage.trim()"
                        class="p-3 bg-[#00a884] text-white rounded-full hover:bg-[#06cf9c] transition-all disabled:opacity-30 disabled:cursor-not-allowed shrink-0 shadow-sm shadow-[#00a884]/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </button>
            </form>
        </div>

        @else
        {{-- Empty State (no conversation selected) --}}
        <div class="flex-1 flex items-center justify-center bg-[#f0f2f5] border-l border-slate-200">
            <div class="text-center max-w-sm px-8">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                    <svg class="w-12 h-12 text-[#bac0c4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-[#41525d]">{{ $siteSettings['site_name'] ?? 'QStoress' }} Chat</h3>
                <p class="text-sm text-[#667781] mt-2 leading-relaxed">Pilih percakapan di samping atau mulai chat baru dari halaman produk</p>
                <a href="{{ route('shop.public') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-2.5 bg-[#00a884] text-white text-xs font-bold rounded-lg hover:bg-[#06cf9c] transition-all shadow-lg shadow-[#00a884]/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Jelajahi Produk
                </a>
            </div>
        </div>
        @endif
    </main>
</div>

<script>
function chatApp() {
    return {
        activeConversation: {{ $activeConversation ? $activeConversation->id : 'null' }},
        newMessage: '',
        searchQuery: '',
        lastMsgId: {{ $messages->isNotEmpty() ? $messages->last()->id : 0 }},
        pollInterval: null,
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.content || '',

        init() {
            this.$nextTick(() => {
                const container = document.getElementById('messages-container');
                if (container) container.scrollTop = container.scrollHeight;
            });

            // Start polling if conversation active
            if (this.activeConversation) {
                this.pollInterval = setInterval(() => this.pollNewMessages(), 4000);
            }
        },

        async sendMsg() {
            const msg = this.newMessage.trim();
            if (!msg || !this.activeConversation) return;
            this.newMessage = '';

            // Optimistic UI
            const container = document.getElementById('messages-container');
            const tempId = 'temp-' + Date.now();
            const el = document.createElement('div');
            el.className = 'flex justify-end group/msg';
            el.id = tempId;
            el.innerHTML = `
                <div class="relative max-w-[70%] bg-[#d9fdd3] bubble-sent rounded-lg px-3 py-1.5 shadow-sm">
                    <p class="text-[13px] text-[#111b21] whitespace-pre-wrap leading-relaxed pr-6">${this.escHtml(msg)}</p>
                    <div class="flex items-center justify-end gap-1 mt-0.5 -mb-0.5">
                        <span class="text-xs text-[#667781] sending-indicator">Mengirim...</span>
                    </div>
                </div>`;
            container.appendChild(el);
            container.scrollTop = container.scrollHeight;

            try {
                const res = await fetch(`/chat/${this.activeConversation}/send`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ message: msg })
                });
                const data = await res.json();
                if (data.success) {
                    this.lastMsgId = data.message.id;
                    const time = new Date(data.message.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                    el.querySelector('.sending-indicator').innerHTML = `${time} <svg class="w-3.5 h-3.5 text-[#667781] inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
                    el.setAttribute('data-msg-id', data.message.id);
                } else {
                    el.querySelector('.sending-indicator').textContent = 'Gagal';
                    el.querySelector('.bg-\\[\\#d9fdd3\\]')?.classList?.replace('bg-[#d9fdd3]', 'bg-rose-100');
                }
            } catch (e) {
                el.querySelector('.sending-indicator').textContent = 'Gagal';
            }
        },

        async pollNewMessages() {
            if (!this.activeConversation) return;
            try {
                const res = await fetch(`/chat/${this.activeConversation}/poll?after=${this.lastMsgId}`, {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': this.csrfToken }
                });
                const data = await res.json();
                if (data.messages && data.messages.length > 0) {
                    const container = document.getElementById('messages-container');
                    const wasAtBottom = container.scrollHeight - container.scrollTop - container.clientHeight < 100;

                    data.messages.forEach(msg => {
                        // Skip if already rendered
                        if (document.querySelector(`[data-msg-id="${msg.id}"]`)) return;
                        
                        const isMine = msg.sender_id === {{ auth()->id() }};
                        const time = new Date(msg.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                        const el = document.createElement('div');
                        el.className = `flex ${isMine ? 'justify-end' : 'justify-start'} group/msg`;
                        el.setAttribute('data-msg-id', msg.id);
                        el.innerHTML = `
                            <div class="relative max-w-[70%] ${isMine ? 'bg-[#d9fdd3] bubble-sent' : 'bg-white bubble-received'} rounded-lg px-3 py-1.5 shadow-sm">
                                <p class="text-[13px] text-[#111b21] whitespace-pre-wrap leading-relaxed">${this.escHtml(msg.message)}</p>
                                <div class="flex items-center justify-end gap-1 mt-0.5 -mb-0.5">
                                    <span class="text-xs text-[#667781]">${time}</span>
                                    ${isMine ? '<svg class="w-4 h-4 text-[#667781]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>' : ''}
                                </div>
                            </div>`;
                        container.appendChild(el);
                        this.lastMsgId = msg.id;
                    });

                    if (wasAtBottom) container.scrollTop = container.scrollHeight;
                }

                // Update unread badge in sidebar
                const badge = document.querySelector('.chat-unread-badge');
                if (badge) {
                    if (data.unread_total > 0) {
                        badge.textContent = data.unread_total > 9 ? '9+' : data.unread_total;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            } catch (e) { /* silent */ }
        },

        async saveEdit(msgId, text, el) {
            text = text.trim();
            if (!text) return;
            try {
                const res = await fetch(`/chat/message/${msgId}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ message: text })
                });
                const data = await res.json();
                if (data.success) {
                    // Update message text in DOM
                    const msgP = el.querySelector('div[x-show="!editing"] p.whitespace-pre-wrap');
                    if (msgP) msgP.textContent = text;
                    // Add "diedit" indicator
                    const timeDiv = el.querySelector('div[x-show="!editing"] .flex.items-center');
                    if (timeDiv && !timeDiv.querySelector('.edited-tag')) {
                        const editTag = document.createElement('span');
                        editTag.className = 'text-[11px] text-slate-400 edited-tag';
                        editTag.textContent = 'diedit';
                        timeDiv.prepend(editTag);
                    }
                } else {
                    alert(data.error || 'Gagal mengedit pesan');
                }
            } catch (e) { alert('Gagal mengedit pesan'); }
        },

        async deleteMsg(msgId, el) {
            if (!confirm('Hapus pesan ini?')) return;
            try {
                const res = await fetch(`/chat/message/${msgId}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    el.style.transition = 'all 0.3s';
                    el.style.opacity = '0';
                    el.style.transform = 'scale(0.95)';
                    setTimeout(() => el.remove(), 300);
                } else {
                    alert(data.error || 'Gagal menghapus');
                }
            } catch (e) { alert('Gagal menghapus pesan'); }
        },

        escHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        },

        destroy() {
            if (this.pollInterval) clearInterval(this.pollInterval);
        }
    };
}

// Expose globally for inline Alpine x-data scoped buttons
window.saveEdit = function(msgId, text, el) {
    document.querySelector('[x-data*="chatApp"]').__x.$data.saveEdit(msgId, text, el);
};
window.deleteMsg = function(msgId, el) {
    document.querySelector('[x-data*="chatApp"]').__x.$data.deleteMsg(msgId, el);
};
</script>
</div>
</body>
</html>

