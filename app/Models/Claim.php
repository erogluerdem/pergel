<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Claim extends Model
{
    protected $fillable = [
        'project_id', 'number', 'title', 'claimant', 'type',
        'amount_requested', 'days_requested', 'status', 'responsible_user_id',
        'description', 'start_date', 'end_date'
    ];

    protected $casts = [
        'amount_requested' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }
}
