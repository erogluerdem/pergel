<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTarget extends Model
{
    protected $fillable = [
        'user_id',
        'period_start',
        'period_end',
        'target_type', // 'revenue' or 'unit'
        'target_amount', // hedef değer (ciro veya birim sayısı)
        'description',
        'status' // 'active', 'completed', 'abandoned'
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'target_amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Gerçekleşen satış adedi (opportunity sayısı)
    public function getCompletedUnitsAttribute()
    {
        return Opportunity::where('owner_id', $this->user_id)
            ->where('stage', 'won')
            ->whereBetween('updated_at', [$this->period_start, $this->period_end])
            ->count();
    }

    // Gerçekleşen ciro (opportunity değerlerinin toplamı)
    public function getCompletedRevenueAttribute()
    {
        return Opportunity::where('owner_id', $this->user_id)
            ->where('stage', 'won')
            ->whereBetween('updated_at', [$this->period_start, $this->period_end])
            ->sum('estimated_value_kdv') ?? 0;
    }

    // Hedef başarı yüzdesi
    public function getCompletionPercentageAttribute()
    {
        if ($this->target_type === 'revenue') {
            return round(($this->completed_revenue / $this->target_amount) * 100, 2);
        } else {
            return round(($this->completed_units / $this->target_amount) * 100, 2);
        }
    }

    // Kalan hedef
    public function getRemainingAttribute()
    {
        if ($this->target_type === 'revenue') {
            return max(0, $this->target_amount - $this->completed_revenue);
        } else {
            return max(0, $this->target_amount - $this->completed_units);
        }
    }

    // Güncellenmiş opportunities sayısı
    public function getActivitiesCountAttribute()
    {
        return Opportunity::where('owner_id', $this->user_id)
            ->whereBetween('updated_at', [$this->period_start, $this->period_end])
            ->count();
    }
}
