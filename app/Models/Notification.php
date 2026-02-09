<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'category',
        'link',
        'icon',
        'data',
        'is_read',
        'email_sent',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'email_sent' => 'boolean',
    ];

    // Notification categories
    const CATEGORY_TRANSACTION = 'transaction';
    const CATEGORY_PROMO = 'promo';
    const CATEGORY_FOLLOWER = 'follower';
    const CATEGORY_SYSTEM = 'system';
    const CATEGORY_CHAT = 'chat';

    // Notification types
    const TYPE_INFO = 'info';
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_ERROR = 'error';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Create notification with all details
    public static function notify(
        int $userId,
        string $title,
        string $message,
        string $category = self::CATEGORY_SYSTEM,
        string $type = self::TYPE_INFO,
        ?string $link = null,
        ?array $data = null,
        bool $sendEmail = false
    ): self {
        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'category' => $category,
            'type' => $type,
            'link' => $link,
            'data' => $data,
            'email_sent' => false,
        ]);
    }

    // Get icon based on category
    public function getIconClassAttribute(): string
    {
        return match ($this->category) {
            self::CATEGORY_TRANSACTION => 'fa-shopping-bag',
            self::CATEGORY_PROMO => 'fa-gift',
            self::CATEGORY_FOLLOWER => 'fa-user-plus',
            self::CATEGORY_CHAT => 'fa-comment',
            default => 'fa-bell',
        };
    }

    // Get color based on type
    public function getColorClassAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_SUCCESS => 'text-emerald-500 bg-emerald-50',
            self::TYPE_WARNING => 'text-amber-500 bg-amber-50',
            self::TYPE_ERROR => 'text-rose-500 bg-rose-50',
            default => 'text-emerald-500 bg-emerald-50',
        };
    }

    // Scope by category
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // Scope unread
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}

