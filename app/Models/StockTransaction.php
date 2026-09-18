<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTransaction extends Model
{
    protected $fillable = [
        'product_id', 'user_id', 'invoice_id',
        'type', 'quantity', 'quantity_before',
        'quantity_after', 'notes'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'stock_in'   => 'success',
            'stock_out'  => 'danger',
            'adjustment' => 'warning',
            default      => 'secondary',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'stock_in'   => 'Stock In',
            'stock_out'  => 'Stock Out',
            'adjustment' => 'Adjustment',
            default      => ucfirst($this->type),
        };
    }
}