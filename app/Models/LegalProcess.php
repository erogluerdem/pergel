<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LegalProcess extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'process_name',
        'description',
        'process_type',
        'start_date',
        'end_date',
        'actual_completion_date',
        'duration_days',
        'responsible_id',
        'followed_by_id',
        'authority',
        'reference_number',
        'cost',
        'actual_cost',
        'status',
        'status_notes',
        'last_status_update',
        'is_delayed',
        'delay_days',
        'delay_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'actual_completion_date' => 'date',
        'cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'is_delayed' => 'boolean',
        'last_status_update' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function followedBy()
    {
        return $this->belongsTo(User::class, 'followed_by_id');
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['not_started', 'in_progress', 'pending_approval']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeDelayed($query)
    {
        return $query->where('is_delayed', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('process_type', $type);
    }

    public function scopeByAuthority($query, $authority)
    {
        return $query->where('authority', $authority);
    }

    // ==================== ACCESSORS & MUTATORS ====================

    public function getIsOverdueAttribute()
    {
        if (!$this->end_date || $this->status === 'completed') return false;
        return $this->end_date < now()->toDateString();
    }

    public function getDaysOverdueAttribute()
    {
        if (!$this->is_overdue) return 0;
        return now()->diffInDays($this->end_date);
    }

    public function getProgressPercentAttribute()
    {
        return match($this->status) {
            'not_started' => 0,
            'in_progress' => 50,
            'pending_approval' => 75,
            'completed' => 100,
            'delayed' => 40,
            'blocked' => 0,
            default => 0,
        };
    }

    // ==================== CUSTOM METHODS ====================

    public function getStatusBadge()
    {
        $badges = [
            'not_started' => '<span class="badge bg-secondary">Başlanmadı</span>',
            'in_progress' => '<span class="badge bg-info">Devam Ediyor</span>',
            'pending_approval' => '<span class="badge bg-warning">Onay Bekliyor</span>',
            'completed' => '<span class="badge bg-success">Tamamlandı</span>',
            'delayed' => '<span class="badge bg-danger">Gecikmiş</span>',
            'blocked' => '<span class="badge bg-dark">Blokeli</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-light">Bilinmiyor</span>';
    }

    public function getProcessTypeBadge()
    {
        $badges = [
            'deed_transfer' => '<span class="badge bg-primary">Tapu Devri</span>',
            'zoning_approval' => '<span class="badge bg-info">İmar Onayı</span>',
            'permit_acquisition' => '<span class="badge bg-success">İzin Alımı</span>',
            'survey' => '<span class="badge bg-secondary">Ölçüm</span>',
            'dispute_resolution' => '<span class="badge bg-danger">Anlaşmazlık Çözümü</span>',
            'other' => '<span class="badge bg-light">Diğer</span>',
        ];

        return $badges[$this->process_type] ?? '<span class="badge bg-light">Bilinmiyor</span>';
    }

    public function markAsCompleted($actualDate = null)
    {
        $this->update([
            'status' => 'completed',
            'actual_completion_date' => $actualDate ?? now()->toDateString(),
            'is_delayed' => false,
            'last_status_update' => now()->toDateString(),
        ]);
    }

    public function markAsDelayed($reason = null)
    {
        $delayDays = 0;
        if ($this->end_date) {
            $delayDays = now()->diffInDays($this->end_date);
        }

        $this->update([
            'status' => 'delayed',
            'is_delayed' => true,
            'delay_days' => $delayDays,
            'delay_reason' => $reason,
            'last_status_update' => now()->toDateString(),
        ]);
    }

    public function updateStatus($newStatus, $notes = null)
    {
        $this->update([
            'status' => $newStatus,
            'status_notes' => $notes,
            'last_status_update' => now()->toDateString(),
        ]);
    }

    public function calculateDuration()
    {
        if ($this->start_date && $this->actual_completion_date) {
            $this->duration_days = $this->start_date->diffInDays($this->actual_completion_date);
            $this->save();
        }
    }

    public function hasExpired()
    {
        return $this->end_date && $this->end_date < now()->toDateString() && $this->status !== 'completed';
    }

    public function getTimelineInfo()
    {
        return [
            'start_date' => $this->start_date,
            'planned_end_date' => $this->end_date,
            'actual_completion_date' => $this->actual_completion_date,
            'is_delayed' => $this->is_delayed,
            'days_overdue' => $this->days_overdue,
            'status' => $this->status,
        ];
    }
}
