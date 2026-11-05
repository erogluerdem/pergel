<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressClaimApproval extends Model
{
    protected $fillable = [
        'progress_claim_id',
        'user_id',
        'action',
        'comments',
        'approved_quantities',
        'approved_at',
    ];

    protected $casts = [
        'approved_quantities' => 'array',
        'approved_at' => 'datetime',
    ];

    public function progressClaim(): BelongsTo
    {
        return $this->belongsTo(ProgressClaim::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
