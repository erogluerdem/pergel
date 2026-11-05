<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashFlowForecast extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'forecast_date',
        'opening_balance',
        'inflow_planned',
        'inflow_actual',
        'outflow_planned',
        'outflow_actual',
        'closing_balance',
        'currency',
        'variance_percentage',
        'status',
        'notes',
        'confidence_level',
    ];

    protected $casts = [
        'forecast_date' => 'date',
        'opening_balance' => 'decimal:2',
        'inflow_planned' => 'decimal:2',
        'inflow_actual' => 'decimal:2',
        'outflow_planned' => 'decimal:2',
        'outflow_actual' => 'decimal:2',
        'closing_balance' => 'decimal:2',
        'variance_percentage' => 'decimal:2',
        'confidence_level' => 'integer',
    ];

    /**
     * Proje ile ilişki
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Tahmin edilen net nakit akış
     */
    public function getPlannedNetFlowAttribute()
    {
        return $this->inflow_planned - $this->outflow_planned;
    }

    /**
     * Gerçekleşen net nakit akış
     */
    public function getActualNetFlowAttribute()
    {
        return $this->inflow_actual - $this->outflow_actual;
    }

    /**
     * Sapma tutarı
     */
    public function getVarianceAmountAttribute()
    {
        return $this->getActualNetFlowAttribute() - $this->getPlannedNetFlowAttribute();
    }

    /**
     * Tahmin doğruluğu (0-100)
     */
    public function getAccuracyScoreAttribute()
    {
        if ($this->getPlannedNetFlowAttribute() == 0) {
            return 100;
        }

        $variance = abs($this->getVarianceAmountAttribute() / $this->getPlannedNetFlowAttribute());
        return max(0, min(100, 100 - ($variance * 100)));
    }

    /**
     * Tahmin durumu açıklaması
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'draft' => 'Taslak',
            'confirmed' => 'Onaylı',
            'revised' => 'Revize Edilmiş',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Güven seviyesi açıklaması
     */
    public function getConfidenceLabelAttribute(): string
    {
        if ($this->confidence_level >= 80) {
            return 'Çok Yüksek';
        } elseif ($this->confidence_level >= 60) {
            return 'Yüksek';
        } elseif ($this->confidence_level >= 40) {
            return 'Orta';
        } else {
            return 'Düşük';
        }
    }
}
