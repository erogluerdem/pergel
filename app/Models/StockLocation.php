<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockLocation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'building',
        'floor',
        'zone',
        'address',
        'city',
        'country',
        'latitude',
        'longitude',
        'capacity',
        'capacity_unit',
        'temperature_controlled',
        'security_level',
        'manager_name',
        'manager_email',
        'manager_phone',
        'is_active',
        'working_hours',
        'notes',
        'image_path'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'capacity' => 'decimal:2',
        'is_active' => 'boolean',
        'working_hours' => 'array',
    ];

    protected $appends = ['capacity_utilization_percentage', 'stock_count'];

    public function stockCards(): HasMany
    {
        return $this->hasMany(StockCard::class, 'location', 'code');
    }

    public function getCapacityUtilizationPercentageAttribute()
    {
        if (!$this->capacity) return 0;
        
        $totalValue = $this->stockCards()->sum('total_value');
        return $this->capacity > 0 ? round(($totalValue / $this->capacity) * 100, 1) : 0;
    }

    public function getStockCountAttribute()
    {
        return $this->stockCards()->count();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public static function getTypes()
    {
        return [
            'warehouse' => 'Depo',
            'room' => 'Oda',
            'shelf' => 'Raf',
            'bin' => 'Kutu/Bölme'
        ];
    }

    public static function getSecurityLevels()
    {
        return [
            'low' => 'Düşük',
            'standard' => 'Standart',
            'high' => 'Yüksek',
            'restricted' => 'Kısıtlı'
        ];
    }

    public static function getTemperatureControlOptions()
    {
        return [
            'no' => 'Yok',
            'yes' => 'Var',
            'cold' => 'Soğuk (+2°C / +8°C)',
            'frozen' => 'Donmuş (-18°C)'
        ];
    }
}
