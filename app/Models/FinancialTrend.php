<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialTrend extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'period_start',
        'period_end',
        'period_type',
        'total_inflow',
        'total_outflow',
        'net_cash_flow',
        'payments_count',
        'on_time_payments',
        'late_payments',
        'payment_timeliness_ratio',
        'collections_count',
        'average_collection_period',
        'collection_ratio',
        'operating_expenses',
        'capital_expenses',
        'expense_ratio',
        'budgeted_amount',
        'actual_amount',
        'budget_variance',
        'growth_rate',
        'trend_direction',
        'notes',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'total_inflow' => 'decimal:2',
        'total_outflow' => 'decimal:2',
        'net_cash_flow' => 'decimal:2',
        'payment_timeliness_ratio' => 'decimal:2',
        'average_collection_period' => 'decimal:2',
        'collection_ratio' => 'decimal:2',
        'operating_expenses' => 'decimal:2',
        'capital_expenses' => 'decimal:2',
        'expense_ratio' => 'decimal:2',
        'budgeted_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'budget_variance' => 'decimal:2',
        'growth_rate' => 'decimal:2',
        'trend_direction' => 'decimal:2',
        'payments_count' => 'integer',
        'on_time_payments' => 'integer',
        'late_payments' => 'integer',
        'collections_count' => 'integer',
    ];

    /**
     * Proje ile ilişki
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Dönem tipi açıklaması
     */
    public function getPeriodTypeLabel(): string
    {
        $labels = [
            'daily' => 'Günlük',
            'weekly' => 'Haftalık',
            'monthly' => 'Aylık',
            'quarterly' => 'Üç Aylık',
            'yearly' => 'Yıllık',
        ];
        return $labels[$this->period_type] ?? $this->period_type;
    }

    /**
     * Trend yönü açıklaması
     */
    public function getTrendDirectionLabel(): string
    {
        if ($this->trend_direction > 10) {
            return 'Yükselişte ⬆️';
        } elseif ($this->trend_direction < -10) {
            return 'Düşüşte ⬇️';
        } else {
            return 'Stabil ➡️';
        }
    }

    /**
     * Bütçe sapması açıklaması
     */
    public function getBudgetVarianceLabel(): string
    {
        if ($this->budget_variance > 5) {
            return "Aşırı Harcama: {$this->budget_variance}%";
        } elseif ($this->budget_variance < -5) {
            return "Tasarruf: " . abs($this->budget_variance) . "%";
        } else {
            return "Normal: {$this->budget_variance}%";
        }
    }

    /**
     * Toplam giderler
     */
    public function getTotalExpensesAttribute(): float
    {
        return (float) ($this->operating_expenses + $this->capital_expenses);
    }

    /**
     * Ödeme performansı (zamanında ödenen / toplam)
     */
    public function getPaymentPerformanceAttribute(): float
    {
        if ($this->payments_count == 0) {
            return 0;
        }
        return ($this->on_time_payments / $this->payments_count) * 100;
    }

    /**
     * Sağlık durumu (0-100)
     */
    public function getHealthScoreAttribute(): int
    {
        $score = 100;

        // Nakit akış sağlığı (net negatifse ceza)
        if (($this->net_cash_flow ?? 0) < 0) {
            $score -= 20;
        }

        // Ödeme zamanında yapılmış mı?
        if (($this->payment_timeliness_ratio ?? 0) < 80) {
            $score -= 15;
        }

        // Tahsilat oranı
        if (($this->collection_ratio ?? 0) < 80) {
            $score -= 10;
        }

        // Bütçe kontrol
        if (abs($this->budget_variance ?? 0) > 10) {
            $score -= 15;
        }

        // Gider kontrolü (giderler gelirin %50'sinden fazla mı?)
        $totalExpenses = ($this->operating_expenses ?? 0) + ($this->capital_expenses ?? 0);
        $totalInflow = ($this->total_inflow ?? 0) ?: 1;
        
        if ($totalExpenses > 0 && $totalExpenses / $totalInflow > 0.5) {
            $score -= 10;
        }

        return max(0, $score);
    }
}
