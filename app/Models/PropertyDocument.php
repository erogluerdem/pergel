<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'asset_id',
        'project_id',
        'document_name',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'document_category',
        'description',
        'document_date',
        'validity_date',
        'renewal_date',
        'is_critical',
        'requires_renewal',
        'indexed_by',
        'indexed_date',
        'uploaded_by_id',
        'document_type',
        'title',
        'document_number',
        'issue_date',
        'expiry_date',
        'status',
        'issued_by',
        'document_cost',
    ];

    protected $casts = [
        'is_critical' => 'boolean',
        'requires_renewal' => 'boolean',
        'document_date' => 'date',
        'validity_date' => 'date',
        'renewal_date' => 'date',
        'indexed_date' => 'date',
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'document_cost' => 'decimal:2',
        'file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by_id');
    }

    public function reminders()
    {
        return $this->hasMany(DocumentReminder::class, 'property_document_id');
    }

    // ==================== SCOPES ====================

    public function scopeCritical($query)
    {
        return $query->where('is_critical', true);
    }

    public function scopeNeedsRenewal($query)
    {
        return $query->where('requires_renewal', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('document_category', $category);
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('validity_date')
            ->where('validity_date', '<', now()->toDateString())
            ->orWhere(function($q) {
                $q->whereNotNull('expiry_date')
                  ->where('expiry_date', '<', now()->toDateString());
            });
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        $futureDate = now()->addDays($days)->toDateString();
        return $query->where(function($q) use ($futureDate) {
            $q->where('validity_date', '<=', $futureDate)
              ->where('validity_date', '>', now()->toDateString());
        })->orWhere(function($q) use ($futureDate) {
            $q->where('expiry_date', '<=', $futureDate)
              ->where('expiry_date', '>', now()->toDateString());
        });
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // ==================== ACCESSORS & MUTATORS ====================

    public function getIsExpiredAttribute()
    {
        $expiryDate = $this->validity_date ?? $this->expiry_date;
        return $expiryDate && $expiryDate < now()->toDateString();
    }

    public function getIsExpiringSoonAttribute()
    {
        $expiryDate = $this->validity_date ?? $this->expiry_date;
        if (!$expiryDate) return false;
        $daysUntilExpiry = now()->diffInDays($expiryDate);
        return $daysUntilExpiry <= 30 && $daysUntilExpiry > 0;
    }

    public function getDaysUntilExpiryAttribute()
    {
        $expiryDate = $this->validity_date ?? $this->expiry_date;
        if (!$expiryDate) return null;
        return now()->diffInDays($expiryDate);
    }

    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size ?? 0;
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    public function getCategoryLabelAttribute()
    {
        $labels = [
            'deed' => 'Tapu',
            'cadastral' => 'Kadastro',
            'zoning' => 'İmar',
            'permit' => 'İzin',
            'survey' => 'Ölçüm',
            'evaluation' => 'Değerlendirme',
            'legal' => 'Yasal',
            'correspondence' => 'Yazışma',
            'contract' => 'Sözleşme',
            'other' => 'Diğer',
        ];

        return $labels[$this->document_category] ?? 'Bilinmiyor';
    }

    // ==================== CUSTOM METHODS ====================

    // Belge süresi dolmuş mu
    public function isExpired(): bool
    {
        $expiryDate = $this->validity_date ?? $this->expiry_date;
        return $expiryDate && $expiryDate < now()->toDateString();
    }

    // Belge süresi dolmak üzere mi (1 ay kaldı)
    public function isAboutToExpire(): bool
    {
        $expiryDate = $this->validity_date ?? $this->expiry_date;
        if (!$expiryDate) return false;
        $daysUntilExpiry = now()->diffInDays($expiryDate);
        return $daysUntilExpiry <= 30 && $daysUntilExpiry > 0;
    }

    public function getStatusBadge()
    {
        if ($this->is_expired) {
            return '<span class="badge bg-danger">Süresi Dolmuş</span>';
        } elseif ($this->is_expiring_soon) {
            return '<span class="badge bg-warning">Süresi Yaklaşıyor (' . $this->days_until_expiry . ' gün)</span>';
        } else {
            return '<span class="badge bg-success">Geçerli</span>';
        }
    }

    // Durum etiketi
    public function getStatusLabel(): string
    {
        $labels = [
            'pending' => 'Bekleme ⏳',
            'received' => 'Alındı ✓',
            'processed' => 'İşlendi ✓✓',
            'expired' => 'Süresi Doldu ✗',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    // Belge türü etiketi
    public function getTypeLabel(): string
    {
        $types = [
            'tapu' => 'Tapu Belgesi',
            'mesken' => 'Mesken Belgesi',
            'imar' => 'İmar Durum Belgesi',
            'yapı_ruhsat' => 'Yapı Ruhsatı',
            'yapı_kullanma' => 'Yapı Kullanma İzni',
            'elektrik' => 'Elektrik Tesisatı Raporu',
            'su' => 'Su Tesisatı Raporu',
            'doğalgaz' => 'Doğalgaz Tesisatı Raporu',
        ];
        return $types[$this->document_type] ?? $this->document_type;
    }

    public function createReminder($reminderData)
    {
        return $this->reminders()->create($reminderData);
    }

    public function downloadUrl()
    {
        return route('properties.documents.download', $this->id);
    }

    public function makeRenewalReminder($daysBeforeExpiry = 30)
    {
        $expiryDate = $this->validity_date ?? $this->expiry_date;
        
        if (!$expiryDate || !$this->requires_renewal) {
            return null;
        }

        $reminderDate = now()->createFromDate(
            $expiryDate->year,
            $expiryDate->month,
            $expiryDate->day
        )->subDays($daysBeforeExpiry);

        return $this->createReminder([
            'reminder_title' => "Belge Yenileme Hatırlatması: {$this->document_name}",
            'renewal_due_date' => $expiryDate,
            'next_notification_date' => $reminderDate,
            'notification_recipients' => json_encode([auth()->id()]),
            'renewal_document_type' => $this->document_category,
        ]);
    }

    public function isValid()
    {
        $expiryDate = $this->validity_date ?? $this->expiry_date;
        if (!$expiryDate) return true;
        return $expiryDate > now()->toDateString();
    }
}

