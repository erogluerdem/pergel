<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgressClaim extends Model
{
    protected $fillable = [
        'subcontractor_contract_id',
        'project_id',
        'claim_number',
        'period_start',
        'period_end',
        'status',
        'total_amount',
        'withholding_amount',
        'advance_deduction',
        'vat_amount',
        'net_amount',
        'submitted_at',
        'approved_at',
        'notes',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'withholding_amount' => 'decimal:2',
        'advance_deduction' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
    ];

    public function subcontractorContract(): BelongsTo
    {
        return $this->belongsTo(SubcontractorContract::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProgressClaimItem::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(ProgressClaimApproval::class);
    }
}
