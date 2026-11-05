<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentReminder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_document_id',
        'reminder_title',
        'description',
        'renewal_due_date',
        'reminder_frequency',
        'notification_status',
        'last_notification_sent',
        'next_notification_date',
        'notification_recipients',
        'notification_channels',
        'renewal_document_type',
        'estimated_renewal_cost',
        'is_active',
        'renewal_completed',
        'renewal_completed_date',
    ];

    protected $casts = [
        'renewal_due_date' => 'date',
        'last_notification_sent' => 'date',
        'next_notification_date' => 'date',
        'notification_recipients' => 'array',
        'is_active' => 'boolean',
        'renewal_completed' => 'boolean',
        'renewal_completed_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function propertyDocument()
    {
        return $this->belongsTo(PropertyDocument::class);
    }

    public function property()
    {
        return $this->propertyDocument->property();
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePending($query)
    {
        return $query->where('notification_status', 'pending');
    }

    public function scopeSent($query)
    {
        return $query->where('notification_status', 'sent');
    }

    public function scopeDue($query)
    {
        return $query->where('next_notification_date', '<=', now()->toDateString())
            ->where('notification_status', 'pending');
    }

    public function scopeUpcoming($query, $days = 7)
    {
        return $query->whereBetween('next_notification_date', [
            now()->toDateString(),
            now()->addDays($days)->toDateString(),
        ]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('renewal_due_date', '<', now()->toDateString())
            ->where('renewal_completed', false);
    }

    // ==================== ACCESSORS & MUTATORS ====================

    public function getIsDueAttribute()
    {
        return $this->next_notification_date && $this->next_notification_date <= now()->toDateString() && $this->notification_status === 'pending';
    }

    public function getIsOverdueAttribute()
    {
        return $this->renewal_due_date < now()->toDateString() && !$this->renewal_completed;
    }

    public function getDaysUntilDueAttribute()
    {
        if (!$this->next_notification_date) return null;
        return now()->diffInDays($this->next_notification_date);
    }

    public function getDaysOverdueAttribute()
    {
        if (!$this->is_overdue) return 0;
        return now()->diffInDays($this->renewal_due_date);
    }

    // ==================== CUSTOM METHODS ====================

    public function getStatusBadge()
    {
        if ($this->renewal_completed) {
            return '<span class="badge bg-success">Tamamlandı</span>';
        } elseif ($this->is_overdue) {
            return '<span class="badge bg-danger">Gecikmiş (' . $this->days_overdue . ' gün)</span>';
        } elseif ($this->is_due) {
            return '<span class="badge bg-warning">Vadesi Geldi</span>';
        } else {
            return '<span class="badge bg-info">Beklemede</span>';
        }
    }

    public function markAsSent()
    {
        $this->update([
            'notification_status' => 'sent',
            'last_notification_sent' => now()->toDateString(),
        ]);
    }

    public function markAsRenewalCompleted()
    {
        $this->update([
            'renewal_completed' => true,
            'renewal_completed_date' => now()->toDateString(),
            'is_active' => false,
        ]);
    }

    public function cancelReminder()
    {
        $this->update([
            'notification_status' => 'cancelled',
            'is_active' => false,
        ]);
    }

    public function getRecipientUsers()
    {
        if (!$this->notification_recipients) return collect();
        
        return User::whereIn('id', $this->notification_recipients)->get();
    }

    public function sendNotification()
    {
        if (!$this->is_active || $this->notification_status !== 'pending') {
            return false;
        }

        // TODO: Implement notification sending logic
        // Send via channels specified in $this->notification_channels
        // email, sms, in_app

        $this->markAsSent();
        return true;
    }

    public function shouldSendNotification()
    {
        return $this->is_active 
            && $this->notification_status === 'pending'
            && $this->is_due;
    }

    public function rescheduleReminder($newDate, $newFrequency = null)
    {
        $this->update([
            'next_notification_date' => $newDate,
            'reminder_frequency' => $newFrequency ?? $this->reminder_frequency,
            'notification_status' => 'pending',
        ]);
    }

    public function getFrequencyLabel()
    {
        $labels = [
            'one_time' => 'Tek Seferlik',
            'weekly' => 'Haftalık',
            'monthly' => 'Aylık',
            'quarterly' => 'Üç Aylık',
            'yearly' => 'Yıllık',
        ];

        return $labels[$this->reminder_frequency] ?? $this->reminder_frequency;
    }
}
