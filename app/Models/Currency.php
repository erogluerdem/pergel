<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    protected $fillable = ['code', 'name', 'symbol', 'exchange_rate', 'rate_updated_at', 'is_active'];

    protected $casts = [
        'exchange_rate' => 'decimal:6',
        'is_active' => 'boolean',
        'rate_updated_at' => 'datetime',
    ];

    public function currencyExchanges(): HasMany
    {
        return $this->hasMany(CurrencyExchange::class, 'from_currency', 'code');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
