<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class ServiceRequest extends Model
{
    protected $fillable = [
        'subject',
        'location',
        'description',
        'photo_path',
        'status',
        'assigned_to',
        'resolution_notes',
        'project_id',
        'unit_id',
        'customer_id',
        'resolution_time',
        'sla_target',
        'qr_token',
        'communication_log',
        'resolved_at',
    ];

    protected $casts = [
        'communication_log' => 'array',
        'resolved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // İlişkiler
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Villa::class, 'unit_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Aksesörler
    public function getResolutionTimeInHoursAttribute()
    {
        if (!$this->resolution_time) return null;
        return round($this->resolution_time / 3600, 2);
    }

    public function getSlaComplianceAttribute()
    {
        if (!$this->sla_target || !$this->resolution_time) return null;
        return ($this->resolution_time <= $this->sla_target) ? 'compliant' : 'violated';
    }

    public function getSlaPercentageAttribute()
    {
        if (!$this->sla_target || !$this->resolution_time) return null;
        return round(($this->resolution_time / $this->sla_target) * 100, 2);
    }

    public function getIsOverdueAttribute()
    {
        if ($this->status === 'completed') return false;
        if (!$this->sla_target) return false;
        
        $elapsedTime = now()->diffInSeconds($this->created_at);
        return $elapsedTime > $this->sla_target;
    }

    public function getTimeRemainingAttribute()
    {
        if ($this->status === 'completed' || !$this->sla_target) return null;
        
        $elapsedTime = now()->diffInSeconds($this->created_at);
        $remaining = $this->sla_target - $elapsedTime;
        
        return $remaining > 0 ? $remaining : 0;
    }

    // Scope'lar
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'completed')
            ->whereNotNull('sla_target')
            ->whereRaw('EXTRACT(EPOCH FROM (NOW() - created_at)) > sla_target');
    }

    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeByUnit($query, $unitId)
    {
        return $query->where('unit_id', $unitId);
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    // Metodlar
    public function addCommunicationLog(string $user, string $message)
    {
        $log = $this->communication_log ?? [];
        $log[] = [
            'timestamp' => now()->toIso8601String(),
            'user' => $user,
            'message' => $message,
        ];
        $this->update(['communication_log' => $log]);
    }

    public function markAsResolved()
    {
        $resolutionTime = now()->diffInSeconds($this->created_at);
        $this->update([
            'status' => 'completed',
            'resolved_at' => now(),
            'resolution_time' => $resolutionTime,
        ]);
    }

    public function canClose()
    {
        return in_array($this->status, ['completed', 'cancelled']);
    }
}
