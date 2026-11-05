<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrExpense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'expense_type',
        'category',
        'title',
        'description',
        'amount',
        'currency',
        'expense_date',
        'submission_date',
        'status',
        'approved_by',
        'approved_at',
        'approval_notes',
        'receipt_path',
        'project_code',
        'department',
        'custom_fields',
        'reimbursed_amount',
        'reimbursement_date',
        'payment_method',
        'reference_no',
        'is_recurring',
        'recurring_frequency',
        'created_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'reimbursed_amount' => 'decimal:2',
        'expense_date' => 'date',
        'submission_date' => 'date',
        'approved_at' => 'datetime',
        'reimbursement_date' => 'date',
        'custom_fields' => 'array',
        'is_recurring' => 'boolean',
    ];

    protected $appends = ['status_badge', 'formatted_amount', 'days_pending'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => ['class' => 'warning', 'text' => 'Beklemede'],
            'approved' => ['class' => 'success', 'text' => 'Onaylandı'],
            'rejected' => ['class' => 'danger', 'text' => 'Reddedildi'],
            'paid' => ['class' => 'info', 'text' => 'Ödendi'],
        ];

        return $badges[$this->status] ?? ['class' => 'secondary', 'text' => 'Bilinmiyor'];
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2, ',', '.') . ' ' . $this->currency;
    }

    public function getDaysPendingAttribute()
    {
        if ($this->status !== 'pending') {
            return 0;
        }
        
        return now()->diffInDays($this->submission_date);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('expense_date', now()->month)
                    ->whereYear('expense_date', now()->year);
    }

    public static function getExpenseTypes()
    {
        return [
            'travel' => 'Seyahat',
            'meal' => 'Yemek',
            'accommodation' => 'Konaklama',
            'transport' => 'Ulaşım',
            'training' => 'Eğitim',
            'office_supplies' => 'Ofis Malzemeleri',
            'communication' => 'İletişim',
            'entertainment' => 'Ağırlama',
            'health' => 'Sağlık',
            'other' => 'Diğer'
        ];
    }

    public static function getCategories()
    {
        return [
            'business' => 'İş Gideri',
            'personal_reimbursement' => 'Kişisel Geri Ödeme',
            'company_paid' => 'Şirket Ödemeli'
        ];
    }

    public static function getDepartments()
    {
        return [
            'it' => 'Bilgi İşlem',
            'hr' => 'İnsan Kaynakları',
            'finance' => 'Finans',
            'marketing' => 'Pazarlama',
            'sales' => 'Satış',
            'operations' => 'Operasyon',
            'management' => 'Yönetim'
        ];
    }
}
