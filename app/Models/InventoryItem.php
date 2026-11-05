<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'title', 'sku', 'code', 'description', 'category',
        'price', 'unit_cost', 'unit_price', 'quantity', 'stock',
        'min_quantity', 'unit', 'supplier', 'location', 'status',
        'images', 'last_restocked', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'last_restocked' => 'date'
    ];

    protected static function booted(): void
    {
        static::saving(function (InventoryItem $item) {
            if (!empty($item->name) && empty($item->title)) {
                $item->title = $item->name;
            } elseif (!empty($item->title) && empty($item->name)) {
                $item->name = $item->title;
            }

            if (!empty($item->sku) && empty($item->code)) {
                $item->code = $item->sku;
            } elseif (!empty($item->code) && empty($item->sku)) {
                $item->sku = $item->code;
            }

            if ($item->price !== null && (empty($item->unit_cost) || $item->unit_cost == 0)) {
                $item->unit_cost = $item->price;
            } elseif ($item->unit_cost !== null && (empty($item->price) || $item->price == 0)) {
                $item->price = $item->unit_cost;
            }

            if ($item->unit_price !== null && (empty($item->price) || $item->price == 0)) {
                $item->price = $item->unit_price;
                $item->unit_cost = $item->unit_price;
            } elseif ($item->price !== null && (empty($item->unit_price) || $item->unit_price == 0)) {
                $item->unit_price = $item->price;
            }

            if ($item->quantity !== null && (empty($item->stock) || $item->stock == 0)) {
                $item->stock = $item->quantity;
            } elseif ($item->stock !== null && (empty($item->quantity) || $item->quantity == 0)) {
                $item->quantity = $item->stock;
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->min_quantity;
    }

    public function totalValue(): float
    {
        return $this->quantity * $this->price;
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => ucfirst($value ?? 'active'),
        );
    }
}
