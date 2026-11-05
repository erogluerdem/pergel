<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressClaimItem extends Model
{
    protected $fillable = [
        'progress_claim_id',
        'item_code',
        'description',
        'unit',
        'contract_quantity',
        'previous_quantity',
        'current_quantity',
        'cumulative_quantity',
        'unit_price',
        'current_amount',
    ];

    protected $casts = [
        'contract_quantity' => 'decimal:2',
        'previous_quantity' => 'decimal:2',
        'current_quantity' => 'decimal:2',
        'cumulative_quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'current_amount' => 'decimal:2',
    ];

    public function progressClaim(): BelongsTo
    {
        return $this->belongsTo(ProgressClaim::class);
    }
}
