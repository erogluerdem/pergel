<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyEvaluation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'evaluation_name',
        'description',
        'evaluation_date',
        'evaluated_by_id',
        'terrain_suitability',
        'accessibility_score',
        'infrastructure_readiness',
        'market_value_per_sqm',
        'total_market_value',
        'appreciation_potential_percent',
        'roi_projection',
        'payback_period_months',
        'investment_recommendation',
        'market_risk',
        'legal_risk',
        'development_risk',
        'risk_notes',
        'comparable_sales_count',
        'comparable_average_price_per_sqm',
        'strengths',
        'weaknesses',
        'opportunities',
        'threats',
        'recommendations',
        'is_final',
    ];

    protected $casts = [
        'evaluation_date' => 'date',
        'terrain_suitability' => 'decimal:2',
        'accessibility_score' => 'decimal:2',
        'infrastructure_readiness' => 'decimal:2',
        'market_value_per_sqm' => 'decimal:2',
        'total_market_value' => 'decimal:2',
        'appreciation_potential_percent' => 'decimal:2',
        'roi_projection' => 'decimal:2',
        'comparable_average_price_per_sqm' => 'decimal:2',
        'is_final' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function evaluatedBy()
    {
        return $this->belongsTo(User::class, 'evaluated_by_id');
    }

    // ==================== SCOPES ====================

    public function scopeFinal($query)
    {
        return $query->where('is_final', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('is_final', false);
    }

    public function scopeHighPotential($query)
    {
        return $query->where('appreciation_potential_percent', '>=', 15);
    }

    public function scopeHighROI($query)
    {
        return $query->where('roi_projection', '>=', 20);
    }

    public function scopeLowRisk($query)
    {
        return $query->where('market_risk', 'low')
            ->where('legal_risk', 'low')
            ->where('development_risk', 'low');
    }

    public function scopeRecommended($query)
    {
        return $query->where('investment_recommendation', 'recommended')
            ->orWhere('investment_recommendation', 'highly_recommended');
    }

    // ==================== ACCESSORS & MUTATORS ====================

    public function getOverallRiskLevelAttribute()
    {
        if ($this->market_risk === 'high' || $this->legal_risk === 'high' || $this->development_risk === 'high') {
            return 'high';
        }
        if ($this->market_risk === 'medium' || $this->legal_risk === 'medium' || $this->development_risk === 'medium') {
            return 'medium';
        }
        return 'low';
    }

    public function getScoreTrendAttribute()
    {
        $scores = [];
        if ($this->terrain_suitability) $scores[] = $this->terrain_suitability;
        if ($this->accessibility_score) $scores[] = $this->accessibility_score;
        if ($this->infrastructure_readiness) $scores[] = $this->infrastructure_readiness;

        return count($scores) > 0 ? array_sum($scores) / count($scores) : 0;
    }

    public function getInvestmentSuitabilityAttribute()
    {
        $score = 0;
        
        // Puanlama faktörleri
        if ($this->roi_projection >= 20) $score += 25;
        elseif ($this->roi_projection >= 15) $score += 20;
        elseif ($this->roi_projection >= 10) $score += 15;
        
        if ($this->appreciation_potential_percent >= 15) $score += 25;
        elseif ($this->appreciation_potential_percent >= 10) $score += 20;
        
        if ($this->market_risk === 'low') $score += 15;
        elseif ($this->market_risk === 'medium') $score += 8;
        
        if ($this->legal_risk === 'low') $score += 20;
        elseif ($this->legal_risk === 'medium') $score += 10;
        
        if ($this->development_risk === 'low') $score += 15;
        elseif ($this->development_risk === 'medium') $score += 8;
        
        return min($score, 100);
    }

    // ==================== CUSTOM METHODS ====================

    public function getInvestmentRecommendationBadge()
    {
        $badges = [
            'highly_recommended' => '<span class="badge bg-success">Yüksek Oranda Tavsiye Edilir</span>',
            'recommended' => '<span class="badge bg-info">Tavsiye Edilir</span>',
            'neutral' => '<span class="badge bg-warning">Nötr</span>',
            'not_recommended' => '<span class="badge bg-danger">Tavsiye Edilmez</span>',
        ];

        return $badges[$this->investment_recommendation] ?? '<span class="badge bg-light">Bilinmiyor</span>';
    }

    public function getRiskBadge($riskLevel)
    {
        $badges = [
            'low' => '<span class="badge bg-success">Düşük</span>',
            'medium' => '<span class="badge bg-warning">Orta</span>',
            'high' => '<span class="badge bg-danger">Yüksek</span>',
        ];

        return $badges[$riskLevel] ?? '<span class="badge bg-light">Bilinmiyor</span>';
    }

    public function getOverallRiskBadge()
    {
        return $this->getRiskBadge($this->overall_risk_level);
    }

    public function generateSWOTAnalysis()
    {
        return [
            'strengths' => explode("\n", $this->strengths ?? ''),
            'weaknesses' => explode("\n", $this->weaknesses ?? ''),
            'opportunities' => explode("\n", $this->opportunities ?? ''),
            'threats' => explode("\n", $this->threats ?? ''),
        ];
    }

    public function getScoreSummary()
    {
        return [
            'terrain_suitability' => $this->terrain_suitability,
            'accessibility_score' => $this->accessibility_score,
            'infrastructure_readiness' => $this->infrastructure_readiness,
            'average_score' => $this->score_trend,
        ];
    }

    public function getFinancialProjection()
    {
        return [
            'market_value_per_sqm' => $this->market_value_per_sqm,
            'total_market_value' => $this->total_market_value,
            'roi_projection' => $this->roi_projection . '%',
            'payback_period_months' => $this->payback_period_months,
            'appreciation_potential' => $this->appreciation_potential_percent . '%',
        ];
    }

    public function markAsFinal()
    {
        $this->update(['is_final' => true]);
    }

    public function makeDraft()
    {
        $this->update(['is_final' => false]);
    }

    public function getInvestmentDecision()
    {
        $suitability = $this->investment_suitability;

        if ($suitability >= 80) {
            return [
                'decision' => 'Yüksek Düzey Tavsiye',
                'color' => 'success',
                'score' => $suitability,
            ];
        } elseif ($suitability >= 60) {
            return [
                'decision' => 'Orta Düzey Tavsiye',
                'color' => 'info',
                'score' => $suitability,
            ];
        } elseif ($suitability >= 40) {
            return [
                'decision' => 'Dikkatli İnceleme Gerekli',
                'color' => 'warning',
                'score' => $suitability,
            ];
        } else {
            return [
                'decision' => 'Tavsiye Edilmiyor',
                'color' => 'danger',
                'score' => $suitability,
            ];
        }
    }
}
