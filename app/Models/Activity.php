<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    protected $fillable = [
        'user_id', 'subject_id', 'subject_type',
        'type', 'activity_date', 'description'
    ];

    protected $casts = [
        'activity_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'call'       => 'primary',
            'email'      => 'info',
            'meeting'    => 'success',
            'follow_up'  => 'warning',
            'note'       => 'secondary',
            default      => 'secondary',
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'call'       => 'bi-telephone',
            'email'      => 'bi-envelope',
            'meeting'    => 'bi-calendar-event',
            'follow_up'  => 'bi-clock-history',
            'note'       => 'bi-sticky',
            default      => 'bi-activity',
        };
    }
}