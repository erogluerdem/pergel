<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetAllocation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'category',
        'allocated_amount',
        'spent_amount',
        'committed_amount',
        'remaining_amount',
        'fiscal_year',
        'quarter',
        'status',
        'notes',
        'approved_by',
        'approved_at',
        'created_by',
    ];

    protected $casts = [
        'allocated_amount' => 'decimal:2',
        'spent_amount' => 'decimal:2',
        'committed_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
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
