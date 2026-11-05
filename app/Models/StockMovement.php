<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $fillable = [
        'stock_card_id',
        'type',
        'quantity',
        'unit_price',
        'total_amount',
        'reference_no',
        'description',
        'created_by'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function stockCard(): BelongsTo
    {
        return $this->belongsTo(StockCard::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}