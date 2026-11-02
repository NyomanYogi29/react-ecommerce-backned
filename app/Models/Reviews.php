<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reviews extends Model
{
    use HasUuids;
    protected $table = 'reviews';

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
    ];

    protected $attributes = [
        'rating' => 1
    ];

    protected function casts(): array {
        return [
            'rating' => 'integer'
        ];
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
