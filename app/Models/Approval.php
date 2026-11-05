<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Approval extends Model
{
    protected $fillable = [
        'requisition_id',
        'approval_level',
        'approver_id',
        'status',
        'comments',
        'approved_at',
        'rejected_at',
        'rejection_reason'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    const APPROVAL_LEVELS = ['manager', 'finance', 'executive', 'final'];

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(Requisition::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function approve($comments = null)
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'comments' => $comments
        ]);
    }

    public function reject($reason)
    {
        $this->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $reason
        ]);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
