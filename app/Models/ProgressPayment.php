<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgressPayment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'contract_id',
        'project_id',
        'user_id',
        'payment_number',
        'payment_date',
        'due_date',
        'amount',
        'percentage',
        'description',
        'status',
        'approved_by',
        'approved_at',
        'paid_at',
        'invoice_id',
        'notes',
        'attachment_path',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'due_date' => 'date',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'amount' => 'float',
        'percentage' => 'float',
    ];

    // Status enum values
    const STATUS_PENDING = 'pending';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_PAID = 'paid';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Hakediş ile ilgili sözleşme
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    /**
     * Hakediş ile ilgili proje
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Hakediş tarafından oluşturan kullanıcı
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hakediş tarafından onaylayan kullanıcı
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Hakediş detay satırları
     */
    public function details(): HasMany
    {
        return $this->hasMany(ProgressPaymentDetail::class, 'progress_payment_id');
    }

    /**
     * Hakediş ile ilgili fatura
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    /**
     * Hakediş tarafından ilgili etkinlikler
     */
    public function activities(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'related_model_id')
            ->where('related_model', 'ProgressPayment');
    }

    // Accessor Methods

    /**
     * Hakediş durumunun kontrol edilebilir ismi
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Beklemede',
            self::STATUS_SUBMITTED => 'Sunuldu',
            self::STATUS_APPROVED => 'Onaylandı',
            self::STATUS_REJECTED => 'Reddedildi',
            self::STATUS_PAID => 'Ödendi',
            self::STATUS_CANCELLED => 'İptal Edildi',
            default => 'Bilinmeyen',
        };
    }

    /**
     * Hakediş durumu badge rengi
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_SUBMITTED => 'info',
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_PAID => 'success',
            self::STATUS_CANCELLED => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Hakediş vadesi geçti mi?
     */
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== self::STATUS_PAID;
    }

    /**
     * Hakediş onay bekliyor mu?
     */
    public function isPendingApproval(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_SUBMITTED]);
    }

    /**
     * Hakediş ödendi mi?
     */
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Hakediş tutarını format et
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2, ',', '.') . ' ₺';
    }

    /**
     * Hakediş yüzdesini format et
     */
    public function getFormattedPercentageAttribute(): string
    {
        return number_format($this->percentage, 2, ',', '.') . '%';
    }

    /**
     * Scope: Onay bekleyen hakedişler
     */
    public function scopePendingApproval($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_SUBMITTED]);
    }

    /**
     * Scope: Ödenmemiş hakedişler
     */
    public function scopeUnpaid($query)
    {
        return $query->whereNotIn('status', [self::STATUS_PAID, self::STATUS_CANCELLED]);
    }

    /**
     * Scope: Vadesi geçen hakedişler
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->where('status', '!=', self::STATUS_PAID);
    }

    /**
     * Scope: Tarih aralığına göre
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
    }

    /**
     * Scope: Proje bazında
     */
    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope: Sözleşme bazında
     */
    public function scopeByContract($query, $contractId)
    {
        return $query->where('contract_id', $contractId);
    }
}
