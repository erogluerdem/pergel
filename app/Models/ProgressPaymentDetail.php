<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressPaymentDetail extends Model
{
    protected $fillable = [
        'progress_payment_id',
        'description',
        'quantity',
        'unit_price',
        'amount',
        'percentage',
        'contract_term_reference',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'float',
        'unit_price' => 'float',
        'amount' => 'float',
        'percentage' => 'float',
    ];

    public $timestamps = false;

    /**
     * İlgili Hakediş
     */
    public function progressPayment(): BelongsTo
    {
        return $this->belongsTo(ProgressPayment::class);
    }

    /**
     * Tutarı format et
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2, ',', '.') . ' ₺';
    }
}
