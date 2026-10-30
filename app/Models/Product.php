<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'user_id',
        'category_id',
        'stock',
        'description',
        'price',
        'image',
        'rating',
        'review_count',
    ];

    protected $attributes = [
        'stock' => 0,
        'price' => 0,
        'rating' => 1.0,
        'review_count' => 0
    ];

    protected function casts(): array {
        return [
            'stock' => 'integer',
            'price' => 'decimal:2',
            'rating' => 'float',
            'review_count' => 'integer'
        ];
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function seller(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function reviews(): HasMany {
        return $this->hasMany(Reviews::class, 'product_id', 'id');
    }

    public function invoicesItems(): HasMany {
        return $this->hasMany(InvoicesItem::class, 'product_id', 'id');
    }
}
