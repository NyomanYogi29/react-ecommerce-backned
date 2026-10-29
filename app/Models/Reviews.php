<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reviews extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'user_id',
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

    public function users(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function products(): BelongsTo {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
