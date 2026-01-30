<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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

    public function isFollowing($userId)
    {
        return $this->following()->where('following_id', $userId)->exists();
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
