<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurrencyExchange extends Model
{
    protected $fillable = [
        'invoice_id','from_currency','to_currency','from_amount','to_amount','exchange_rate','commission_percentage','commission_amount','exchange_date','notes'
    ];

    protected $casts = [
        'from_amount' => 'decimal:2',
        'to_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:6',
        'commission_percentage' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'exchange_date' => 'datetime',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
