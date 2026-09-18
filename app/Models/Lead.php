<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Lead extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'company', 'email', 'phone', 'source',
        'status', 'expected_value', 'follow_up_date',
        'notes', 'assigned_to', 'created_by'
    ];

    protected $casts = [
        'follow_up_date'  => 'date',
        'expected_value'  => 'decimal:2',
    ];

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'new'       => 'secondary',
            'contacted' => 'info',
            'qualified' => 'primary',
            'proposal'  => 'warning',
            'won'       => 'success',
            'lost'      => 'danger',
            default     => 'secondary',
        };
    }
}