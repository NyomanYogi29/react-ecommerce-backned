<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carts extends Model
{
    use HasUuids;

    protected $table = 'carts';

    protected $fillable = [
        'user_id',
        'product_id',
        'product_count',
        'final_price'
    ];

    protected $attributes = [
        'product_count' => 0,
        'final_price' => 0
    ];

    protected function cast(): array {
        return [
            'product_count' => 'integer',
            'final_price' => 'float'
        ];
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function cartsItem(): HasMany {
        return $this->hasMany(CartsItem::class, 'cart_id', 'id');
    } 
}
