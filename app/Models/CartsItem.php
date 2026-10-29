<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartsItem extends Model
{
    use HasUuids;

    protected $table = 'carts_item';

    protected $attributes = [
        'quantity' => 1,
    ];

    protected $fillable = [
        'cart_id',
        'product_name',
        'quantity',
        'subtotal'
    ];

    protected function casts(): array {
        return [
            'quantity' => 'integer',
            'subtotal' => 'float'
        ];
    }

    public function carts(): BelongsTo {
        return $this->belongsTo(Carts::class, 'cart_id', 'id');
    }

    public function products(): BelongsTo {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
