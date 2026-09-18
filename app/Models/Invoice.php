<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id', 'created_by', 'invoice_number',
        'invoice_date', 'due_date', 'status',
        'subtotal', 'tax_percent', 'tax_amount',
        'discount_percent', 'discount_amount',
        'grand_total', 'paid_amount', 'remaining_amount', 'notes'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date'     => 'date',
        'subtotal'     => 'decimal:2',
        'grand_total'  => 'decimal:2',
        'paid_amount'  => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft'     => 'secondary',
            'sent'      => 'info',
            'paid'      => 'success',
            'partial'   => 'warning',
            'overdue'   => 'danger',
            'cancelled' => 'dark',
            default     => 'secondary',
        };
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date &&
               $this->due_date->isPast() &&
               !in_array($this->status, ['paid', 'cancelled']);
    }
}