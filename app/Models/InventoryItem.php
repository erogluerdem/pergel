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
        'name', 'sku', 'description', 'category', 'price', 'quantity',
        'min_quantity', 'unit', 'supplier', 'location', 'status',
        'images', 'last_restocked', 'created_by', 'updated_by'
    ];
    
    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'last_restocked' => 'date'
    ];
    
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
            get: fn (string $value) => ucfirst($value),
        );
    }
}
