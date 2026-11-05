<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Concerns\UsesSubcontractorConnection;

class SubcontractorContract extends Model
{
    use HasFactory;
    use UsesSubcontractorConnection;

    protected $fillable = [
        'contract_number',
        'contract_name',
        'subcontractor_id',
        'project_id',
        'contract_type',
        'total_amount',
        'paid_amount',
        'status',
        'start_date',
        'end_date',
        'description',
        'documents',
        'specifications',
        'insurance_expiry_dates',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'documents' => 'array',
        'specifications' => 'array',
        'insurance_expiry_dates' => 'array',
    ];

    public function subcontractor(): BelongsTo
    {
        return $this->belongsTo(Subcontractor::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function changeOrders()
    {
        return $this->hasMany(ChangeOrder::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function serviceEntries()
    {
        return $this->hasMany(SubcontractorServiceEntry::class);
    }

    public function materialEntries()
    {
        return $this->hasMany(SubcontractorMaterialEntry::class);
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->total_amount == 0) return 0;
        return round(($this->paid_amount / $this->total_amount) * 100, 2);
    }

    public function getExpiringInsurancesAttribute()
    {
        if (!$this->insurance_expiry_dates) return [];

        $expiring = [];
        $now = now();
        $warningDays = 30; // 30 gün önceden uyarı

        foreach ($this->insurance_expiry_dates as $insurance => $expiryDate) {
            $expiry = \Carbon\Carbon::parse($expiryDate);
            $daysUntilExpiry = $now->diffInDays($expiry, false);

            if ($daysUntilExpiry <= $warningDays && $daysUntilExpiry >= 0) {
                $expiring[$insurance] = [
                    'expiry_date' => $expiry,
                    'days_remaining' => $daysUntilExpiry,
                    'status' => $daysUntilExpiry <= 7 ? 'critical' : 'warning'
                ];
            } elseif ($daysUntilExpiry < 0) {
                $expiring[$insurance] = [
                    'expiry_date' => $expiry,
                    'days_remaining' => $daysUntilExpiry,
                    'status' => 'expired'
                ];
            }
        }

        return $expiring;
    }
}
