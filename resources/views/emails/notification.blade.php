@component('mail::message')
# Hai {{ $user->name }}! 👋

{{ $notification->message }}

@if($notification->link)
@component('mail::button', ['url' => url($notification->link)])
Lihat Detail
@endcomponent
@endif

---

Notifikasi ini dikirim otomatis dari **{{ config('app.name') }}**.  
Jika kamu tidak ingin menerima email seperti ini, silakan atur preferensi notifikasi di pengaturan akun.

Terima kasih bolo! 🙏

{{ config('app.name') }}
@endcomponent

