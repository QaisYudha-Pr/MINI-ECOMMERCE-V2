<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RealtimeController extends Controller
{
    /**
     * Poll for new notifications (for real-time without WebSocket)
     */
    public function pollNotifications(Request $request)
    {
        $user = Auth::user();
        $lastId = $request->input('last_id', 0);
        $category = $request->input('category', null);
        
        $query = Notification::where('user_id', $user->id)
            ->where('id', '>', $lastId);
        
        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }
        
        $notifications = $query->orderBy('id', 'desc')->take(10)->get();
        
        $unreadCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
        
        return response()->json([
            'notifications' => $notifications->map(fn($n) => [
                'id' => $n->id,
                'title' => $n->title,
                'message' => $n->message,
                'type' => $n->type,
                'category' => $n->category,
                'link' => $n->link,
                'is_read' => $n->is_read,
                'created_at' => $n->created_at->diffForHumans(),
                'icon_class' => $n->icon_class,
                'color_class' => $n->color_class,
            ]),
            'unread_count' => $unreadCount,
            'last_id' => $notifications->first()?->id ?? $lastId,
        ]);
    }

    /**
     * Poll for new messages in a conversation
     */
    public function pollMessages(Request $request, Conversation $conversation)
    {
        $user = Auth::user();
        
        // Verify user is part of conversation
        if ($conversation->user_one !== $user->id && $conversation->user_two !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $lastId = $request->input('last_id', 0);
        
        $messages = $conversation->messages()
            ->where('id', '>', $lastId)
            ->with('sender')
            ->orderBy('id', 'asc')
            ->get();
        
        // Mark as read
        $conversation->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
        
        return response()->json([
            'messages' => $messages->map(fn($m) => [
                'id' => $m->id,
                'sender_id' => $m->sender_id,
                'sender' => [
                    'id' => $m->sender->id,
                    'name' => $m->sender->name,
                    'avatar' => $m->sender->avatar,
                ],
                'message' => $m->message,
                'type' => $m->type,
                'is_read' => $m->is_read,
                'created_at' => $m->created_at->format('H:i'),
            ]),
            'last_id' => $messages->last()?->id ?? $lastId,
        ]);
    }

    /**
     * Get notification counts by category
     */
    public function notificationCounts()
    {
        $user = Auth::user();
        
        $counts = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category');
        
        return response()->json([
            'total' => $counts->sum(),
            'transaction' => $counts->get('transaction', 0),
            'promo' => $counts->get('promo', 0),
            'follower' => $counts->get('follower', 0),
            'chat' => $counts->get('chat', 0),
            'system' => $counts->get('system', 0),
        ]);
    }

    /**
     * Get unread message count
     */
    public function unreadMessageCount()
    {
        $user = Auth::user();
        
        $count = Message::whereHas('conversation', function ($q) use ($user) {
            $q->where('user_one', $user->id)->orWhere('user_two', $user->id);
        })
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->count();
        
        return response()->json(['count' => $count]);
    }
}
