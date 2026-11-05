<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetAllocation extends Model
{
    protected $fillable = [
        'project_id',
        'category',
        'allocated_amount',
        'spent_amount',
        'remaining_amount',
        'fiscal_year',
        'notes'
    ];

    protected $casts = [
        'allocated_amount' => 'decimal:2',
        'spent_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function getRemainingPercentageAttribute()
    {
        if ($this->allocated_amount == 0) return 0;
        return ($this->remaining_amount / $this->allocated_amount) * 100;
    }

    public function getSpentPercentageAttribute()
    {
        if ($this->allocated_amount == 0) return 0;
        return ($this->spent_amount / $this->allocated_amount) * 100;
    }

    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function updateSpent($amount)
    {
        $this->spent_amount += $amount;
        $this->remaining_amount = $this->allocated_amount - $this->spent_amount;
        $this->save();
    }
}
