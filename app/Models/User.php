<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasRoles;

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function itemShops()
    {
        return $this->hasMany(ItemShop::class);
    }

    // Alias for camelCase relation to avoid "item_shops()" method errors
    public function item_shops()
    {
        return $this->itemShops();
    }

    public function favoriteItems()
    {
        return $this->belongsToMany(ItemShop::class, 'favorites');
    }

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'courier_agency_id',
        'nama_toko',
        'avatar',
        'alamat',
        'latitude',
        'longitude',
        'seller_status',
        'seller_rating',
        'seller_rating_count',
        'is_top_seller',
        'theme_color',
        'seller_document',
        'balance',
        'phone',
        'banner',
    ];

    public function courierAgency()
    {
        return $this->belongsTo(Courier::class, 'courier_agency_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function conversations()
    {
        return Conversation::where('user_one', $this->id)
            ->orWhere('user_two', $this->id);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function sellerReviews()
    {
        return $this->hasMany(SellerReview::class, 'seller_id');
    }

    public function givenSellerReviews()
    {
        return $this->hasMany(SellerReview::class, 'buyer_id');
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'seller_id');
    }

    public function isFollowing($userId)
    {
        return $this->following()->where('following_id', $userId)->exists();
    }

    /**
     * Check if user is online (active session within last 5 minutes).
     */
    public function isOnline(): bool
    {
        return DB::table('sessions')
            ->where('user_id', $this->id)
            ->where('last_activity', '>=', now()->subMinutes(5)->timestamp)
            ->exists();
    }

    /**
     * Get seller merchant level based on performance metrics.
     * Returns: 'regular' | 'power' | 'star' | 'super'
     */
    public function getMerchantLevel(): string
    {
        if (!$this->hasRole('seller')) return 'regular';

        $totalSold = $this->itemShops()->sum('total_terjual');
        $rating = (float) ($this->seller_rating ?? 0);
        $itemCount = $this->itemShops()->count();
        $accountAge = $this->created_at ? $this->created_at->diffInMonths(now()) : 0;

        // Super Seller: 500+ sold, 4.8+ rating, 6+ months, 20+ products
        if ($totalSold >= 500 && $rating >= 4.8 && $accountAge >= 6 && $itemCount >= 20) {
            return 'super';
        }

        // Star Seller: 200+ sold, 4.5+ rating, 3+ months, 10+ products
        if ($totalSold >= 200 && $rating >= 4.5 && $accountAge >= 3 && $itemCount >= 10) {
            return 'star';
        }

        // Power Merchant: 50+ sold, 4.0+ rating, 1+ month, 5+ products
        if ($totalSold >= 50 && $rating >= 4.0 && $accountAge >= 1 && $itemCount >= 5) {
            return 'power';
        }

        return 'regular';
    }

    /**
     * Get merchant badge display info.
     */
    public function getMerchantBadge(): array
    {
        $level = $this->getMerchantLevel();

        return match($level) {
            'super' => [
                'label' => 'Super Seller',
                'color' => 'bg-gradient-to-r from-amber-400 to-orange-500 text-white',
                'icon' => '👑',
                'border' => 'border-amber-400',
            ],
            'star' => [
                'label' => 'Star Seller',
                'color' => 'bg-gradient-to-r from-emerald-500 to-emerald-700 text-white',
                'icon' => '⭐',
                'border' => 'border-emerald-500',
            ],
            'power' => [
                'label' => 'Power Merchant',
                'color' => 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white',
                'icon' => '⚡',
                'border' => 'border-emerald-500',
            ],
            default => [
                'label' => 'Seller',
                'color' => 'bg-slate-100 text-slate-600',
                'icon' => '🏪',
                'border' => 'border-slate-200',
            ],
        };
    }

    /**
     * Get formatted last seen text.
     */
    public function lastSeenFormatted(): string
    {
        $lastActivity = DB::table('sessions')
            ->where('user_id', $this->id)
            ->max('last_activity');

        if (!$lastActivity) {
            return 'Offline';
        }

        $lastSeen = \Carbon\Carbon::createFromTimestamp($lastActivity);
        $diffMinutes = $lastSeen->diffInMinutes(now());

        if ($diffMinutes < 60) {
            return "Terakhir dilihat {$diffMinutes} menit lalu";
        }

        if ($lastSeen->isToday()) {
            return "Terakhir dilihat hari ini pukul " . $lastSeen->format('H:i');
        }

        if ($lastSeen->isYesterday()) {
            return "Terakhir dilihat kemarin pukul " . $lastSeen->format('H:i');
        }

        return "Terakhir dilihat " . $lastSeen->translatedFormat('d M') . " pukul " . $lastSeen->format('H:i');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
