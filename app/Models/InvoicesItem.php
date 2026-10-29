<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoicesItem extends Model
{
    use HasUuids;

    protected $table = 'invoices_item';

    protected $fillable = [
        'invoice_id',
        'product_id',
        'product_name',
        'quantity',
        'subtotal',
        'unit_price'
    ];

    protected $attributes = [
        'quantity' => 1,
    ];

    public function casts(): array {
        return [
            'quantity' => 'integer',
            'subtotal' => 'float',
            'unit_price' => 'float'
        ];
    }

    public function invoices(): BelongsTo {
        return $this->belongsTo(Invoices::class, 'invoice_id', 'id');
    }

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
