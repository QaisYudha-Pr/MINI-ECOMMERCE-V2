<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Main chat page — WhatsApp-style full layout.
     * If conversation param given, load it in main pane.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $conversations = Conversation::forUser($user->id)
            ->with(['userOne', 'userTwo', 'latestMessage', 'item'])
            ->orderByDesc('last_message_at')
            ->get();

        $activeConversation = null;
        $messages = collect();
        $otherUser = null;

        if ($request->has('c')) {
            $activeConversation = Conversation::find($request->c);
            if ($activeConversation && ($activeConversation->user_one === $user->id || $activeConversation->user_two === $user->id)) {
                $activeConversation->messages()
                    ->where('sender_id', '!=', $user->id)
                    ->where('is_read', false)
                    ->update(['is_read' => true, 'read_at' => now()]);

                $messages = $activeConversation->messages()
                    ->with('sender')
                    ->orderBy('created_at')
                    ->get();

                $otherUser = $activeConversation->getOtherUser($user->id);
            } else {
                $activeConversation = null;
            }
        }
        
        return view('chat.index', compact('conversations', 'activeConversation', 'messages', 'otherUser'));
    }

    public function show(Conversation $conversation)
    {
        $user = Auth::user();
        if ($conversation->user_one !== $user->id && $conversation->user_two !== $user->id) {
            abort(403);
        }
        return redirect()->route('chat.index', ['c' => $conversation->id]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
            'item_id' => 'nullable|exists:item_shops,id',
        ]);
        
        $user = Auth::user();
        $receiverId = $request->receiver_id;
        
        if ($receiverId == $user->id) {
            return back()->with('error', 'Tidak bisa kirim pesan ke diri sendiri bolo!');
        }
        
        $conversation = Conversation::findOrCreateBetween($user->id, $receiverId, $request->item_id);
        
        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'message' => $request->message,
            'type' => Message::TYPE_TEXT,
        ]);
        
        $conversation->update(['last_message_at' => now()]);
        
        Notification::notify(
            $receiverId,
            'Pesan Baru dari ' . $user->name,
            substr($request->message, 0, 100) . (strlen($request->message) > 100 ? '...' : ''),
            Notification::CATEGORY_CHAT,
            Notification::TYPE_INFO,
            route('chat.index', ['c' => $conversation->id]),
            ['conversation_id' => $conversation->id, 'sender_id' => $user->id]
        );
        
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $message->load('sender')]);
        }
        
        return redirect()->route('chat.index', ['c' => $conversation->id]);
    }

    public function startChat(User $seller)
    {
        $user = Auth::user();
        if ($seller->id == $user->id) {
            return back()->with('error', 'Tidak bisa chat dengan diri sendiri!');
        }
        $conversation = Conversation::findOrCreateBetween($user->id, $seller->id);
        return redirect()->route('chat.index', ['c' => $conversation->id]);
    }

    public function startFromProduct(Request $request, User $seller)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'item_id' => 'nullable|exists:item_shops,id',
        ]);
        
        $user = Auth::user();
        if ($seller->id == $user->id) {
            return back()->with('error', 'Tidak bisa kirim pesan ke diri sendiri bolo!');
        }
        
        $conversation = Conversation::findOrCreateBetween($user->id, $seller->id, $request->item_id);
        
        $conversation->messages()->create([
            'sender_id' => $user->id,
            'message' => $request->message,
            'type' => $request->item_id ? Message::TYPE_PRODUCT : Message::TYPE_TEXT,
            'metadata' => $request->item_id ? ['item_id' => $request->item_id] : null,
        ]);
        
        $conversation->update(['last_message_at' => now()]);
        
        Notification::notify(
            $seller->id,
            'Pesan Baru dari ' . $user->name,
            substr($request->message, 0, 100),
            Notification::CATEGORY_CHAT,
            Notification::TYPE_INFO,
            route('chat.index', ['c' => $conversation->id])
        );
        
        return redirect()->route('chat.index', ['c' => $conversation->id])->with('success', 'Pesan terkirim!');
    }

    public function unreadCount()
    {
        $user = Auth::user();
        $count = Message::whereHas('conversation', fn($q) => $q->forUser($user->id))
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->count();
        return response()->json(['count' => $count]);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $user = Auth::user();
        if ($conversation->user_one !== $user->id && $conversation->user_two !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $request->validate(['message' => 'required|string|max:1000']);
        
        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'message' => $request->message,
            'type' => Message::TYPE_TEXT,
        ]);
        
        $conversation->update(['last_message_at' => now()]);
        
        $otherUserId = $conversation->user_one === $user->id ? $conversation->user_two : $conversation->user_one;
        
        Notification::notify(
            $otherUserId,
            'Pesan Baru dari ' . $user->name,
            substr($request->message, 0, 100),
            Notification::CATEGORY_CHAT,
            Notification::TYPE_INFO,
            route('chat.index', ['c' => $conversation->id])
        );
        
        return response()->json(['success' => true, 'message' => $message->load('sender')]);
    }

    /**
     * Edit message (sender only, 15 min window)
     */
    public function editMessage(Request $request, Message $message)
    {
        $user = Auth::user();
        if ($message->sender_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        if ($message->created_at->diffInMinutes(now()) > 15) {
            return response()->json(['error' => 'Waktu edit sudah lewat (maks 15 menit)'], 422);
        }
        $request->validate(['message' => 'required|string|max:1000']);
        $message->update(['message' => $request->message, 'edited_at' => now()]);
        return response()->json(['success' => true, 'message' => $message->fresh()->load('sender')]);
    }

    /**
     * Delete message (sender only)
     */
    public function deleteMessage(Message $message)
    {
        $user = Auth::user();
        if ($message->sender_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $conversationId = $message->conversation_id;
        $message->delete();

        $conversation = Conversation::find($conversationId);
        if ($conversation) {
            $latest = $conversation->messages()->orderByDesc('created_at')->first();
            $conversation->update(['last_message_at' => $latest?->created_at ?? $conversation->created_at]);
        }
        return response()->json(['success' => true]);
    }

    /**
     * Poll for new messages (AJAX)
     */
    public function pollMessages(Request $request, Conversation $conversation)
    {
        $user = Auth::user();
        if ($conversation->user_one !== $user->id && $conversation->user_two !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $after = $request->input('after', 0);

        $conversation->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        $newMessages = $conversation->messages()
            ->with('sender')
            ->where('id', '>', $after)
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'messages' => $newMessages,
            'unread_total' => Message::whereHas('conversation', fn($q) => $q->forUser($user->id))
                ->where('sender_id', '!=', $user->id)
                ->where('is_read', false)
                ->count(),
        ]);
    }
}
