<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budget extends Model
{
    protected $fillable = ['user_id', 'category_id', 'amount', 'month', 'year'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Calculate how much has been spent in this budget period.
     */
    public function getSpentAttribute(): float
    {
        return Transaction::where('user_id', $this->user_id)
            ->where('category_id', $this->category_id)
            ->whereMonth('transaction_date', $this->month)
            ->whereYear('transaction_date', $this->year)
            ->sum('amount');
    }

    /**
     * Calculate percentage of budget used.
     */
    public function getPercentageAttribute(): float
    {
        if ($this->amount == 0) return 0;
        return min(100, round(($this->spent / $this->amount) * 100, 1));
    }

    /**
     * Remaining budget.
     */
    public function getRemainingAttribute(): float
    {
        return max(0, $this->amount - $this->spent);
    }
}
