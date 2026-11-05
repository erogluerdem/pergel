<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockCard extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'category',
        'unit',
        'min_stock',
        'max_stock',
        'current_stock',
        'unit_price',
        'total_value',
        'location',
        'supplier',
        'status',
        'image_path',
        'barcode',
        'notes'
    ];

    protected $casts = [
        'min_stock' => 'decimal:2',
        'max_stock' => 'decimal:2',
        'current_stock' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_value' => 'decimal:2',
    ];

    protected $appends = ['stock_status', 'stock_level_percentage'];

    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getStockStatusAttribute()
    {
        if ($this->current_stock <= $this->min_stock) {
            return 'critical';
        } elseif ($this->current_stock <= ($this->min_stock * 1.2)) {
            return 'low';
        } elseif ($this->current_stock >= $this->max_stock) {
            return 'overstock';
        }
        return 'normal';
    }

    public function getStockLevelPercentageAttribute()
    {
        if ($this->max_stock > 0) {
            return round(($this->current_stock / $this->max_stock) * 100, 1);
        }
        return 0;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeCriticalStock($query)
    {
        return $query->whereRaw('current_stock <= min_stock');
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('current_stock <= min_stock * 1.2');
    }
}