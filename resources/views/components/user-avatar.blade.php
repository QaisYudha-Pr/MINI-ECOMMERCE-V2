@props(['user', 'size' => 'w-10 h-10', 'textSize' => 'text-[10px]', 'shape' => 'rounded-xl'])

@php
    $initials = collect(explode(' ', $user->nama_toko ?? $user->name ?? 'User'))
        ->map(fn($n) => mb_substr($n, 0, 1))
        ->take(2)
        ->join('');
@endphp

<div {{ $attributes->merge(['class' => "relative $size overflow-hidden $shape border border-emerald-100 flex items-center justify-center bg-emerald-50 text-emerald-600 font-bold $textSize transition-all"]) }}>
    @if ($user && $user->avatar)
        <img src="{{ asset($user->avatar) }}" class="w-full h-full object-cover">
    @else
        <span class="uppercase tracking-wider">{{ $initials }}</span>
    @endif
</div>

