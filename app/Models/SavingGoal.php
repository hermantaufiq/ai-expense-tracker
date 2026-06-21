<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class SavingGoal extends Model
{
    protected $fillable = [
        'user_id', 'name', 'icon',
        'target_amount', 'current_amount',
        'deadline', 'is_completed',
    ];

    protected $casts = [
        'deadline'     => 'date',
        'is_completed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Percentage of goal achieved (0-100) */
    public function getPercentageAttribute(): float
    {
        if ($this->target_amount == 0) return 0;
        return min(100, round(($this->current_amount / $this->target_amount) * 100, 1));
    }

    /** Amount still needed */
    public function getRemainingAttribute(): float
    {
        return max(0, $this->target_amount - $this->current_amount);
    }

    /** Days remaining until deadline */
    public function getDaysLeftAttribute(): ?int
    {
        if (!$this->deadline) return null;
        $diff = now()->startOfDay()->diffInDays($this->deadline->startOfDay(), false);
        return (int) $diff;
    }

    /** Monthly saving needed to hit the deadline */
    public function getMonthlySavingNeededAttribute(): ?float
    {
        if (!$this->deadline || $this->remaining <= 0) return null;
        $monthsLeft = now()->floatDiffInMonths($this->deadline);
        if ($monthsLeft <= 0) return $this->remaining;
        return round($this->remaining / $monthsLeft, 0);
    }
}
