<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'user_one',
        'user_two',
        'item_shop_id',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two');
    }

    public function item()
    {
        return $this->belongsTo(ItemShop::class, 'item_shop_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

    // Get the other participant in conversation
    public function getOtherUser(int $currentUserId): User
    {
        if ($this->user_one === $currentUserId) {
            return $this->userTwo;
        }
        return $this->userOne;
    }

    // Get unread count for a user
    public function getUnreadCount(int $userId): int
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }

    // Find or create conversation between two users
    public static function findOrCreateBetween(int $userOne, int $userTwo, ?int $itemId = null): self
    {
        // Ensure consistent ordering
        $users = [$userOne, $userTwo];
        sort($users);
        
        $conversation = self::where(function ($query) use ($users) {
            $query->where('user_one', $users[0])->where('user_two', $users[1]);
        })->orWhere(function ($query) use ($users) {
            $query->where('user_one', $users[1])->where('user_two', $users[0]);
        })->first();

        if (!$conversation) {
            $conversation = self::create([
                'user_one' => $users[0],
                'user_two' => $users[1],
                'item_shop_id' => $itemId,
            ]);
        }

        return $conversation;
    }

    // Scope for user's conversations
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_one', $userId)
            ->orWhere('user_two', $userId);
    }
}
