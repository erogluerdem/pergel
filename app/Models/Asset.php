<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Asset extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'asset_code',
        'name',
        'description',
        'category',
        'subcategory',
        'brand',
        'model',
        'serial_number',
        'purchase_date',
        'purchase_price',
        'current_value',
        'depreciation_rate',
        'depreciation_method',
        'useful_life_years',
        'useful_life_months',
        'salvage_value',
        'location',
        'latitude',
        'longitude',
        'floor_number',
        'department',
        'assigned_to',
        'status',
        'condition',
        'installation_date',
        'warranty_start_date',
        'warranty_end_date',
        'warranty_expiry', // backward compatibility
        'maintenance_schedule',
        'last_maintenance_date',
        'next_maintenance_date',
        'supplier_name',
        'supplier_contact',
        'invoice_number',
        'insurance_policy',
        'insurance_value',
        'tags',
        'custom_fields',
        'image_path',
        'photo_path', // backward compatibility
        'qr_code',
        'barcode',
        'maintenance_template_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'current_value' => 'decimal:2',
        'salvage_value' => 'decimal:2',
        'insurance_value' => 'decimal:2',
        'depreciation_rate' => 'decimal:2',
        'useful_life_years' => 'integer',
        'useful_life_months' => 'integer',
        'purchase_date' => 'date',
        'installation_date' => 'date',
        'warranty_start_date' => 'date',
        'warranty_end_date' => 'date',
        'warranty_expiry' => 'date',
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'tags' => 'array',
        'custom_fields' => 'array',
    ];

    protected $dates = ['deleted_at'];

    // İlişkiler
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    public function maintenanceTemplate(): BelongsTo
    {
        return $this->belongsTo(MaintenanceTemplate::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(AssetDocument::class);
    }

    // Kapsamlar
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeNeedsMaintenance($query)
    {
        return $query->where('next_maintenance_date', '<=', now()->addDays(30));
    }

    public function scopeWarrantyExpiring($query)
    {
        return $query->where(function($q) {
            $q->where('warranty_end_date', '<=', now()->addDays(90))
              ->where('warranty_end_date', '>=', now())
              ->orWhere('warranty_expiry', '<=', now()->addDays(90))
              ->where('warranty_expiry', '>=', now());
        });
    }

    // Aksesörler
    public function getFormattedPurchasePriceAttribute()
    {
        return '₺' . number_format($this->purchase_price ?: 0, 2, ',', '.');
    }

    public function getFormattedCurrentValueAttribute()
    {
        return '₺' . number_format($this->current_value ?: 0, 2, ',', '.');
    }

    public function getAgeInYearsAttribute()
    {
        $date = $this->purchase_date ?: $this->installation_date;
        return $date ? $date->diffInYears(now()) : 0;
    }

    public function getAgeInMonthsAttribute()
    {
        $date = $this->purchase_date ?: $this->installation_date;
        return $date ? $date->diffInMonths(now()) : 0;
    }

    public function getDepreciationAmountAttribute()
    {
        return ($this->purchase_price ?: 0) - ($this->current_value ?: 0);
    }

    public function getDepreciationPercentageAttribute()
    {
        if (($this->purchase_price ?: 0) == 0) return 0;
        return ((($this->purchase_price ?: 0) - ($this->current_value ?: 0)) / ($this->purchase_price ?: 0)) * 100;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => '<span class="badge bg-success">Aktif</span>',
            'inactive' => '<span class="badge bg-secondary">Pasif</span>',
            'maintenance' => '<span class="badge bg-warning">Bakımda</span>',
            'disposed' => '<span class="badge bg-danger">İmha Edildi</span>',
            'sold' => '<span class="badge bg-info">Satıldı</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Bilinmiyor</span>';
    }

    public function getConditionBadgeAttribute()
    {
        $badges = [
            'excellent' => '<span class="badge bg-success">Mükemmel</span>',
            'good' => '<span class="badge bg-primary">İyi</span>',
            'fair' => '<span class="badge bg-warning">Orta</span>',
            'poor' => '<span class="badge bg-danger">Kötü</span>',
        ];

        return $badges[$this->condition] ?? '<span class="badge bg-secondary">Belirtilmemiş</span>';
    }

    public function getCategoryIconAttribute()
    {
        $icons = [
            'computer' => 'mdi-laptop',
            'furniture' => 'mdi-sofa',
            'vehicle' => 'mdi-car',
            'equipment' => 'mdi-tools',
            'machinery' => 'mdi-factory',
            'building' => 'mdi-office-building',
            'land' => 'mdi-terrain',
            'software' => 'mdi-monitor',
            'mobile' => 'mdi-cellphone',
            'other' => 'mdi-package-variant'
        ];

        return $icons[$this->category] ?? 'mdi-package-variant';
    }

    public function getWarrantyStatusAttribute()
    {
        $warrantyDate = $this->warranty_end_date ?: $this->warranty_expiry;
        if (!$warrantyDate) return 'none';
        
        $daysLeft = now()->diffInDays($warrantyDate, false);
        
        if ($daysLeft < 0) return 'expired';
        if ($daysLeft <= 30) return 'expiring';
        
        return 'active';
    }

    public function getMaintenanceStatusAttribute()
    {
        if (!$this->next_maintenance_date) return 'none';
        
        $daysLeft = now()->diffInDays($this->next_maintenance_date, false);
        
        if ($daysLeft < 0) return 'overdue';
        if ($daysLeft <= 7) return 'due_soon';
        
        return 'scheduled';
    }

    // Statik metodlar
    public static function getCategories()
    {
        return [
            'computer' => 'Bilgisayar & IT',
            'furniture' => 'Mobilya',
            'vehicle' => 'Araç',
            'equipment' => 'Ekipman',
            'machinery' => 'Makine',
            'building' => 'Bina',
            'land' => 'Arsa',
            'software' => 'Yazılım',
            'mobile' => 'Mobil Cihaz',
            'other' => 'Diğer'
        ];
    }

    public static function getStatuses()
    {
        return [
            'active' => 'Aktif',
            'inactive' => 'Pasif',
            'maintenance' => 'Bakımda',
            'disposed' => 'İmha Edildi',
            'sold' => 'Satıldı'
        ];
    }

    public static function getConditions()
    {
        return [
            'excellent' => 'Mükemmel',
            'good' => 'İyi',
            'fair' => 'Orta',
            'poor' => 'Kötü'
        ];
    }
}
