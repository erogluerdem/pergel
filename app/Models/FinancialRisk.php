<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialRisk extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'payment_id',
        'risk_type',
        'risk_level',
        'days_overdue',
        'risk_amount',
        'impact_score',
        'probability_score',
        'risk_score',
        'description',
        'mitigation_strategy',
        'identified_date',
        'target_resolution_date',
        'status',
        'assigned_to',
    ];

    protected $casts = [
        'identified_date' => 'date',
        'target_resolution_date' => 'date',
        'risk_amount' => 'decimal:2',
        'impact_score' => 'decimal:2',
        'probability_score' => 'decimal:2',
        'days_overdue' => 'integer',
        'risk_score' => 'integer',
    ];

    /**
     * Proje ile ilişki
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Ödeme ile ilişki
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Sorumlu kişi
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Risk türü açıklaması
     */
    public function getRiskTypeLabel(): string
    {
        $labels = [
            'overdue_payment' => 'Vadesi Geçmiş Ödeme',
            'cash_shortage' => 'Nakit Yetersizliği',
            'currency_fluctuation' => 'Döviz Dalgalanması',
            'vendor_default' => 'Tedarikçi Temerrüdü',
            'budget_overrun' => 'Bütçe Aşımı',
        ];
        return $labels[$this->risk_type] ?? $this->risk_type;
    }

    /**
     * Risk seviyesi rengi
     */
    public function getRiskLevelColor(): string
    {
        $colors = [
            'low' => '#28a745',
            'medium' => '#ffc107',
            'high' => '#fd7e14',
            'critical' => '#dc3545',
        ];
        return $colors[$this->risk_level] ?? '#6c757d';
    }

    /**
     * Risk seviyesi açıklaması
     */
    public function getRiskLevelLabel(): string
    {
        $labels = [
            'low' => 'Düşük',
            'medium' => 'Orta',
            'high' => 'Yüksek',
            'critical' => 'Kritik',
        ];
        return $labels[$this->risk_level] ?? $this->risk_level;
    }

    /**
     * Risk skoru hesapla (impact * probability / 100)
     */
    public function calculateRiskScore(): int
    {
        return (int) (($this->impact_score * $this->probability_score) / 100);
    }

    /**
     * Risk seviyesini skora göre otomatik belirle
     */
    public function determineRiskLevel(): string
    {
        $score = $this->calculateRiskScore();
        
        if ($score >= 75) {
            return 'critical';
        } elseif ($score >= 50) {
            return 'high';
        } elseif ($score >= 25) {
            return 'medium';
        } else {
            return 'low';
        }
    }

    /**
     * Status etiketi
     */
    public function getStatusLabel(): string
    {
        $labels = [
            'open' => 'Açık',
            'monitoring' => 'Takip Edilmekte',
            'resolved' => 'Çözüldü',
        ];
        return $labels[$this->status] ?? $this->status;
    }
}
