<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Standard extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
        'status',
        'version',
        'effective_date',
        'expiry_date',
        'category',
        'priority',
        'compliance_level',
        'document_url',
        'tags',
        'metadata',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'effective_date' => 'date',
        'expiry_date' => 'date',
        'tags' => 'array',
        'metadata' => 'array',
        'priority' => 'integer',
    ];

    protected $dates = ['deleted_at'];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->whereBetween('expiry_date', [now(), now()->addDays($days)]);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Accessors
    public function getIsExpiredAttribute()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function getIsExpiringSoonAttribute()
    {
        return $this->expiry_date && $this->expiry_date->diffInDays(now()) <= 30;
    }

    public function getDaysUntilExpiryAttribute()
    {
        if (!$this->expiry_date) {
            return null;
        }
        return max(0, $this->expiry_date->diffInDays(now()));
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'active' => 'success',
            'draft' => 'warning',
            'inactive' => 'secondary',
            'expired' => 'danger',
            'pending' => 'info',
            default => 'secondary'
        };
    }

    public function getPriorityColorAttribute()
    {
        return match ($this->priority) {
            1 => 'danger',
            2 => 'warning', 
            3 => 'info',
            4 => 'secondary',
            5 => 'secondary',
            default => 'secondary'
        };
    }

    public function getPriorityTextAttribute()
    {
        return match ($this->priority) {
            1 => 'Kritik',
            2 => 'Yüksek',
            3 => 'Orta',
            4 => 'Düşük',
            5 => 'Çok Düşük',
            default => 'Belirsiz'
        };
    }

    // Constants
    const TYPES = [
        'quality' => 'Kalite Standardı',
        'safety' => 'Güvenlik Standardı',
        'environmental' => 'Çevre Standardı',
        'technical' => 'Teknik Standart',
        'process' => 'Süreç Standardı',
        'compliance' => 'Uyumluluk Standardı',
        'security' => 'Güvenlik Standardı',
        'performance' => 'Performans Standardı'
    ];

    const STATUSES = [
        'active' => 'Aktif',
        'draft' => 'Taslak',
        'inactive' => 'Pasif',
        'expired' => 'Süresi Dolmuş',
        'pending' => 'Beklemede'
    ];

    const CATEGORIES = [
        'iso' => 'ISO Standartları',
        'ts' => 'TS Standartları',
        'en' => 'EN Standartları',
        'astm' => 'ASTM Standartları',
        'din' => 'DIN Standartları',
        'bs' => 'BS Standartları',
        'ansi' => 'ANSI Standartları',
        'iec' => 'IEC Standartları',
        'ieee' => 'IEEE Standartları',
        'company' => 'Şirket Standartları',
        'project' => 'Proje Standartları',
        'regulatory' => 'Yasal Standartlar',
        'other' => 'Diğer'
    ];

    const PRIORITIES = [
        1 => 'Kritik',
        2 => 'Yüksek',
        3 => 'Orta',
        4 => 'Düşük',
        5 => 'Çok Düşük'
    ];

    const COMPLIANCE_LEVELS = [
        'mandatory' => 'Zorunlu',
        'recommended' => 'Önerilen',
        'optional' => 'İsteğe Bağlı',
        'reference' => 'Referans'
    ];
}