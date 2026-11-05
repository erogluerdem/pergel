<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlaAlert extends Model
{
    protected $fillable = [
        'service_request_id',
        'alert_type',
        'triggered_at',
        'acknowledged_at',
        'acknowledged_by',
        'notes',
    ];

    protected $casts = [
        'triggered_at' => 'datetime',
        'acknowledged_at' => 'datetime',
    ];

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function acknowledgedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }

    public function scopeUnacknowledged($query)
    {
        return $query->whereNull('acknowledged_at');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('alert_type', $type);
    }

    public function acknowledge(User $user, $notes = null)
    {
        $this->update([
            'acknowledged_at' => now(),
            'acknowledged_by' => $user->id,
            'notes' => $notes,
        ]);
    }
}
