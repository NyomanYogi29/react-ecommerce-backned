<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Hidden(['password'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasUuids, HasApiTokens;

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function isSeller(): bool {
        return $this->role === 'seller';
    }

    public function product(): HasMany {
        return $this->hasMany(Product::class, 'user_id', 'id');
    }

    public function reviews(): HasMany {
        return $this->hasMany(Reviews::class, 'user_id', 'id');
    }

    public function carts(): HasMany {
        return $this->hasMany(Carts::class, 'user_id', 'id');
    }

    public function invoices(): HasMany {
        return $this->hasMany(Invoices::class, 'user_id', 'id');
    }
}
