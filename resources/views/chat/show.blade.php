{{-- This view is no longer used. Chat conversations are rendered in the unified chat/index layout. --}}
{{-- The ChatController@show now redirects to chat.index?c={id} --}}
<script>window.location.href = "{{ route('chat.index', ['c' => $conversation->id ?? '']) }}";</script>

