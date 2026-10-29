<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use HasUuids;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'uuid',
            'name' => 'string',
            'email' => 'string',
            'role' => ["user", "seller"],
            'password' => 'hashed',
            'created_at' => 'timestamp',
            'updated_at' => 'timestamp'
        ];
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
