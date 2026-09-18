<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'invoice_id', 'customer_id', 'created_by',
        'amount', 'payment_date', 'payment_method',
        'reference_number', 'notes'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'cash'          => '💵 Cash',
            'bank_transfer' => '🏦 Bank Transfer',
            'upi'           => '📱 UPI',
            'card'          => '💳 Card',
            'other'         => '📋 Other',
            default         => ucfirst($this->payment_method),
        };
    }

    public function getMethodColorAttribute(): string
    {
        return match($this->payment_method) {
            'cash'          => 'success',
            'bank_transfer' => 'primary',
            'upi'           => 'info',
            'card'          => 'warning',
            default         => 'secondary',
        };
    }
}