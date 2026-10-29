<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoices extends Model
{
    use HasUuids;

    protected $table = 'invoices';

    protected $fillable = [
        'user_id',
        'final_price',
        'status',
        'paid_at'
    ];

    protected $attributes = [
        'status' => 'pending',
        'final_price' => 0
    ];

    public function casts(): array {
        return [
            'final_price' => 'float',
            'status' => ["pending", "paid", "cancelled"]
        ];
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function invoicesItem(): HasMany {
        return $this->hasMany(InvoicesItem::class, 'invoice_id', 'id');
    }
}
