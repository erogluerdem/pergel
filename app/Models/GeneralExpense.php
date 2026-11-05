<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralExpense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'category',
        'subcategory',
        'amount',
        'currency',
        'expense_date',
        'vendor_id',
        'vendor_name',
        'vendor_tax_number',
        'invoice_number',
        'invoice_date',
        'status',
        'approved_by',
        'approved_at',
        'approval_notes',
        'payment_method',
        'payment_date',
        'payment_reference',
        'receipt_path',
        'project_id',
        'project_code',
        'villa_id',
        'department',
        'cost_center',
        'budget_code',
        'vat_rate',
        'vat_amount',
        'total_amount',
        'is_recurring',
        'recurring_frequency',
        'recurring_until',
        'tags',
        'custom_fields',
        'created_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'vat_rate' => 'decimal:2',
        'expense_date' => 'date',
        'invoice_date' => 'date',
        'approved_at' => 'datetime',
        'payment_date' => 'date',
        'recurring_until' => 'date',
        'is_recurring' => 'boolean',
        'tags' => 'array',
        'custom_fields' => 'array',
    ];

    protected $dates = ['deleted_at'];

    // İlişkiler
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function villa()
    {
        return $this->belongsTo(Villa::class, 'villa_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    // Kapsamlar
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('expense_date', now()->month)
                    ->whereYear('expense_date', now()->year);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Aksesörler
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2, ',', '.') . ' ' . strtoupper($this->currency);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Onay Bekliyor</span>',
            'approved' => '<span class="badge bg-success">Onaylandı</span>',
            'rejected' => '<span class="badge bg-danger">Reddedildi</span>',
            'paid' => '<span class="badge bg-info">Ödendi</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Bilinmiyor</span>';
    }

    public function getCategoryIconAttribute()
    {
        $icons = [
            'office' => 'mdi-office-building',
            'utilities' => 'mdi-flash',
            'travel' => 'mdi-airplane',
            'marketing' => 'mdi-bullhorn',
            'software' => 'mdi-monitor',
            'equipment' => 'mdi-tools',
            'supplies' => 'mdi-package',
            'legal' => 'mdi-gavel',
            'insurance' => 'mdi-shield-check',
            'maintenance' => 'mdi-wrench',
            'construction' => 'mdi-home-modern',
            'materials' => 'mdi-cube-outline',
            'labor' => 'mdi-account-hard-hat',
            'fuel' => 'mdi-fuel',
            'logistics' => 'mdi-truck',
            'catering' => 'mdi-silverware-fork-knife',
            'fees' => 'mdi-file-document-edit',
            'overhead' => 'mdi-domain',
            'other' => 'mdi-file-document'
        ];

        return $icons[$this->category] ?? 'mdi-file-document';
    }

    // Statik metodlar
    public static function getCategories()
    {
        // First try to load categories from storage file so admin UI changes take effect
        $file = storage_path('app/expense_categories.json');
        if (file_exists($file)) {
            try {
                $contents = file_get_contents($file);
                $data = json_decode($contents, true);
                if (is_array($data) && count($data) > 0) {
                    return $data;
                }
            } catch (\Exception $e) {
                // ignore and fall back to defaults
            }
        }

        // Fallback defaults
        return [
            'office' => 'Ofis Giderleri',
            'utilities' => 'Enerji & Su',
            'travel' => 'Seyahat',
            'marketing' => 'Pazarlama',
            'software' => 'Yazılım & Lisans',
            'equipment' => 'Ekipman',
            'supplies' => 'Malzeme',
            'legal' => 'Hukuki',
            'insurance' => 'Sigorta',
            'maintenance' => 'Bakım',
            'construction' => 'İnşaat Genel',
            'materials' => 'İnşaat Malzemeleri',
            'labor' => 'İşçilik',
            'fuel' => 'Akaryakıt',
            'logistics' => 'Lojistik',
            'catering' => 'Yemek & İkram',
            'fees' => 'Resmi Harçlar',
            'overhead' => 'Şantiye Genel',
            'other' => 'Diğer'
        ];
    }

    // Kategori simgesini doğrudan döndüren statik metot
    public static function getCategoryIcon($category)
    {
        $icons = [
            'office' => 'mdi-office-building',
            'utilities' => 'mdi-flash',
            'travel' => 'mdi-airplane',
            'marketing' => 'mdi-bullhorn',
            'software' => 'mdi-monitor',
            'equipment' => 'mdi-tools',
            'supplies' => 'mdi-package',
            'legal' => 'mdi-gavel',
            'insurance' => 'mdi-shield-check',
            'maintenance' => 'mdi-wrench',
            'construction' => 'mdi-home-modern',
            'materials' => 'mdi-cube-outline',
            'labor' => 'mdi-account-hard-hat',
            'fuel' => 'mdi-fuel',
            'logistics' => 'mdi-truck',
            'catering' => 'mdi-silverware-fork-knife',
            'fees' => 'mdi-file-document-edit',
            'overhead' => 'mdi-domain',
            'other' => 'mdi-file-document'
        ];

        return $icons[$category] ?? 'mdi-file-document';
    }

    public static function getStatuses()
    {
        return [
            'pending' => 'Onay Bekliyor',
            'approved' => 'Onaylandı',
            'rejected' => 'Reddedildi',
            'paid' => 'Ödendi'
        ];
    }
}
